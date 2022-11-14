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
  let sumbangans = [];
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
      dataArray.append("action", "UserKemaskiniAhliTerimaSumbangan");
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

    sumbangans = JSON.parse(await apidata).submitpost.sumbangans;
    loading = false;

    fields = {
      id: passdata,
      kariah_id: khai_user.data.kariah_id,
      tarikhSumbangan: [year, month, day].join("-"),
      catatan: "",
    };
  });

  onDestroy(() => {
    unsubscribe;
  });

  const submitHandler = async (e) => {
    submitpost.post = "";
    submitpost.error = [];
    loading = true;
    if (e.target.getAttribute("id") == "TerimaSumbangan") {
      let apidata = new Promise(function (myResolve, myReject) {
        let dataArray = new FormData();
        dataArray.append("action", "TambahTerimaSumbangan");
        dataArray.append("id", fields.id);
        dataArray.append("kariah_id", fields.kariah_id);
        dataArray.append("tarikhSumbangan", fields.tarikhSumbangan);
        dataArray.append("jumlahSumbangan", fields.jumlahSumbangan);
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

      sumbangans = JSON.parse(await apidata).submitpost.sumbangans;

      fields.tarikhSumbangan = "";
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
        dataArray.append("tarikhSumbangan", fields.tarikhSumbangan);
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

      sumbangans = JSON.parse(await apidata).submitpost.sumbangans;

      fields.tarikhSumbangan = "";
      fields.noKadPengenalanTanggungan = "";
      fields.pertalianKeluargaTanggungan = "";
      fields.nomborTelefonTanggungan = "";
      submitpost.post = "Berjaya Kemaskini";
      open = false;
    } else if (e.target.getAttribute("id") == "DeleteTanggungan") {
      if (xtanggungan.length > 0) {
        let myfilter;
        myfilter = sumbangans.filter((wordx, v) => {
          if (!xtanggungan[v]) {
            return wordx;
          }
        });
        sumbangans = myfilter;

        let apidata = new Promise(function (myResolve, myReject) {
          let dataArray = new FormData();
          dataArray.append("action", "DeleteTanggungan");
          dataArray.append("id", fields.id);
          dataArray.append("kariah_id", fields.kariah_id);
          dataArray.append("senaraiTanggungan", JSON.stringify(sumbangans));
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
      myfilter = sumbangans.filter((wordx, v) => {
        if (getid == v) {
          return wordx;
        }
      });

      fields.tarikhSumbangan = myfilter[0].tarikhSumbangan;
      fields.noKadPengenalanTanggungan = myfilter[0].noKadPengenalanTanggungan;
      fields.pertalianKeluargaTanggungan =
        myfilter[0].pertalianKeluargaTanggungan;
      fields.nomborTelefonTanggungan = myfilter[0].nomborTelefonTanggungan;
      open = !open;
    } else {
      if (e.target.getAttribute("name") == "cancel") {
        fields.tarikhSumbangan = "";
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

  <main>
    <div class="container-fluid px-4">
      <form id="DeleteTanggungan" on:submit|preventDefault={submitHandler}>
        <Table responsive>
          <thead>
            <tr>
              <th>#</th>
              <th>Tarikh</th>
              <th>Catatan</th>
              <th>Jumlah</th>
            </tr>
          </thead>
          <tbody>
            {#if sumbangans}
              {#each sumbangans as sumbangan, i}
                <tr>
                  <td>{i + 1}</td>
                  <td>{sumbangan.tarikhSumbangan}</td>
                  <td>{sumbangan.catatan}</td>
                  <td>{sumbangan.jumlahSumbangan}</td>
                </tr>
              {/each}
            {/if}
          </tbody>
        </Table>
      </form>

      <div>
        <Modal size="lg" isOpen={open} {toggle}>
          <form
            id="UpdateTanggunganForm"
            on:submit|preventDefault={submitHandler}
          >
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
                  <label class="form-label" for="tarikhSumbangan"
                    >Nama Tanggungan</label
                  >
                  <input
                    class="form-control {submitpost.error.findIndex((p) =>
                      p.hasOwnProperty('tarikhSumbangan')
                    ) != -1
                      ? 'is-invalid'
                      : ''}"
                    id="tarikhSumbangan"
                    type="text"
                    placeholder="Nama Tanggungan"
                    required
                    bind:value={fields.tarikhSumbangan}
                  />

                  <div
                    class="invalid-feedback"
                    data-sb-feedback="tarikhSumbangan:required"
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
    </div>
  </main>
{/if}
