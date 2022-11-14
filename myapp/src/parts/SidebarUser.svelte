<script>
  import { createEventDispatcher } from "svelte";
  import { onMount } from "svelte";
  import { onDestroy } from "svelte";
  import { data } from "../store.js";
  let dispatch = createEventDispatcher();
  let unsubscribe;
  let khai_user = {
    data: {
      display_name: "",
    },
  };
  export let activeMenu;

  onMount(async () => {
    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector("#sidebarToggle");
    if (sidebarToggle) {
      // Uncomment Below to persist sidebar toggle between refreshes
      // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
      //     document.body.classList.toggle('sb-sidenav-toggled');
      // }
      sidebarToggle.addEventListener("click", (event) => {
        event.preventDefault();
        document.body.classList.toggle("sb-sidenav-toggled");
        localStorage.setItem(
          "sb|sidebar-toggle",
          document.body.classList.contains("sb-sidenav-toggled")
        );
      });
    }

    let myPromise = new Promise(function (myResolve, myReject) {
      unsubscribe = data.subscribe((value) => {
        //   console.log(value.store.passdata);
        myResolve(value); // when successful
      });
    });
    khai_user = (await myPromise).khai_user;
    // console.log(khai_user.data.display_name);
  });
  onDestroy(() => {
    unsubscribe;
  });
</script>

<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <div class="nav">
        <div class="sb-sidenav-menu-heading">Core</div>
        <a
          class="nav-link"
          class:active={activeMenu === "User Dashboard"}
          on:click|preventDefault={() =>
            dispatch("menuChange", "User Dashboard")}
          href="User Dashboard"
        >
          <div class="sb-nav-link-icon">
            <i class="fas fa-tachometer-alt" />
          </div>
          Dashboard
        </a>
        <a
          class="nav-link"
          class:active={activeMenu === "Pilih Pakej"}
          on:click|preventDefault={() => dispatch("menuChange", "Pilih Pakej")}
          href="Pilih Pakej"
        >
          <div class="sb-nav-link-icon">
            <i class="fas fa-tachometer-alt" />
          </div>
          Pilih Pakej
        </a>
        <a
          class="nav-link"
          class:active={activeMenu === "User Khairat - Maklumat Ahli"}
          on:click|preventDefault={() =>
            dispatch("menuChange", "User Khairat - Maklumat Ahli")}
          href="User Khairat - Maklumat Ahli"
        >
          <div class="sb-nav-link-icon">
            <i class="fas fa-tachometer-alt" />
          </div>
          Maklumat Ahli
        </a>
        <a
          class="nav-link"
          class:active={activeMenu === "User Khairat - Maklumat Bayaran"}
          on:click|preventDefault={() =>
            dispatch("menuChange", "User Khairat - Maklumat Bayaran")}
          href="User Khairat - Maklumat Bayaran"
        >
          <div class="sb-nav-link-icon">
            <i class="fas fa-tachometer-alt" />
          </div>
          Maklumat Bayaran
        </a>
        <a
          class="nav-link"
          class:active={activeMenu === "User Khairat - Maklumat Tuntutan"}
          on:click|preventDefault={() =>
            dispatch("menuChange", "User Khairat - Maklumat Tuntutan")}
          href="User Khairat - Maklumat Tuntutan"
        >
          <div class="sb-nav-link-icon">
            <i class="fas fa-tachometer-alt" />
          </div>
          Maklumat Tuntutan
        </a>

        <div class="sb-sidenav-menu-heading">Konfigurasi</div>
        <a
          class="nav-link"
          class:active={activeMenu === "Maklumat Kariah"}
          on:click|preventDefault={() =>
            dispatch("menuChange", "Maklumat Kariah")}
          href="Maklumat Kariah"
        >
          <div class="sb-nav-link-icon">
            <i class="fas fa-tachometer-alt" />
          </div>
          Maklumat Kariah
        </a>
        <a
          class="nav-link"
          class:active={activeMenu === "Jenis Yuran"}
          on:click|preventDefault={() => dispatch("menuChange", "Jenis Yuran")}
          href="Jenis Yuran"
        >
          <div class="sb-nav-link-icon">
            <i class="fas fa-tachometer-alt" />
          </div>
          Jenis Yuran
        </a>
      </div>
    </div>
    <div class="sb-sidenav-footer">
      <div class="small">Logged in as:</div>
      {khai_user.data.display_name}
    </div>
  </nav>
</div>
