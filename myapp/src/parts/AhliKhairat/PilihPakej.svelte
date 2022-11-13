<script>
  import { onMount } from "svelte";
  import { onDestroy } from "svelte";
  import { getContext } from "svelte";
  import { createEventDispatcher } from "svelte";
  import { data } from "../../store.js";
  import { Alert } from "sveltestrap";
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

  onMount(async () => {
    unsubscribe = data.subscribe((value) => {
      khai_user = value.khai_user;
    });
    loading = true;
    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "PilihPakej");
      dataArray.append("id", khai_user.ID);
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
      dataArray.append("action", "PilihPakej");
      dataArray.append("id", khai_user.ID);
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

    data.update((value) => {
      return { ...value, khai_user };
    });

    dispatch("menuChange", "User Khairat - Maklumat Ahli");
  };

  const closealert = async () => {
    visible = false;
    submitpost.error = [];
    submitpost.post = "";
  };
</script>

<main>
  <div class="container-fluid px-4">
    <h1 class="mt-4">Pilih Pakej</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item active" />
    </ol>
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
        {#if get_jenisyuran}
          {#each get_jenisyuran as yuran, i}
            <div class="row">
              <div class="col-10">
                <div class="card mb-4 rounded-3 shadow-sm">
                  <div class="card-header py-3">
                    <h4 class="my-0 fw-normal">{yuran.Tajuk}</h4>
                  </div>
                  <div class="card-body">
                    <h1 class="card-title pricing-card-title">
                      RM{yuran.Jumlah}<small class="text-muted fw-light"
                        >/ {yuran.jenisYuran}</small
                      >
                    </h1>
                    {yuran.keteranganYuran}
                  </div>
                </div>
              </div>
              <div class="col-2">
                <input
                  style="width: 80%; height: 80%;cursor: pointer;"
                  class="form-check-input"
                  type="checkbox"
                  id="pakej{i}"
                  bind:group={selectionjenisyuran}
                  value={yuran.ID}
                />
              </div>
            </div>
          {/each}
        {/if}

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
