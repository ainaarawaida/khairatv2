<script>
  import { onMount } from "svelte";
  import { onDestroy } from "svelte";
  import { getContext } from "svelte";
  import { createEventDispatcher } from "svelte";
  import { data } from "../../store.js";
  import KemaskiniAhliMaklumatPeribadi from "./KemaskiniAhliMaklumatPeribadi.svelte";
  import KemaskiniAhliTanggungan from "./KemaskiniAhliTanggungan.svelte";
  import KemaskiniAhliPakejYuran from "./KemaskiniAhliPakejYuran.svelte";
  import KemaskiniAhliMaklumatBayaranAhli from "./KemaskiniAhliMaklumatBayaranAhli.svelte";
  import KemaskiniAhliTerimaSumbangan from "./KemaskiniAhliTerimaSumbangan.svelte";
  import KemaskiniAhliMuatnaikDokumen from "./KemaskiniAhliMuatnaikDokumen.svelte";

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
  let myapiurl = getContext("myapiurl");
  let unsubscribe;
  let tanggungans;
  let passdata;
  let mypluginurl = getContext("mypluginurl");
  let khai_user;
  let loading;
  let xtanggungan = [];
  let fields = {};
  let submitpost = {
    error: [],
    post: "",
  };
  let visible = true;

  onMount(async () => {
    let myPromise = new Promise(function (myResolve, myReject) {
      unsubscribe = data.subscribe((value) => {
        myResolve(value); // when successful
      });
    });
    passdata = (await myPromise).store.passdata;
    khai_user = (await myPromise).khai_user;
  });

  const submitHandler = async (e) => {
    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "SemakanAdmin");
      dataArray.append("id", passdata);
      dataArray.append("pelulus", khai_user.ID);
      dataArray.append("kariah_id", khai_user.data.kariah_id);
      dataArray.append("semakanadmin", fields.semakanadmin);
      dataArray.append("notasemakanadmin", fields.notasemakanadmin);
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

    submitpost.post = "Maklumat Ahli Telah disemak";
  };

  const closealert = async () => {
    visible = false;
    submitpost.error = [];
    submitpost.post = "";
    // console.log("submitpost", submitpost);
  };

  let open = false;
  let size = "xl";
  const toggle = (e) => {
    let getid = e.target.parentElement.getAttribute("data-id");
    if (getid == null) {
      getid = e.target.getAttribute("data-id");
    }
    if (getid == null) {
      getid = e.target.parentElement.parentElement.getAttribute("data-id");
    }

    let myfilter;

    if (getid) {
      myfilter = tanggungans.filter((wordx, v) => {
        if (getid == v) {
          return wordx;
        }
      });

      fields.namaTanggungan = myfilter[0].namaTanggungan;
      fields.noKadPengenalanTanggungan = myfilter[0].noKadPengenalanTanggungan;
      fields.pertalianKeluargaTanggungan =
        myfilter[0].pertalianKeluargaTanggungan;
      fields.nomborTelefonTanggungan = myfilter[0].nomborTelefonTanggungan;
    }

    open = !open;
  };

  let lulussemakan = async (e) => {};
</script>

<main>
  <div class="container-fluid px-4">
    <h1 class="mt-3">Kemaskini Ahli</h1>
    <ol class="breadcrumb mb-1">
      <li class="breadcrumb-item active" />
    </ol>

    <div class="container px-1 my-2">
      {#if loading === true}
        <div class="d-flex justify-content-center">
          <img class="" src="{mypluginurl}assets/img/loading.gif" alt="" />
        </div>
      {:else}
        <Accordion>
          <AccordionItem header="Maklumat Peribadi ">
            <KemaskiniAhliMaklumatPeribadi />
          </AccordionItem>

          <AccordionItem header="Tanggungan">
            <KemaskiniAhliTanggungan />
          </AccordionItem>
          <AccordionItem header="Pakej Yuran">
            <KemaskiniAhliPakejYuran />
          </AccordionItem>
          <AccordionItem header="Maklumat Bayaran Ahli">
            <KemaskiniAhliMaklumatBayaranAhli />
          </AccordionItem>
          <AccordionItem header="Terima Sumbangan">
            <KemaskiniAhliTerimaSumbangan />
          </AccordionItem>
          <AccordionItem header="Muatnaik Dokumen">
            <KemaskiniAhliMuatnaikDokumen />
          </AccordionItem>
        </Accordion>
        <div class="m-3" />

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
        <form id="semakanadmin" on:submit|preventDefault={submitHandler}>
          <div class="row">
            <div class="col">
              <label class="form-label text-danger" for="semakanadmin"
                >Pilih langkah seterusnya :</label
              >
            </div>
          </div>
          <div class="row">
            <div class="col-sm col-sm-5">
              <select
                bind:value={fields.semakanadmin}
                class="form-control"
                id="semakanadmin"
                name="semakanadmin"
                aria-required="true"
                aria-invalid="false"
                required
              >
                <option value="4">Lulus Semakan Maklumat Ahli</option>
                <option value="2">Gagal Semakan Maklumat Ahli</option>
                <option value="1">Tukar pakej Yuran</option>
              </select>
            </div>
            <div class="col-sm col-sm-5">
              <textarea
                class="form-control {submitpost.error.findIndex((p) =>
                  p.hasOwnProperty('notasemakanadmin')
                ) != -1
                  ? 'is-invalid'
                  : ''}"
                id="notasemakanadmin"
                type="text"
                placeholder="Nota Untuk Ahli"
                style="height: 10rem;"
                data-sb-validations="required"
                bind:value={fields.notasemakanadmin}
              />
            </div>
            <div class="col col-sm-2">
              <button
                class="btn btn-success btn-lg"
                id="submitButton"
                type="submit">Hantar</button
              >
            </div>
          </div>
        </form>
      {/if}
    </div>
  </div>
</main>

<!-- <LibLoader src="{mypluginurl}js/sb-forms-latest.js" libraryDetectionObject="sb-forms-latest" /> -->
