<script>
  import { onMount } from "svelte";
  import { onDestroy } from "svelte";
  import { getContext } from "svelte";
  import { createEventDispatcher } from "svelte";
  import { data } from "../../store.js";
  import {
    Table,
    Alert,
    Accordion,
    AccordionItem,
    Button,
    Modal,
    ModalBody,
    ModalFooter,
    ModalHeader,
  } from "sveltestrap";
  let dispatch = createEventDispatcher();
  let unsubscribe;
  let khai_user;
  let loading;
  let myapiurl = getContext("myapiurl");
  let submitpost = {
    post: "",
    error: [],
  };
  let selectionjenisyuran = [];
  let mypluginurl = getContext("mypluginurl");
  let visible;
  let fields = {
    pakej: "",
  };
  let get_jenisyuran;
  let passdata;

  onMount(async () => {
    let myPromise = new Promise(function (myResolve, myReject) {
      unsubscribe = data.subscribe((value) => {
        khai_user = value.khai_user;
        myResolve(value); // when successful
      });
    });
    passdata = (await myPromise).store.passdata;
    loading = true;
    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "KemaskiniAhliPakejYuran");
      dataArray.append("id", passdata);
      dataArray.append("kariah_id", khai_user.data.kariah_id);
      fetch(myapiurl, {
        method: "POST",
        body: dataArray,
      })
        .then((response) => response.json())
        .then((result) => {
          myResolve(result);
        })
        .catch((error) => console.log("error", error));
    });
    get_jenisyuran = JSON.parse(await apidata).submitpost.get_jenisyuran;
    khai_user = JSON.parse(await apidata).khai_user;
    selectionjenisyuran = khai_user.data.pakej;
    loading = false;
  });

  let terimapakej = async (pakej_id) => {};
  onDestroy(() => {
    unsubscribe;
  });

  const submitHandler = async (e) => {
    console.log(selectionjenisyuran.length);
    if (selectionjenisyuran.length == 0) {
      submitpost.post = "true";
      submitpost.error.push({
        katalaluanSemula: "Pilih Pakej Anda",
      });
      return;
    }
    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "KemaskiniAhliPakejYuran");
      dataArray.append("id", passdata);
      dataArray.append("kariah_id", khai_user.data.kariah_id);
      dataArray.append("pakej", JSON.stringify(selectionjenisyuran));
      fetch(myapiurl, {
        method: "POST",
        body: dataArray,
      })
        .then((response) => response.json())
        .then((result) => {
          myResolve(result);
        })
        .catch((error) => console.log("error", error));
    });
    khai_user = JSON.parse(await apidata).khai_user;

    submitpost.post = "Pakej Berjaya kemaskini";

    data.update((value) => {
      return { ...value, khai_user };
    });

    // dispatch("menuChange", "User Khairat - Maklumat Ahli");
  };

  const closealert = async () => {
    visible = false;
    submitpost.error = [];
    submitpost.post = "";
  };
</script>

<main>
  <div class="container-fluid px-4">
    {#if loading === true}
      <div class="d-flex justify-content-center">
        <img class="" src="{mypluginurl}assets/img/loading.gif" alt="" />
      </div>
    {:else}
      {#if submitpost.post && submitpost.post != ""}
        {#if submitpost.error.length > 0}
          {#each submitpost.error as cat}
            <Alert toggle={() => closealert()} color="danger" dismissible
              >{Object.values(cat)}</Alert
            >
          {/each}
        {:else}
          <Alert toggle={() => closealert()} color="info" dismissible
            >{submitpost.post}</Alert
          >
        {/if}
      {/if}
      <form id="PilihPakej" on:submit|preventDefault={submitHandler}>
        <Table responsive>
          <thead>
            <tr>
              <th>#</th>
              <th>Tajuk</th>
              <th>Jumlah</th>
              <th>Jenis Yuran</th>
              <th>Keterangan Yuran</th>
              <th>Pilih</th>
            </tr>
          </thead>
          <tbody>
            {#if get_jenisyuran}
              {#each get_jenisyuran as yuran, i}
                <tr>
                  <td>{i + 1}</td>
                  <td>{yuran.Tajuk}</td>
                  <td>{yuran.Jumlah}</td>
                  <td>{yuran.jenisYuran}</td>
                  <td>{yuran.keteranganYuran}</td>
                  <td>
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="pakej{i}"
                      bind:group={selectionjenisyuran}
                      value={yuran.ID}
                    /></td
                  >
                </tr>
              {/each}
            {/if}
          </tbody>
        </Table>

        <div class="row">
          <div class="col-sm">
            <button type="submit" class="w-100 btn btn-lg btn-success"
              >Terima</button
            >
          </div>
        </div>
      </form>
    {/if}
  </div>
</main>
