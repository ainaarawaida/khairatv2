<script>
  import { PowerTable } from "@muonw/powertable";
  import { onMount } from "svelte";
  import { onDestroy } from "svelte";
  import { data } from "../store.js";
  let unsubscribe;
  let MyPowerTable;
  let khai_user;
  let ptData = [
    { "Tarikh Daftar": 1, name: "Fay" },
    { "Tarikh Daftar": 2, name: "Luca" },
  ];
  let ptOptions = {
    checkboxColumn: true,
    segments: {
      topBar: ["settings", "search", "pagination"],
      pTable: ["table"],
      bottomBar: ["dropdown", "stats", "pagination"],
    },
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

  onMount(async () => {
    let myPromise = new Promise(function (myResolve, myReject) {
      unsubscribe = data.subscribe((value) => {
        //   console.log(value.store.passdata);
        myResolve(value); // when successful
      });
    });
    khai_user = (await myPromise).khai_user;
  });
  onDestroy(() => {
    unsubscribe;
  });
</script>

<main>
  <div class="container-fluid px-4">
    <h1 class="mt-4">User Dashboard</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item active">User Dashboard</li>
    </ol>
  </div>
</main>
