<script>
  import { PowerTable } from "@muonw/powertable";
  import { onMount } from "svelte";
  import { getContext } from "svelte";
  import { data } from "../../store.js";
  import { Alert } from "sveltestrap";
  let myapiurl = getContext("myapiurl");
  let mypluginurl = getContext("mypluginurl");
  let mybaseurl = getContext("mybaseurl");
  let loading;
  let unsubscribe;
  let passdata;
  let khai_user;
  let submitpost;
  let MyPowerTable;
  let ptData = [{}];
  let ptOptions = {
    checkboxColumn: false,
    segments: {
      topBar: ["settings", "search", "pagination"],
      pTable: ["table"],
      bottomBar: ["dropdown", "stats", "pagination"],
    },
  };

  let ptInstructs = [];

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

    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "KemaskiniAhliMaklumatBayaranAhli");
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

    submitpost = JSON.parse(await apidata).submitpost;
    ptData = submitpost.senarai_yuran;
    for (let i = 0; i < ptData.length; i++) {
      ptData[i].status = `<div style="width:100px;"><button
                    class="btn btn-danger btn-sm" data-pass="${btoa(
                      JSON.stringify(ptData[i].ID)
                    )}"
                    >Belum Bayar</button
                  ></div>`;
      ptData[
        i
      ].action = `<div style="width:165px;"><a href="${mybaseurl}/checkout/?add-to-cart=${
        ptData[i].product_id
      }"
                    class="btn btn-warning btn-sm" data-pass="${btoa(
                      JSON.stringify(ptData[i].ID)
                    )}"
                    >Bayar</a
                  >&nbsp;<button
                    class="btn btn-primary btn-sm editahli" data-pass="${btoa(
                      JSON.stringify(ptData[i].ID)
                    )}"
                    ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                  </svg></button
                  >&nbsp;<button
                    class="btn btn-danger btn-sm deleteahli" data-pass="${btoa(
                      JSON.stringify(ptData[i].ID)
                    )}"
                    ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
                  </svg></button
                  ></div>`;
    }
    if (ptData.length > 0) {
      for (var key of Object.keys(ptData[0])) {
        if (key == "action") {
          ptInstructs.push({
            key,
            filterable: false,
            parseAs: "unsafe-html",
          });
        } else if (key == "status") {
          ptInstructs.push({
            key,
            filterable: false,
            parseAs: "unsafe-html",
          });
        } else {
          ptInstructs.push({ key });
        }
      }
    }

    loading = false;
  });

  function exportJsonData(e) {
    MyPowerTable.closeMenu(e);
    let currentData = MyPowerTable.getData().data;

    // Download JSON object as a file from browser (https://stackoverflow.com/a/30800715)
    var dataStr =
      "data:text/json;charset=utf-8," +
      encodeURIComponent(JSON.stringify(currentData));
    var downloadAnchorNode = document.createElement("a");
    downloadAnchorNode.setAttribute("href", dataStr);
    downloadAnchorNode.setAttribute("download", "PowerTable_current_data.json");
    document.body.appendChild(downloadAnchorNode); // required for firefox
    downloadAnchorNode.click();
    downloadAnchorNode.remove();
  }

  let fields = {
    pakej: "",
  };
</script>

<main>
  <div class="container-fluid px-4">
    <h1 class="mt-4">Maklumat Bayaran Ahli</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item active">Maklumat Yuran Ahli</li>
    </ol>
    <div class="m-2" />
    <div class="card mb-4">
      <div class="card-header">
        <i class="fas fa-table me-1" />
        Maklumat Yuran Ahli
      </div>
      <div class="card-body">
        <div class="MuonW PowerTable">
          {#if loading === true}
            <div class="d-flex justify-content-center">
              <img class="" src="{mypluginurl}assets/img/loading.gif" alt="" />
            </div>
          {:else}
            <PowerTable
              {ptData}
              {ptOptions}
              {ptInstructs}
              bind:this={MyPowerTable}
            >
              <div slot="settings">
                <div data-name="item" on:click={exportJsonData}>
                  Export current data
                </div>
              </div>
            </PowerTable>
          {/if}
        </div>
      </div>
    </div>
  </div>
</main>
