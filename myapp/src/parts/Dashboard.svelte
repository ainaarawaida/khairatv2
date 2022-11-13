<script>
  import { PowerTable } from "@muonw/powertable";
  import { onMount } from "svelte";
  import { onDestroy } from "svelte";
  import { data } from "../store.js";
  import { getContext } from "svelte";
  import { createEventDispatcher } from "svelte";
  let dispatch = createEventDispatcher();
  let unsubscribe;
  let submitpost;
  let khai_user;
  let MyPowerTable;
  let mypluginurl = getContext("mypluginurl");
  let myapiurl = getContext("myapiurl");
  let loading = true;
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
    unsubscribe = data.subscribe((value) => {
      khai_user = value.khai_user;
    });

    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "Dashboard");
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

    ptData = JSON.parse(await apidata).submitpost.get_senarai_ahli;
    for (let i = 0; i < ptData.length; i++) {
      // console.log("passdata", ptData[i]);
      ptData[i].stage_daftar == "1"
        ? (ptData[i].stage_daftar = "Aktif")
        : "Tidak Aktif";

      if (khai_user.ID == ptData[i].ID) {
        ptData[
          i
        ].action = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-check-fill" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
      <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
      </svg>`;
      } else {
        ptData[i].action = `<div style="width:35px;"><button
                    class="btn btn-primary btn-sm editahli" data-pass="${btoa(
                      JSON.stringify(ptData[i].ID)
                    )}"
                    ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                  </svg></button
                  ></div>`;
      }
    }
    if (ptData.length > 0) {
      for (var key of Object.keys(ptData[0])) {
        // console.log(key + " -> " + ptData[0][key]);
        var setTitle = key;
        if (key == "stage_daftar") {
          setTitle = "Status ";
        }
        if (key == "nomborTelefon") {
          setTitle = "No. Telefon";
        }
        if (key == "namaKariah") {
          setTitle = "Nama Kariah";
        }
        if (key == "noKadPengenalanBaru") {
          setTitle = "No. IC";
        }
        if (key == "display_name") {
          setTitle = "Nama";
        }
        if (key == "no_ahli") {
          setTitle = "No. Ahli";
        }
        if (key == "action") {
          setTitle = "Action ";
        }

        if (key == "user_registered") {
          setTitle = "Tarikh Daftar ";
        }

        if (key == "action") {
          ptInstructs.push({
            key,
            title: setTitle,
            filterable: false,
            parseAs: "unsafe-html",
          });
        } else if (key == "role" || key == "user_email") {
        } else {
          ptInstructs.push({ key, title: setTitle });
        }
      }
    }

    loading = false;

    var element = document.querySelector("div.MuonW.PowerTable");
    element.addEventListener("click", function (e) {
      if (
        typeof e.target.className === "string" &&
        e.target.className.includes("editahli")
      ) {
        // console.log(e.target.getAttribute('data-pass'));
        let dataid = e.target.getAttribute("data-pass");
        myeditahli(dataid);
        dispatch("menuChange", ["Ahli Khairat - Kemaskini Ahli", dataid]);
      }
      if (
        typeof e.target.parentElement.parentElement.className === "string" &&
        e.target.parentElement.parentElement.className.includes("editahli")
      ) {
        // console.log(e.target.parentElement.parentElement.getAttribute('data-pass'));
        let dataid =
          e.target.parentElement.parentElement.getAttribute("data-pass");
        myeditahli(dataid);
        dispatch("menuChange", ["Ahli Khairat - Kemaskini Ahli", dataid]);
      }
      if (
        typeof e.target.parentElement.className === "string" &&
        e.target.parentElement.className.includes("editahli")
      ) {
        // console.log(e.target.parentElement.parentElement.getAttribute('data-pass'));
        let dataid = e.target.parentElement.getAttribute("data-pass");
        myeditahli(dataid);
        dispatch("menuChange", ["Ahli Khairat - Kemaskini Ahli", dataid]);
      }

      if (
        typeof e.target.className === "string" &&
        e.target.className.includes("deleteahli")
      ) {
        // console.log(e.target.getAttribute('data-pass'));
        let dataid = e.target.getAttribute("data-pass");
        delahli(dataid);
        // dispatch("menuChange", ["Ahli Khairat - Kemaskini Ahli", dataid] );
      }
      if (
        typeof e.target.parentElement.className === "string" &&
        e.target.parentElement.className.includes("deleteahli")
      ) {
        // console.log(e.target.parentElement.parentElement.getAttribute('data-pass'));
        let dataid = e.target.parentElement.getAttribute("data-pass");
        delahli(dataid);
        // dispatch("menuChange", ["Ahli Khairat - Kemaskini Ahli", dataid] );
      }
      if (
        typeof e.target.parentElement.parentElement.className === "string" &&
        e.target.parentElement.parentElement.className.includes("deleteahli")
      ) {
        // console.log(e.target.parentElement.parentElement.getAttribute('data-pass'));
        let dataid =
          e.target.parentElement.parentElement.getAttribute("data-pass");
        delahli(dataid);
      }
    });
  });

  let myeditahli = (dataid) => {
    dataid = JSON.parse(atob(dataid));
    unsubscribe = data.subscribe((value) => {
      // console.log(value);
      value.store.passdata = dataid;
    });
  };

  onDestroy(() => {
    unsubscribe;
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
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    <div class="row">
      <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white mb-4">
          <div class="card-body">{ptData.length}</div>
          <div
            class="card-footer d-flex align-items-center justify-content-between"
          >
            <a
              class="small text-white stretched-link text-decoration-none"
              href="#">Permohonan Baru</a
            >
            <div class="small text-white">
              <i class="fas fa-angle-right" />
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white mb-4">
          <div class="card-body">160.00</div>
          <div
            class="card-footer d-flex align-items-center justify-content-between"
          >
            <a
              class="small text-white stretched-link text-decoration-none"
              href="#">Jumlah Bayaran (RM)</a
            >
            <div class="small text-white">
              <i class="fas fa-angle-right" />
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white mb-4">
          <div class="card-body">2700</div>
          <div
            class="card-footer d-flex align-items-center justify-content-between"
          >
            <a
              class="small text-white stretched-link text-decoration-none"
              href="#">Jumlah Tunggakan (RM)</a
            >
            <div class="small text-white">
              <i class="fas fa-angle-right" />
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white mb-4">
          <div class="card-body">Danger Card</div>
          <div
            class="card-footer d-flex align-items-center justify-content-between"
          >
            <a
              class="small text-white stretched-link text-decoration-none"
              href="#">Ahli Berdaftar (Aktif)</a
            >
            <div class="small text-white">
              <i class="fas fa-angle-right" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header">
        <i class="fas fa-table me-1" />
        Pendaftaran Online
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

<style global>
  @import "../../node_modules/@muonw/powertable/package/dist/power-table.css";
</style>
