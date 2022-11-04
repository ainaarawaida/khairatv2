<script>
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
  import { onMount } from "svelte";
  import { onDestroy } from "svelte";
  import { data } from "../../store.js";
  import { getContext } from "svelte";
  import moment from "moment";
  let mypluginurl = getContext("mypluginurl");

  let myapiurl = getContext("myapiurl");
  let submitpost = {
    get_senarai_ahli: [],
    error: [],
  };
  let loading = true;
  let fields;
  let xtanggungan = [];
  let tanggungans = [];
  let visible;
  let open = false;
  let size = "xl";
  let unsubscribe;
  let khai_user;
  let passdata;
  onMount(async () => {
    loading = true;
    let myPromise = new Promise(function (myResolve, myReject) {
      unsubscribe = data.subscribe((value) => {
        //   console.log(value.store.passdata);
        myResolve(value); // when successful
      });
    });
    passdata = (await myPromise).store.passdata;
    khai_user = (await myPromise).khai_user;

    let apidata2 = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "TambahAhliTanggungan");
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

    tanggungans = JSON.parse(await apidata2).submitpost.tanggungans;
    loading = false;

    fields = {
      id: passdata,
      kariah_id: khai_user.data.kariah_id,
    };
  });

  onDestroy(() => {
    unsubscribe;
  });

  const submitHandler = async (e) => {
    submitpost.post = "";
    submitpost.error = [];
    loading = true;
    if (e.target.getAttribute("id") == "TanggunganForm") {
      if (tanggungans.length > 0) {
        let check = tanggungans.filter((wordx, v) => {
          if (
            wordx.noKadPengenalanTanggungan == fields.noKadPengenalanTanggungan
          ) {
            return wordx;
          }
        });

        if (check.length > 0) {
          submitpost.post = "true";
          submitpost.error.push({
            katalaluanSemula: "Alamat IC telah wujud",
          });
          loading = false;
          return;
        }
      }

      let apidata = new Promise(function (myResolve, myReject) {
        let dataArray = new FormData();
        dataArray.append("action", "TambahAhliTanggungan");
        dataArray.append("id", fields.id);
        dataArray.append("kariah_id", fields.kariah_id);
        dataArray.append("namaTanggungan", fields.namaTanggungan);
        dataArray.append(
          "noKadPengenalanTanggungan",
          fields.noKadPengenalanTanggungan
        );
        dataArray.append(
          "pertalianKeluargaTanggungan",
          fields.pertalianKeluargaTanggungan
        );
        dataArray.append(
          "nomborTelefonTanggungan",
          fields.nomborTelefonTanggungan
        );

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

      tanggungans = JSON.parse(await apidata).submitpost.tanggungans;

      fields.namaTanggungan = "";
      fields.noKadPengenalanTanggungan = "";
      fields.pertalianKeluargaTanggungan = "";
      fields.nomborTelefonTanggungan = "";
      submitpost.post = "Berjaya Tambah";
    } else if (e.target.getAttribute("id") == "UpdateTanggunganForm") {
      let apidata = new Promise(function (myResolve, myReject) {
        let dataArray = new FormData();
        dataArray.append("action", "KemaskiniAhliTanggungan");
        dataArray.append("id", fields.id);
        dataArray.append("kariah_id", fields.kariah_id);
        dataArray.append("namaTanggungan", fields.namaTanggungan);
        dataArray.append(
          "noKadPengenalanTanggungan",
          fields.noKadPengenalanTanggungan
        );
        dataArray.append(
          "pertalianKeluargaTanggungan",
          fields.pertalianKeluargaTanggungan
        );
        dataArray.append(
          "nomborTelefonTanggungan",
          fields.nomborTelefonTanggungan
        );

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

      tanggungans = JSON.parse(await apidata).submitpost.tanggungans;

      fields.namaTanggungan = "";
      fields.noKadPengenalanTanggungan = "";
      fields.pertalianKeluargaTanggungan = "";
      fields.nomborTelefonTanggungan = "";
      submitpost.post = "Berjaya Kemaskini";
    } else if (e.target.getAttribute("id") == "DeleteTanggungan") {
      if (xtanggungan.length > 0) {
        let myfilter;
        myfilter = tanggungans.filter((wordx, v) => {
          if (!xtanggungan[v]) {
            return wordx;
          }
        });
        tanggungans = myfilter;

        let apidata = new Promise(function (myResolve, myReject) {
          let dataArray = new FormData();
          dataArray.append("action", "DeleteTanggungan");
          dataArray.append("id", fields.id);
          dataArray.append("kariah_id", fields.kariah_id);
          dataArray.append("senaraiTanggungan", JSON.stringify(tanggungans));
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
        // submitpost = JSON.parse(await apidata).submitpost;
        submitpost.post = "Maklumat Tanggungan berjaya dibuang";
        xtanggungan = [];
      }
    }
    loading = false;
  };

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
    } else {
      if (e.target.getAttribute("name") == "cancel") {
        fields.namaTanggungan = "";
        fields.noKadPengenalanTanggungan = "";
        fields.pertalianKeluargaTanggungan = "";
        fields.nomborTelefonTanggungan = "";
      }
    }

    open = !open;
  };

  const kiraumur = (icno) => {
    if (icno) {
      let myear = icno.substring(0, 2);
      let mmonth = icno.substring(2, 4);
      let mday = icno.substring(4, 6);
      var dob = moment(`${myear}${mmonth}${mday}`, "YYMMDD").fromNow();
      // var dob = new Date("2022-03-25");
      // console.log(`${myear}${mmonth}${mday}`);
      var numb = dob.replace(/[^0-9]/g, "");
      return numb;
    } else {
      return "";
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
  <form id="TanggunganForm" on:submit|preventDefault={submitHandler}>
    <input class="form-control" id="id" type="hidden" bind:value={fields.id} />
    <input
      class="form-control"
      id="kariah_id"
      type="hidden"
      bind:value={fields.kariah_id}
    />
    <div class="row">
      <div class="col-sm">
        <label class="form-label" for="namaTanggungan">Nama Tanggungan</label>
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('namaTanggungan')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="namaTanggungan"
          type="text"
          placeholder="Nama Tanggungan"
          required
          bind:value={fields.namaTanggungan}
        />

        <div
          class="invalid-feedback"
          data-sb-feedback="namaTanggungan:required"
        >
          Nama Tanggungan is required.
        </div>
      </div>
      <div class="col-sm">
        <label class="form-label" for="noKadPengenalanTanggungan"
          >No. KP / Sijil Lahir Tanggungan</label
        >
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('noKadPengenalanTanggungan')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="noKadPengenalanTanggungan"
          type="number"
          min="100000000000"
          max="999999999999"
          placeholder="No. Kad Pengenalan Tanggungan"
          required
          bind:value={fields.noKadPengenalanTanggungan}
        />

        <div
          class="invalid-feedback"
          data-sb-feedback="noKadPengenalanTanggungan:required"
        >
          No. Kad Pengenalan Tanggungan is required.
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm">
        <label class="form-label" for="pertalianKeluargaTanggungan"
          >Pertalian Keluarga</label
        >
        <select
          bind:value={fields.pertalianKeluargaTanggungan}
          class="form-control"
          id="pertalianKeluargaTanggungan"
          name="pertalianKeluargaTanggungan"
          aria-required="true"
          aria-invalid="false"
          required
        >
          <option value="">Sila Pilih</option>
          <option value="PASANGAN">PASANGAN</option>
          <option value="ANAK">ANAK</option>
          <option value="IBU/BAPA">IBU/BAPA</option>
          <option value="DATUK/NENEK">DATUK/NENEK</option>
          <option value="LAIN-LAIN">LAIN-LAIN</option>
        </select>
      </div>
      <div class="col-sm">
        <label class="form-label" for="nomborTelefonTanggungan"
          >Nombor Telefon</label
        >
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('nomborTelefonTanggungan')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="nomborTelefonTanggungan"
          type="number"
          placeholder="Nombor Telefon"
          required
          bind:value={fields.nomborTelefonTanggungan}
        />

        <div
          class="invalid-feedback"
          data-sb-feedback="nomborTelefonTanggungan:required"
        >
          Nombor Telefon is required.
        </div>
      </div>
    </div>

    <div class="m-3" />

    <div class="d-grid">
      <button class="btn btn-success btn-lg" id="submitButton" type="submit"
        >Tambah</button
      >
    </div>
  </form>
  <div class="m-2" />
  <form id="DeleteTanggungan" on:submit|preventDefault={submitHandler}>
    <Table responsive>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Pertalian</th>
          <th>No. KP</th>
          <th>Telefon</th>
          <th>Umur</th>
          <th>Yuran</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        {#if tanggungans}
          {#each tanggungans as tanggungan, i}
            <tr>
              <th scope="row"
                ><input
                  type="checkbox"
                  name="checked_id[]"
                  bind:checked={xtanggungan[i]}
                /></th
              >
              <td>{tanggungan.namaTanggungan}</td>
              <td>{tanggungan.pertalianKeluargaTanggungan}</td>
              <td>{tanggungan.noKadPengenalanTanggungan}</td>
              <td>{tanggungan.nomborTelefonTanggungan}</td>
              <td>{kiraumur(tanggungan.noKadPengenalanTanggungan)}</td>
              <td />
              <td
                ><a data-id={i} href="#" on:click={(e) => toggle(e)}
                  ><i class="fa fa-search" /></a
                ></td
              >
            </tr>
          {/each}
        {/if}
      </tbody>
    </Table>
    <button class="btn btn-danger btn-lg" id="submitButton" type="submit"
      >Buang</button
    >
  </form>

  <div>
    <Modal size="lg" isOpen={open} {toggle}>
      <form id="UpdateTanggunganForm" on:submit|preventDefault={submitHandler}>
        <ModalHeader {toggle}>Maklumat Tanggungan</ModalHeader>
        <ModalBody>
          <input
            class="form-control"
            id="id"
            type="hidden"
            bind:value={fields.id}
          />
          <input
            class="form-control"
            id="kariah_id"
            type="hidden"
            bind:value={fields.kariah_id}
          />
          <div class="row">
            <div class="col-sm">
              <label class="form-label" for="namaTanggungan"
                >Nama Tanggungan</label
              >
              <input
                class="form-control {submitpost.error.findIndex((p) =>
                  p.hasOwnProperty('namaTanggungan')
                ) != -1
                  ? 'is-invalid'
                  : ''}"
                id="namaTanggungan"
                type="text"
                placeholder="Nama Tanggungan"
                required
                bind:value={fields.namaTanggungan}
              />

              <div
                class="invalid-feedback"
                data-sb-feedback="namaTanggungan:required"
              >
                Nama Tanggungan is required.
              </div>
            </div>
            <div class="col-sm">
              <label class="form-label" for="noKadPengenalanTanggungan"
                >No. KP / Sijil Lahir Tanggungan</label
              >
              <input
                class="form-control {submitpost.error.findIndex((p) =>
                  p.hasOwnProperty('noKadPengenalanTanggungan')
                ) != -1
                  ? 'is-invalid'
                  : ''}"
                id="noKadPengenalanTanggungan"
                type="number"
                min="100000000000"
                max="999999999999"
                placeholder="No. Kad Pengenalan Tanggungan"
                required
                bind:value={fields.noKadPengenalanTanggungan}
              />

              <div
                class="invalid-feedback"
                data-sb-feedback="noKadPengenalanTanggungan:required"
              >
                No. Kad Pengenalan Tanggungan is required.
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm">
              <label class="form-label" for="pertalianKeluargaTanggungan"
                >Pertalian Keluarga</label
              >
              <select
                bind:value={fields.pertalianKeluargaTanggungan}
                class="form-control"
                id="pertalianKeluargaTanggungan"
                name="pertalianKeluargaTanggungan"
                aria-required="true"
                aria-invalid="false"
                required
              >
                <option value="">Sila Pilih</option>
                <option value="PASANGAN">PASANGAN</option>
                <option value="ANAK">ANAK</option>
                <option value="IBU/BAPA">IBU/BAPA</option>
                <option value="DATUK/NENEK">DATUK/NENEK</option>
                <option value="LAIN-LAIN">LAIN-LAIN</option>
              </select>
            </div>
            <div class="col-sm">
              <label class="form-label" for="nomborTelefonTanggungan"
                >Nombor Telefon</label
              >
              <input
                class="form-control {submitpost.error.findIndex((p) =>
                  p.hasOwnProperty('nomborTelefonTanggungan')
                ) != -1
                  ? 'is-invalid'
                  : ''}"
                id="nomborTelefonTanggungan"
                type="number"
                placeholder="Nombor Telefon"
                required
                bind:value={fields.nomborTelefonTanggungan}
              />

              <div
                class="invalid-feedback"
                data-sb-feedback="nomborTelefonTanggungan:required"
              >
                Nombor Telefon is required.
              </div>
            </div>
          </div>

          <div class="m-3" />
        </ModalBody>
        <ModalFooter>
          <Button type="submit" color="primary" on:click={toggle}
            >Kemaskini</Button
          >
          <Button
            type="button"
            name="cancel"
            color="secondary"
            on:click={toggle}>Cancel</Button
          >
        </ModalFooter>
      </form>
    </Modal>
  </div>
{/if}
