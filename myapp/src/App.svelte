<script>
  import { onMount } from "svelte";
  import { data } from "./store.js";

  //  import { loadScript } from './document.js';
  import "./assets/js/fontawesome.js";
  import "./assets/js/bootstrap.bundle.min.js";

  // import "./assets/js/luqscripts.js";
  import Header from "./parts/Header.svelte";
  import Sidebar from "./parts/Sidebar.svelte";
  import SidebarUser from "./parts/SidebarUser.svelte";
  import RightSidebar from "./parts/RightSidebar.svelte";
  import Dashboard from "./parts/Dashboard.svelte";
  import UserDashboard from "./parts/UserDashboard.svelte";
  import Footer from "./parts/Footer.svelte";

  //Maklumat Kariah
  import MaklumatKariah from "./parts/MaklumatKariah/MaklumatKariah.svelte";
  import MaklumatProfil from "./parts/MaklumatProfil.svelte";

  //Ahli khairat
  import SenaraiAhli from "./parts/AhliKhairat/SenaraiAhli.svelte";
  import SenaraiAhliTidakAktif from "./parts/AhliKhairat/SenaraiAhliTidakAktif.svelte";
  import DaftarAhli from "./parts/AhliKhairat/DaftarAhli.svelte";
  import KemaskiniAhli from "./parts/AhliKhairat/KemaskiniAhli.svelte";
  import PilihPakej from "./parts/AhliKhairat/PilihPakej.svelte";

  import UserKhairat from "./parts/AhliKhairat/UserKhairat.svelte";
  import UserKemaskiniAhliMaklumatBayaranAhli from "./parts/AhliKhairat/UserKemaskiniAhliMaklumatBayaranAhli.svelte";
  import UserKemaskiniAhliTerimaSumbangan from "./parts/AhliKhairat/UserKemaskiniAhliTerimaSumbangan.svelte";

  import JenisYuran from "./parts/Yuran/JenisYuran.svelte";
  import TambahJenisYuran from "./parts/Yuran/TambahJenisYuran.svelte";
  import KemaskiniJenisYuran from "./parts/Yuran/KemaskiniJenisYuran.svelte";
  import GenerateYuran from "./parts/Yuran/GenerateYuran.svelte";

  import Mystep from "./parts/Mystep.svelte";

  //test
  // import Test from './Test.svelte';

  import { setContext } from "svelte";

  let khai_user;

  data.subscribe((value) => {
    khai_user = value.khai_user;
  });

  let loading = false;
  let mybaseurl =
    import.meta.env.MODE !== "development"
      ? window.location.origin
      : "http://khairat.test";
  setContext("mybaseurl", mybaseurl);

  let myapiurl = `${mybaseurl}/wp-json/api/v1/data`;
  setContext("myapiurl", myapiurl);

  let mypluginurl =
    import.meta.env.MODE !== "development"
      ? `${import.meta.env.BASE_URL}`
      : `/`;
  setContext("mypluginurl", mypluginurl);

  let activeMenu = "Dashboard";
  let passdata;
  const menuChange = (e, data) => {
    passdata = {};
    if (Array.isArray(e.detail) === true) {
      // console.log('tabChange',   e.detail[1]);
      passdata = { passdata: e.detail[1] };
      activeMenu = e.detail[0];
    } else {
      activeMenu = e.detail;
    }
  };

  onMount(async () => {
    loading = true;
    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "Utama");
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

    khai_user = JSON.parse(await apidata).khai_user;

    data.update((currentPolls) => {
      return { ...currentPolls, khai_user };
    });

    loading = false;
    if (khai_user.data.kariah_id == "") {
      activeMenu = "Maklumat Kariah";
    } else if (
      khai_user.data.noKadPengenalanBaru == "" ||
      khai_user.data.noKadPengenalanBaru == undefined
    ) {
      activeMenu = "Maklumat Profil";
    } else if (
      khai_user.data.role != "1" &&
      khai_user.data.stage_daftar == "1"
    ) {
      activeMenu = "Pilih Pakej";
    } else if (khai_user.data.role != "1") {
      activeMenu = "User Dashboard";
    }
  });

  $: console.log("activeMenu", activeMenu);
  // $: console.log("pakej", khai_user.data.pakej);
  // $: console.log("role", khai_user.data.role);
  // $: console.log("stage_daftar", khai_user.data.stage_daftar);
  $: khai_user.data.stage_daftar = khai_user.data.stage_daftar;
</script>

<svelte:head>
  <link href="{mypluginurl}css/bootstrap.min.css" rel="stylesheet" />
  <link href="{mypluginurl}css/styles.css" rel="stylesheet" />
</svelte:head>

<GenerateYuran />

