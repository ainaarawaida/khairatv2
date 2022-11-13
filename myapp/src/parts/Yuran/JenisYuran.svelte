<script>
  import { PowerTable } from "@muonw/powertable";
  import { onMount } from "svelte";
  import { onDestroy } from "svelte";
  import { Alert } from "sveltestrap";
  import { createEventDispatcher } from "svelte";
  import { Button, Tooltip } from "sveltestrap";
  import { getContext } from "svelte";
  import { data } from "../../store.js";
  export let passdata = {};
  let myapiurl = getContext("myapiurl");
  let dispatch = createEventDispatcher();
  let visible;
  let loading;
  let mypluginurl = getContext("mypluginurl");
  let MyPowerTable;
  let ptData = [{}];
  let unsubscribe;
  let ptOptions = {
    checkboxColumn: false,
    segments: {
      topBar: ["settings", "search", "pagination"],
      pTable: ["table"],
      bottomBar: ["dropdown", "stats", "pagination"],
    },
  };
  let ptInstructs = [];
  let submitpost = {};

  onMount(async () => {
    loading = true;
    if (Object.keys(passdata).length == 0) {
      submitpost.post = "";
    } else {
      submitpost.post = passdata.passdata;
      submitpost.error = [];
    }

    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "JenisYuran");
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
    ptData = JSON.parse(await apidata).submitpost.get_jenisyuran;

    for (let i = 0; i < ptData.length; i++) {
      ptData[i].statusYuran == "1"
        ? (ptData[i].statusYuran = "Aktif")
        : (ptData[i].statusYuran = "Tidak Aktif");
      ptData[i].paparanYuran == "1"
        ? (ptData[i].paparanYuran = "Ya")
        : (ptData[i].paparanYuran = "Tidak");

      ptData[i].action = `<div style="width:125px;"><button
                    class="btn btn-primary btn-sm edityuran" data-pass="${btoa(
                      JSON.stringify(ptData[i].ID)
                    )}"
                    ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                  </svg></button
                  >&nbsp;<button
                    class="btn btn-danger btn-sm deleteyuran" data-pass="${btoa(
                      JSON.stringify(ptData[i].ID)
                    )}"
                    ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
                  </svg></button
                  ></div>`;
    }
    if (ptData.length > 0) {
      for (var key of Object.keys(ptData[0])) {
        // console.log(key + " -> " + ptData[0][key]);
        var setTitle = key;
        if (key == "jenisYuran") {
          setTitle = "Jenis Yuran ";
        }
        if (key == "Jumlah") {
          setTitle = "Jumlah (RM)";
        }
        if (key == "keteranganYuran") {
          setTitle = "Keterangan Yuran";
        }
        if (key == "mulaYuran") {
          setTitle = "Mula Yuran";
        }
        if (key == "statusYuran") {
          setTitle = "Status Yuran";
        }
        if (key == "paparanYuran") {
          setTitle = "Paparan Pakej";
        }

        if (key == "action") {
          ptInstructs.push({
            key,
            title: setTitle,
            filterable: false,
            parseAs: "unsafe-html",
          });
        } else if (key == "ID") {
        } else {
          ptInstructs.push({ key, title: setTitle });
        }
      }
    }

    var element = document.querySelector("div.MuonW.PowerTable");
    element.addEventListener("click", function (e) {
      if (
        typeof e.target.className === "string" &&
        e.target.className.includes("edityuran")
      ) {
        // console.log(e.target.getAttribute('data-pass'));
        let dataid = e.target.getAttribute("data-pass");
        edityuran(dataid);
      }
      if (
        typeof e.target.parentElement.parentElement.className === "string" &&
        e.target.parentElement.parentElement.className.includes("edityuran")
      ) {
        // console.log(e.target.parentElement.parentElement.getAttribute('data-pass'));
        let dataid =
          e.target.parentElement.parentElement.getAttribute("data-pass");
        edityuran(dataid);
      }
      if (
        typeof e.target.parentElement.className === "string" &&
        e.target.parentElement.className.includes("edityuran")
      ) {
        // console.log(e.target.parentElement.parentElement.getAttribute('data-pass'));
        let dataid = e.target.parentElement.getAttribute("data-pass");
        edityuran(dataid);
      }

      if (
        typeof e.target.className === "string" &&
        e.target.className.includes("deleteyuran")
      ) {
        // console.log(e.target.getAttribute('data-pass'));
        let dataid = e.target.getAttribute("data-pass");
        deleteyuran(dataid);
        // dispatch("menuChange", ["Ahli Khairat - Kemaskini Ahli", dataid] );
      }
      if (
        typeof e.target.parentElement.className === "string" &&
        e.target.parentElement.className.includes("deleteyuran")
      ) {
        // console.log(e.target.parentElement.parentElement.getAttribute('data-pass'));
        let dataid = e.target.parentElement.getAttribute("data-pass");
        deleteyuran(dataid);
        // dispatch("menuChange", ["Ahli Khairat - Kemaskini Ahli", dataid] );
      }
      if (
        typeof e.target.parentElement.parentElement.className === "string" &&
        e.target.parentElement.parentElement.className.includes("deleteyuran")
      ) {
        // console.log(e.target.parentElement.parentElement.getAttribute('data-pass'));
        let dataid =
          e.target.parentElement.parentElement.getAttribute("data-pass");
        deleteyuran(dataid);
      }

      if (
        typeof e.target.className === "string" &&
        e.target.className.includes("generateyuran")
      ) {
        // console.log(e.target.getAttribute('data-pass'));
        let dataid = e.target.getAttribute("data-pass");
        generateyuran(dataid);
        // dispatch("menuChange", ["Ahli Khairat - Kemaskini Ahli", dataid] );
      }
      if (
        typeof e.target.parentElement.className === "string" &&
        e.target.parentElement.className.includes("generateyuran")
      ) {
        // console.log(e.target.parentElement.parentElement.getAttribute('data-pass'));
        let dataid = e.target.parentElement.getAttribute("data-pass");
        generateyuran(dataid);
        // dispatch("menuChange", ["Ahli Khairat - Kemaskini Ahli", dataid] );
      }
      if (
        typeof e.target.parentElement.parentElement.className === "string" &&
        e.target.parentElement.parentElement.className.includes("generateyuran")
      ) {
        // console.log(e.target.parentElement.parentElement.getAttribute('data-pass'));
        let dataid =
          e.target.parentElement.parentElement.getAttribute("data-pass");
        generateyuran(dataid);
      }
    });

    loading = false;
  });

  onDestroy(() => {
    unsubscribe;
  });
  const submitHandler = async () => {};

  const closealert = async () => {
    visible = false;
    submitpost.error = [];
    submitpost.post = false;
    // console.log("submitpost", submitpost);
  };

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

  let generateyuran = (dataid) => {
    dataid = JSON.parse(atob(dataid));

    alert("gnerate Success");
  };
  let edityuran = (dataid) => {
    dataid = JSON.parse(atob(dataid));
    unsubscribe = data.subscribe((value) => {
      // console.log(value);
      value.store.passdata = dataid;
    });
    dispatch("menuChange", "Jenis Yuran - Kemaskini Jenis Yuran");
  };
  const deleteyuran = async (dataid) => {
    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "DeleteJenisYuran");
      dataArray.append("ID", JSON.parse(atob(dataid)));
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

    // passdata = JSON.parse(await apidata).submitpost;
    // console.log("passdata", JSON.parse(atob(dataid)));
    var result = ptData.filter((wordx) => {
      return wordx.ID != JSON.parse(atob(dataid));
    });
    ptData = result;
  };
</script>

<main>
  <div class="container-fluid px-4">
    <h1 class="mt-4">Jenis Yuran</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item active" />
    </ol>
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

    <div class="text-end">
      <a
        class="btn btn-primary"
        href="Yuran - Tambah Jenis Yuran"
        on:click|preventDefault={() =>
          dispatch("menuChange", "Jenis Yuran - Tambah Jenis Yuran")}
        ><i class="fa fa-plus" /> Tambah</a
      >
    </div>
    <div class="m-2" />

    <div class="card mb-4">
      <div class="card-header">
        <i class="fas fa-table me-1" />
        Senarai Aktif Ahli Khairat
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
