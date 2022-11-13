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
  let dokumens = [];
  let visible;
  let open = false;
  let size = "xl";
  let unsubscribe;
  let khai_user;
  let passdata;
  onMount(async () => {
    let year = new Date().toISOString().substring(0, 4);
    let month = new Date().toISOString().substring(5, 7);
    let day = new Date().toISOString().substring(8, 10);

    loading = true;
    let myPromise = new Promise(function (myResolve, myReject) {
      unsubscribe = data.subscribe((value) => {
        //   console.log(value.store.passdata);
        myResolve(value); // when successful
      });
    });
    passdata = (await myPromise).store.passdata;
    khai_user = (await myPromise).khai_user;

    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "KemaskiniAhliMuatnaikDokumen");
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

    dokumens = JSON.parse(await apidata).submitpost.dokumens;

    loading = false;

    fields = {
      id: passdata,
      kariah_id: khai_user.data.kariah_id,
      tajukDokumen: "",
    };
  });

  onDestroy(() => {
    unsubscribe;
  });

  const submitHandler = async (e) => {
    submitpost.post = "";
    submitpost.error = [];
    loading = true;
    if (e.target.getAttribute("id") == "MuatnaikDokumen") {
      let apidata = new Promise(function (myResolve, myReject) {
        let dataArray = new FormData();
        dataArray.append("action", "TambahMuatnaikDokumen");
        dataArray.append("id", fields.id);
        dataArray.append("kariah_id", fields.kariah_id);
        dataArray.append("tajukDokumen", fields.tajukDokumen);
        dataArray.append("jenisDokumen", fields.jenisDokumen[0]);

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

      dokumens = JSON.parse(await apidata).submitpost.dokumens;
      console.log(dokumens);
      fields.tajukDokumen = "";
      fields.noKadPengenalanTanggungan = "";
      fields.pertalianKeluargaTanggungan = "";
      fields.nomborTelefonTanggungan = "";
      submitpost.post = "Berjaya Tambah";
    } else if (
      e.target.getAttribute("id") == "UpdateKemaskiniAhliTerimaSumbangan"
    ) {
      let apidata = new Promise(function (myResolve, myReject) {
        let dataArray = new FormData();
        dataArray.append("action", "KemaskiniAhliTanggungan");
        dataArray.append("id", fields.id);
        dataArray.append("kariah_id", fields.kariah_id);
        dataArray.append("tajukDokumen", fields.tajukDokumen);
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

      dokumens = JSON.parse(await apidata).submitpost.dokumens;

      fields.tajukDokumen = "";
      fields.noKadPengenalanTanggungan = "";
      fields.pertalianKeluargaTanggungan = "";
      fields.nomborTelefonTanggungan = "";
      submitpost.post = "Berjaya Kemaskini";
      open = false;
    } else if (e.target.getAttribute("id") == "DeleteTanggungan") {
      if (xtanggungan.length > 0) {
        let myfilter;
        myfilter = dokumens.filter((wordx, v) => {
          if (!xtanggungan[v]) {
            return wordx;
          }
        });
        dokumens = myfilter;

        let apidata = new Promise(function (myResolve, myReject) {
          let dataArray = new FormData();
          dataArray.append("action", "DeleteTanggungan");
          dataArray.append("id", fields.id);
          dataArray.append("kariah_id", fields.kariah_id);
          dataArray.append("senaraiTanggungan", JSON.stringify(dokumens));
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
      myfilter = dokumens.filter((wordx, v) => {
        if (getid == v) {
          return wordx;
        }
      });

      fields.tajukDokumen = myfilter[0].tajukDokumen;
      fields.noKadPengenalanTanggungan = myfilter[0].noKadPengenalanTanggungan;
      fields.pertalianKeluargaTanggungan =
        myfilter[0].pertalianKeluargaTanggungan;
      fields.nomborTelefonTanggungan = myfilter[0].nomborTelefonTanggungan;
      open = !open;
    } else {
      if (e.target.getAttribute("name") == "cancel") {
        fields.tajukDokumen = "";
        fields.noKadPengenalanTanggungan = "";
        fields.pertalianKeluargaTanggungan = "";
        fields.nomborTelefonTanggungan = "";
        open = !open;
      }
    }
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

  let deldokumen = async (e) => {
    let getid = e.target.parentElement.getAttribute("data-pass");
    if (getid == null) {
      getid = e.target.getAttribute("data-pass");
    }
    if (getid == null) {
      getid = e.target.parentElement.parentElement.getAttribute("data-pass");
    }

    let myfilter;
    myfilter = dokumens.filter((wordx, v) => {
      if (getid != v) {
        return wordx;
      }
    });
    dokumens = myfilter;

    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "DeleteMuatnaikDokumen");
      dataArray.append("id", fields.id);
      dataArray.append("kariah_id", fields.kariah_id);
      dataArray.append("dokumens", JSON.stringify(dokumens));
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
    submitpost.post = "Dokumen berjaya dibuang";
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
  <form id="MuatnaikDokumen" on:submit|preventDefault={submitHandler}>
    <input class="form-control" id="id" type="hidden" bind:value={fields.id} />
    <input
      class="form-control"
      id="kariah_id"
      type="hidden"
      bind:value={fields.kariah_id}
    />
    <div class="row">
      <div class="col-sm">
        <label class="form-label" for="tajukDokumen">Tajuk Dokumen</label>
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('tajukDokumen')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="tajukDokumen"
          type="text"
          placeholder="Tajuk Dokumen"
          required
          bind:value={fields.tajukDokumen}
        />

        <div class="invalid-feedback" data-sb-feedback="tajukDokumen:required">
          Tajuk Dokumen is required.
        </div>
      </div>
      <div class="col-sm">
        <label class="form-label" for="jenisDokumen"
          >Jenis Dokumen (JPG, PNG, GIF, PDF)</label
        >
        <input
          class="form-control {submitpost.error.findIndex((p) =>
            p.hasOwnProperty('jenisDokumen')
          ) != -1
            ? 'is-invalid'
            : ''}"
          id="jenisDokumen"
          type="file"
          placeholder="Jenis Dokumen"
          required
          bind:files={fields.jenisDokumen}
        />

        <div class="invalid-feedback" data-sb-feedback="jenisDokumen:required">
          Jenis Dokumen is required.
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
          <th>Tajuk Dokumen</th>
          <th>Tarikh Muatnaik</th>
          <th>Muat turun</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        {#if dokumens}
          {#each dokumens as dokumen, i}
            <tr>
              <td>{i + 1}</td>
              <td>{dokumen.tajukDokumen}</td>
              <td>{dokumen.tarikhDokumen}</td>
              <td
                ><a
                  target="_blank"
                  rel="noopener noreferrer"
                  href={dokumen[0].url}
                  class="btn btn-success btn-sm"
                  on:click={(e) => toggle(e)}
                  ><svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-cloud-download-fill"
                    viewBox="0 0 16 16"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M8 0a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 4.095 0 5.555 0 7.318 0 9.366 1.708 11 3.781 11H7.5V5.5a.5.5 0 0 1 1 0V11h4.188C14.502 11 16 9.57 16 7.773c0-1.636-1.242-2.969-2.834-3.194C12.923 1.999 10.69 0 8 0zm-.354 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V11h-1v3.293l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"
                    />
                  </svg></a
                >
              </td>
              <td>
                <button
                  class="btn btn-danger btn-sm"
                  data-pass={i}
                  on:click={(e) => deldokumen(e)}
                  ><svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-trash3-fill"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"
                    />
                  </svg></button
                >
              </td>
            </tr>
          {/each}
        {/if}
      </tbody>
    </Table>
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
              <label class="form-label" for="tajukDokumen"
                >Nama Tanggungan</label
              >
              <input
                class="form-control {submitpost.error.findIndex((p) =>
                  p.hasOwnProperty('tajukDokumen')
                ) != -1
                  ? 'is-invalid'
                  : ''}"
                id="tajukDokumen"
                type="text"
                placeholder="Nama Tanggungan"
                required
                bind:value={fields.tajukDokumen}
              />

              <div
                class="invalid-feedback"
                data-sb-feedback="tajukDokumen:required"
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
