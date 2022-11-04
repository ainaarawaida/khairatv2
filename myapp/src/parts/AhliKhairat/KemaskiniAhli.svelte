<script>
  import { onMount } from "svelte";
  import { onDestroy } from "svelte";
  import { getContext } from "svelte";
  import { createEventDispatcher } from "svelte";
  import { data } from "../../store.js";
  import KemaskiniAhliMaklumatPeribadi from "./KemaskiniAhliMaklumatPeribadi.svelte";
  import KemaskiniAhliTanggungan from "./KemaskiniAhliTanggungan.svelte";
  import KemaskiniAhliMaklumatBayaranAhli from "./KemaskiniAhliMaklumatBayaranAhli.svelte";

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

  let tanggungans;
  let xtanggungan = [];
  let fields = {};
  let submitpost = {
    error: [],
    post: "",
  };
  let visible = true;

  onMount(async () => {});

  const submitHandler = async (e) => {};

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
</script>

<main>
  <div class="container-fluid px-4">
    <h1 class="mt-3">Kemaskini Ahli</h1>
    <ol class="breadcrumb mb-1">
      <li class="breadcrumb-item active" />
    </ol>

    <div class="container px-1 my-2">
      <Accordion>
        <AccordionItem active header="Maklumat Peribadi ">
          <KemaskiniAhliMaklumatPeribadi />
        </AccordionItem>

        <AccordionItem header="Tanggungan">
          <KemaskiniAhliTanggungan />
        </AccordionItem>
        <AccordionItem header="Maklumat Bayaran Ahli">
          <KemaskiniAhliMaklumatBayaranAhli />
        </AccordionItem>
        <AccordionItem header="Terima Sumbangan">UCSB Library</AccordionItem>
        <AccordionItem header="Muatnaik Dokumen">UCSB Library</AccordionItem>
      </Accordion>
    </div>
  </div>
</main>

<!-- <LibLoader src="{mypluginurl}js/sb-forms-latest.js" libraryDetectionObject="sb-forms-latest" /> -->