{#if loading === true}
  <div class="d-flex justify-content-center">
    <img class="" src="{mypluginurl}assets/img/loading.gif" alt="" />
  </div>
{:else if loading === false}
  {#if khai_user.data.role == "1"}
    {#if khai_user.data.stage_daftar != "0" && khai_user.data.kariah_id != undefined && khai_user.data.kariah_id != "" && khai_user.data.noKadPengenalanBaru != ""}
      <Header {activeMenu} on:menuChange={menuChange} />
    {/if}

    <div id="layoutSidenav">
      {#if khai_user.data.stage_daftar != "0" && khai_user.data.kariah_id != undefined && khai_user.data.kariah_id != "" && khai_user.data.noKadPengenalanBaru != ""}
        <Sidebar {activeMenu} on:menuChange={menuChange} />
      {/if}
      <div id="layoutSidenav_content">
        {#if activeMenu === "Dashboard"}
          <Dashboard on:menuChange={menuChange} />
        {:else if activeMenu === "Test"}
          <!-- <Test/> -->
        {:else if activeMenu === "Maklumat Profil"}
          <MaklumatProfil />
        {:else if activeMenu === "Maklumat Kariah"}
          <MaklumatKariah on:menuChange={menuChange} />
        {:else if activeMenu === "Ahli Khairat - Senarai Ahli"}
          <SenaraiAhli {passdata} on:menuChange={menuChange} />
        {:else if activeMenu === "Ahli Khairat - Senarai Ahli Tidak Aktif"}
          <SenaraiAhliTidakAktif {passdata} on:menuChange={menuChange} />
        {:else if activeMenu === "Ahli Khairat - Kemaskini Ahli"}
          <KemaskiniAhli {passdata} on:menuChange={menuChange} />
        {:else if activeMenu === "Ahli Khairat - Daftar Ahli"}
          <DaftarAhli on:menuChange={menuChange} />
        {:else if activeMenu === "Jenis Yuran"}
          <JenisYuran {passdata} on:menuChange={menuChange} />
        {:else if activeMenu === "Jenis Yuran - Tambah Jenis Yuran"}
          <TambahJenisYuran on:menuChange={menuChange} />
        {:else if activeMenu === "Jenis Yuran - Kemaskini Jenis Yuran"}
          <KemaskiniJenisYuran on:menuChange={menuChange} />
        {/if}

        <Footer />
      </div>
    </div>
  {:else if khai_user.data.role != "1"}
    {#if khai_user.data.stage_daftar > 1 && khai_user.data.kariah_id != undefined && khai_user.data.kariah_id != "" && khai_user.data.noKadPengenalanBaru != "" && khai_user.data.pakej != "" && khai_user.data.pakej != undefined}
      <Header {activeMenu} on:menuChange={menuChange} />
    {/if}
    <div id="layoutSidenav">
      {#if khai_user.data.stage_daftar > 1 && khai_user.data.kariah_id != undefined && khai_user.data.kariah_id != "" && khai_user.data.noKadPengenalanBaru != "" && khai_user.data.pakej != "" && khai_user.data.pakej != undefined}
        <SidebarUser {activeMenu} on:menuChange={menuChange} />
      {/if}
      <div id="layoutSidenav_content">
        <Mystep />
        {#if activeMenu === "User Dashboard"}
          <UserDashboard />
        {:else if activeMenu === "Test"}
          <!-- <Test/> -->
        {:else if activeMenu === "Maklumat Profil"}
          <MaklumatProfil on:menuChange={menuChange} />
        {:else if activeMenu === "User Khairat - Maklumat Ahli"}
          <UserKhairat />
        {:else if activeMenu === "Maklumat Kariah"}
          <MaklumatKariah on:menuChange={menuChange} />
        {:else if activeMenu === "Pilih Pakej"}
          <PilihPakej on:menuChange={menuChange} />
        {:else if activeMenu === "User Khairat - Maklumat Bayaran"}
          <UserKemaskiniAhliMaklumatBayaranAhli on:menuChange={menuChange} />
        {:else if activeMenu === "User Khairat - Maklumat Tuntutan"}
          <UserKemaskiniAhliTerimaSumbangan on:menuChange={menuChange} />
        {:else if activeMenu === "Ahli Khairat - Senarai Ahli"}
          <SenaraiAhli {passdata} on:menuChange={menuChange} />
        {:else if activeMenu === "Ahli Khairat - Senarai Ahli Tidak Aktif"}
          <SenaraiAhliTidakAktif {passdata} on:menuChange={menuChange} />
        {:else if activeMenu === "Ahli Khairat - Kemaskini Ahli"}
          <KemaskiniAhli {passdata} on:menuChange={menuChange} />
        {:else if activeMenu === "Ahli Khairat - Daftar Ahli"}
          <DaftarAhli on:menuChange={menuChange} />
        {:else if activeMenu === "Jenis Yuran"}
          <JenisYuran {passdata} on:menuChange={menuChange} />
        {:else if activeMenu === "Jenis Yuran - Tambah Jenis Yuran"}
          <TambahJenisYuran on:menuChange={menuChange} />
        {:else if activeMenu === "Jenis Yuran - Kemaskini Jenis Yuran"}
          <KemaskiniJenisYuran on:menuChange={menuChange} />
        {/if}

        <Footer />
      </div>
    </div>
  {/if}
{/if}
