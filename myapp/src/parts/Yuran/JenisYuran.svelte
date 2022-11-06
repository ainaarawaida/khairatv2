<script>
   
     import { PowerTable } from "@muonw/powertable";
  import { onMount } from "svelte";
  import { onDestroy } from "svelte";
  import { Alert } from "sveltestrap";
  import { createEventDispatcher } from "svelte";
  import { getContext } from "svelte";
  import { data } from "../../store.js";
     let visible ;
     let loading;
     let mypluginurl = getContext("mypluginurl");
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
    let submitpost = {

    }

    const submitHandler = async () => {
    }

    const closealert = async () => {
    visible = false;
    submitpost.error = [];
    submitpost.post = false;
    // console.log("submitpost", submitpost);
  };
</script>
<main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Jenis Yuran</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active" />
      </ol>
      {#if submitpost.post}
        {#if submitpost.error.length > 0}
          {#each submitpost.error as cat}
            <Alert toggle={() => closealert()} color="danger" dismissible
              >{Object.values(cat)}</Alert
            >
          {/each}
        {:else}
          <Alert toggle={() => closealert()} color="info" dismissible
            >Berjaya Hantar</Alert
          >
        {/if}
      {/if}
  
   
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