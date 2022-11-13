<script>
  import { onMount } from "svelte";
  import { onDestroy } from "svelte";
  import { data } from "../../store.js";
  import { getContext } from "svelte";
  import { createEventDispatcher } from "svelte";
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
  let mypluginurl = getContext("mypluginurl");
  let passdata = "";
  let fields;
  let unsubscribe;
  let myapiurl = getContext("myapiurl");
  let loading = true;
  let visible;
  let khai_user = {
    data: {
      kariah_id: "",
    },
  };
  let submitpost = {
    get_senarai_ahli: [],
    error: [],
  };

  onMount(async () => {
    let myPromise = new Promise(function (myResolve, myReject) {
      unsubscribe = data.subscribe((value) => {
        myResolve(value); // when successful
      });
    });
    passdata = (await myPromise).store.passdata;
    khai_user = (await myPromise).khai_user;

    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "UserKemaskiniAhli");
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
    submitpost = JSON.parse(await apidata).submitpost;

    let year = submitpost.get_senarai_ahli[0].user_registered.substring(0, 4);
    let month = submitpost.get_senarai_ahli[0].user_registered.substring(5, 7);
    let day = submitpost.get_senarai_ahli[0].user_registered.substring(8, 10);

    fields = {
      id: khai_user.ID,
      kariah_id: khai_user.data.kariah_id,
      namaPenuh: submitpost.get_senarai_ahli[0].display_name,
      emel: submitpost.get_senarai_ahli[0].user_email,
      katalaluan: "",
      katalaluanSemula: "",
      noKadPengenalanBaru: submitpost.get_senarai_ahli[0].noKadPengenalanBaru,
      nomborTelefon: submitpost.get_senarai_ahli[0].nomborTelefon,
      tarikhDaftar: [year, month, day].join("-"),
      nomborKeahlian: submitpost.get_senarai_ahli[0].no_ahli,
      jenisAhli: submitpost.get_senarai_ahli[0].role,
      namaKariah: khai_user.data.namaKariah,
      statusAhli: submitpost.get_senarai_ahli[0].stage_daftar <= 6 ? "1" : "2",
      catatan: submitpost.get_senarai_ahli[0].catatan,
      alamat: submitpost.get_senarai_ahli[0].alamat,
    };
    loading = false;
  });

  onDestroy(() => {
    unsubscribe;
  });

  const submitHandler = async (e) => {
    submitpost.post = "";
    submitpost.error = [];
    // console.log(e.target.getAttribute("id"));
    if (e.target.getAttribute("id") == "maklumatPeribadi") {
      if (fields.katalaluan !== fields.katalaluanSemula) {
        submitpost.error.push({
          katalaluanSemula: "Kata Laluan Ulangan tidak sama",
        });
      }

      if (submitpost.error.length > 0) {
        return;
      }

      let apidata = new Promise(function (myResolve, myReject) {
        let dataArray = new FormData();
        dataArray.append("action", "UserKemaskiniAhli");
        dataArray.append("id", fields.id);
        dataArray.append("kariah_id", fields.kariah_id);
        dataArray.append("namaPenuh", fields.namaPenuh);
        dataArray.append("emel", fields.emel);
        dataArray.append("katalaluan", fields.katalaluan);
        dataArray.append("katalaluanSemula", fields.katalaluanSemula);
        dataArray.append("noKadPengenalanBaru", fields.noKadPengenalanBaru);
        dataArray.append("nomborTelefon", fields.nomborTelefon);
        dataArray.append("tarikhDaftar", fields.tarikhDaftar);
        dataArray.append("nomborKeahlian", fields.nomborKeahlian);
        dataArray.append("jenisAhli", fields.jenisAhli);
        dataArray.append("namaKariah", fields.namaKariah);
        dataArray.append("alamat", fields.alamat);
        dataArray.append("catatan", fields.catatan);

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

      submitpost = JSON.parse(await apidata).submitpost;
      khai_user = JSON.parse(await apidata).khai_user;

      data.update((value) => {
        return { ...value, khai_user };
      });

      submitpost.post = "true";

      if (submitpost.ID != undefined) {
        fields.nomborKeahlian = `000${submitpost.ID}`;
      }

      if (submitpost.error.length == 0) {
        submitpost.post = "Kemaskini Berjaya";
      }
    }
  };
  const closealert = async () => {
    visible = false;
    submitpost.error = [];
    submitpost.post = "";
  };
</script>

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

  <form id="maklumatPeribadi" on:submit|preventDefault={submitHandler}>
    <input class="form-control" id="id" type="hidden" bind:value={fields.id} />
    <input
      class="form-control"
      id="kariah_id"
      type="hidden"
      bind:value={fields.kariah_id}
    />
    <div class="row">
      <div class="col-sm">
        <label class="form-label" for="namaPenuh">Nama Penuh</label>
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('namaPenuh')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="namaPenuh"
          type="text"
          placeholder="Nama Penuh"
          required
          bind:value={fields.namaPenuh}
        />

        <div class="invalid-feedback" data-sb-feedback="namaPenuh:required">
          Nama Penuh is required.
        </div>
      </div>
      <div class="col-sm">
        <label class="form-label" for="emel">Emel</label>
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('emel')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="emel"
          type="email"
          placeholder="Emel"
          required
          bind:value={fields.emel}
        />

        <div class="invalid-feedback" data-sb-feedback="emel:required">
          Emel is required.
        </div>
        <div class="invalid-feedback" data-sb-feedback="emel:email">
          Emel Email is not valid.
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm">
        <label class="form-label" for="katalaluan"
          >Katalaluan <i>(Kosongkan jika tidak ingin ubah)</i></label
        >
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('katalaluan')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="katalaluan"
          type="password"
          placeholder="Katalaluan"
          bind:value={fields.katalaluan}
        />

        <div class="invalid-feedback" data-sb-feedback="katalaluan:required">
          Katalaluan is required.
        </div>
      </div>
      <div class="col-sm">
        <label class="form-label" for="katalaluanSemula"
          >Katalaluan Semula <i>(Kosongkan jika tidak ingin ubah)</i></label
        >
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('katalaluanSemula')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="katalaluanSemula"
          type="password"
          placeholder="Katalaluan Semula"
          bind:value={fields.katalaluanSemula}
        />

        <div
          class="invalid-feedback"
          data-sb-feedback="katalaluanSemula:required"
        >
          Katalaluan Semula is required.
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm">
        <label class="form-label" for="noKadPengenalanBaru"
          >No. Kad Pengenalan Baru</label
        >
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('noKadPengenalanBaru')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="noKadPengenalanBaru"
          type="number"
          min="100000000000"
          max="999999999999"
          placeholder="No. Kad Pengenalan Baru"
          required
          bind:value={fields.noKadPengenalanBaru}
        />

        <div
          class="invalid-feedback"
          data-sb-feedback="noKadPengenalanBaru:required"
        >
          No. Kad Pengenalan Baru is required.
        </div>
      </div>
      <div class="col-sm">
        <label class="form-label" for="nomborTelefon">Nombor Telefon</label>
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('nomborTelefon')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="nomborTelefon"
          type="number"
          placeholder="Nombor Telefon"
          required
          bind:value={fields.nomborTelefon}
        />

        <div class="invalid-feedback" data-sb-feedback="nomborTelefon:required">
          Nombor Telefon is required.
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm">
        <label class="form-label" for="tarikhDaftar">Tarikh Daftar</label>
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('tarikhDaftar')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="tarikhDaftar"
          type="date"
          placeholder="Tarikh Daftar"
          required
          bind:value={fields.tarikhDaftar}
        />

        <div class="invalid-feedback" data-sb-feedback="tarikhDaftar:required">
          Tarikh Daftar is required.
        </div>
      </div>
      <div class="col-sm">
        <label class="form-label" for="nomborKeahlian"
          >Nombor Keahlian (Kosongkan jika ingin auto)</label
        >
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('nomborKeahlian')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="nomborKeahlian"
          type="text"
          placeholder="Kosongkan untuk auto nombor keahlian"
          bind:value={fields.nomborKeahlian}
        />

        <div
          class="invalid-feedback"
          data-sb-feedback="nomborKeahlian:required"
        >
          Nombor Keahlian is required.
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm">
        <label class="form-label" for="jenisAhliView">Jenis Ahli</label>
        <select
          disabled
          bind:value={fields.jenisAhli}
          class="form-control"
          id="jenisAhli"
          name="jenisAhli"
          aria-required="true"
          aria-invalid="false"
          required
        >
          <option value="">Pilih Ahli</option>
          <option value="1">Pentadbir Sistem</option>
          <option value="2">Ahli Biasa</option>
          <option value="3">Ahli Asnaf</option>
        </select>
      </div>
      <div class="col-sm">
        <label class="form-label" for="namaKariah">Kariah</label>
        <input
          readonly
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('namaKariah')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="namaKariah"
          type="text"
          placeholder="Kariah"
          required
          bind:value={fields.namaKariah}
        />

        <div class="invalid-feedback" data-sb-feedback="namaKariah:required">
          Kariah is required.
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm">
        <label class="form-label" for="alamat">Alamat</label>
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('alamat')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="alamat"
          type="text"
          placeholder="Alamat penuh"
          required
          bind:value={fields.alamat}
        />

        <div class="invalid-feedback" data-sb-feedback="alamat:required">
          Alamat is required.
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm">
        <label class="form-label" for="statusAhli">Status Ahli</label>
        <select
          disabled
          bind:value={fields.statusAhli}
          class="form-control"
          id="statusAhli"
          name="statusAhli"
          aria-required="true"
          aria-invalid="false"
          required
        >
          <option value="1">Aktif</option>
          <option value="2">Tidak Aktif</option>
        </select>
      </div>
      <div class="col-sm">
        <label class="form-label" for="catatan">Catatan</label>
        <textarea
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('catatan')
          ) != -1
            ? 'is-invalid'
            : ''}"
          rows="2"
          name="catatan"
          id="catatan"
          placeholder="Catatan"
          bind:value={fields.catatan}
        />
      </div>
    </div>

    <div class="m-5" />

    <div class="d-grid">
      <button class="btn btn-primary btn-lg" id="submitButton" type="submit"
        >Simpan</button
      >
    </div>
  </form>
{/if}
