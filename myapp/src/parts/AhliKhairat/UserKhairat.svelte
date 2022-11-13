<script>
  import { onMount } from "svelte";
  import { onDestroy } from "svelte";
  import { getContext } from "svelte";
  import { createEventDispatcher } from "svelte";
  import { data } from "../../store.js";
  import UserKemaskiniAhliMaklumatPeribadi from "./UserKemaskiniAhliMaklumatPeribadi.svelte";
  import UserKemaskiniAhliTanggungan from "./UserKemaskiniAhliTanggungan.svelte";
  import UserKemaskiniAhliMaklumatBayaranAhli from "./UserKemaskiniAhliMaklumatBayaranAhli.svelte";
  import UserKemaskiniAhliTerimaSumbangan from "./UserKemaskiniAhliTerimaSumbangan.svelte";
  import UserKemaskiniAhliMuatnaikDokumen from "./UserKemaskiniAhliMuatnaikDokumen.svelte";

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

  let mypluginurl = getContext("mypluginurl");
  let loading;
  let tanggungans;
  let xtanggungan = [];
  let unsubscribe;
  let khai_user;
  let fields = {};
  let submitpost = {
    error: [],
    post: "",
  };
  let visible = true;

  onMount(async () => {});

  onDestroy(() => {
    unsubscribe;
  });
  const submitHandler = async (e) => {};

  let hantarsemakan = async (e) => {
    let myPromise = new Promise(function (myResolve, myReject) {
      unsubscribe = data.subscribe((value) => {
        myResolve(value); // when successful
      });
    });
    khai_user = (await myPromise).khai_user;

    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "UserKhairat");
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

    khai_user = JSON.parse(await apidata).khai_user;
    data.update((value) => {
      return { ...value, khai_user };
    });

    submitpost.post = "Maklumat Ahli Berjaya dihantar untuk semakan";
  };

  const closealert = async () => {
    visible = false;
    submitpost.error = [];
    submitpost.post = "";
  };
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

        <Accordion>
          <AccordionItem header="Maklumat Peribadi ">
            <UserKemaskiniAhliMaklumatPeribadi />
          </AccordionItem>

          <AccordionItem header="Tanggungan">
            <UserKemaskiniAhliTanggungan />
          </AccordionItem>
          <!-- 
          <AccordionItem header="Maklumat Bayaran Ahli">
          <UserKemaskiniAhliMaklumatBayaranAhli />
        </AccordionItem>
        <AccordionItem header="Terima Sumbangan">
          <UserKemaskiniAhliTerimaSumbangan />
        </AccordionItem> 
      -->
          <AccordionItem header="Muatnaik Dokumen">
            <UserKemaskiniAhliMuatnaikDokumen />
          </AccordionItem>
        </Accordion>

        <div class="m-3" />
        * Saya mengakui semua maklumat yang disertakan di sini adalah benar ;<br
        />
        <button
          on:click={(e) => hantarsemakan(e)}
          class="btn btn-success btn-lg"
          id="submitButton"
          type="submit">Hantar untuk semakan</button
        >
      {/if}
    </div>
  </div>
</main>

<!-- <LibLoader src="{mypluginurl}js/sb-forms-latest.js" libraryDetectionObject="sb-forms-latest" /> -->
