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
        myResolve(value); // when successful
      });
    });
    passdata = (await myPromise).store.passdata;
    khai_user = (await myPromise).khai_user;

    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "UserKemaskiniAhliMaklumatBayaranAhli");
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
      ].action = `<div style="width:50px;"><a href="${mybaseurl}/checkout/?add-to-cart=${
        ptData[i].product_id
      }"
                    class="btn btn-warning btn-sm" data-pass="${btoa(
                      JSON.stringify(ptData[i].ID)
                    )}"
                    >Bayar</a
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
