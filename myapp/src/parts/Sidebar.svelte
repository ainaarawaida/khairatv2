<script>
  import { createEventDispatcher } from "svelte";
  import { onMount } from "svelte";
  let dispatch = createEventDispatcher();

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
  });
</script>

<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <div class="nav">
        <div class="sb-sidenav-menu-heading">Core</div>
        <a
          class="nav-link"
          class:active={activeMenu === "Dashboard"}
          on:click|preventDefault={() => dispatch("menuChange", "Dashboard")}
          href="Dashboard"
        >
          <div class="sb-nav-link-icon">
            <i class="fas fa-tachometer-alt" />
          </div>
          Dashboard
        </a>
        <a
          class="nav-link collapsed"
          href="#"
          class:active={activeMenu.includes("Ahli Khairat")}
          data-bs-toggle="collapse"
          data-bs-target="#collapseLayouts"
          aria-expanded="false"
          aria-controls="collapseLayouts"
        >
          <div class="sb-nav-link-icon"><i class="fas fa-columns" /></div>
          Ahli Khairat
          <div class="sb-sidenav-collapse-arrow">
            <i class="fas fa-angle-down" />
          </div>
        </a>
        <div
          class="collapse"
          id="collapseLayouts"
          aria-labelledby="headingOne"
          data-bs-parent="#sidenavAccordion"
        >
          <nav class="sb-sidenav-menu-nested nav">
            <a
              class="nav-link"
              href="AhliKhairat"
              class:active={activeMenu.includes("Ahli Khairat - Senarai Ahli")}
              on:click|preventDefault={() =>
                dispatch("menuChange", "Ahli Khairat - Senarai Ahli")}
              >Senarai Ahli</a
            >
            <a
              class="nav-link"
              href="DaftarAhli"
              class:active={activeMenu.includes("Ahli Khairat - Daftar Ahli")}
              on:click|preventDefault={() =>
                dispatch("menuChange", "Ahli Khairat - Daftar Ahli")}
              >Daftar Ahli</a
            >
          </nav>
        </div>
        <a
          class="nav-link"
          class:active={activeMenu.includes("Test")}
          on:click|preventDefault={() => dispatch("menuChange", "Test")}
          href="Test"
        >
          <div class="sb-nav-link-icon">
            <i class="fas fa-tachometer-alt" />
          </div>
          Test
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

        <div class="sb-sidenav-menu-heading">Interface</div>
        <a
          class="nav-link collapsed"
          href="#"
          data-bs-toggle="collapse"
          data-bs-target="#collapseLayouts"
          aria-expanded="false"
          aria-controls="collapseLayouts"
        >
          <div class="sb-nav-link-icon"><i class="fas fa-columns" /></div>
          Layouts
          <div class="sb-sidenav-collapse-arrow">
            <i class="fas fa-angle-down" />
          </div>
        </a>
        <div
          class="collapse"
          id="collapseLayouts"
          aria-labelledby="headingOne"
          data-bs-parent="#sidenavAccordion"
        >
          <nav class="sb-sidenav-menu-nested nav">
            <a class="nav-link" href="layout-static.html">Static Navigation</a>
            <a class="nav-link" href="layout-sidenav-light.html"
              >Light Sidenav</a
            >
          </nav>
        </div>
        <a
          class="nav-link collapsed"
          href="#"
          data-bs-toggle="collapse"
          data-bs-target="#collapsePages"
          aria-expanded="false"
          aria-controls="collapsePages"
        >
          <div class="sb-nav-link-icon"><i class="fas fa-book-open" /></div>
          Pages
          <div class="sb-sidenav-collapse-arrow">
            <i class="fas fa-angle-down" />
          </div>
        </a>
        <div
          class="collapse"
          id="collapsePages"
          aria-labelledby="headingTwo"
          data-bs-parent="#sidenavAccordion"
        >
          <nav
            class="sb-sidenav-menu-nested nav accordion"
            id="sidenavAccordionPages"
          >
            <a
              class="nav-link collapsed"
              href="#"
              data-bs-toggle="collapse"
              data-bs-target="#pagesCollapseAuth"
              aria-expanded="false"
              aria-controls="pagesCollapseAuth"
            >
              Authentication
              <div class="sb-sidenav-collapse-arrow">
                <i class="fas fa-angle-down" />
              </div>
            </a>
            <div
              class="collapse"
              id="pagesCollapseAuth"
              aria-labelledby="headingOne"
              data-bs-parent="#sidenavAccordionPages"
            >
              <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="login.html">Login</a>
                <a class="nav-link" href="register.html">Register</a>
                <a class="nav-link" href="password.html">Forgot Password</a>
              </nav>
            </div>
            <a
              class="nav-link collapsed"
              href="#"
              data-bs-toggle="collapse"
              data-bs-target="#pagesCollapseError"
              aria-expanded="false"
              aria-controls="pagesCollapseError"
            >
              Error
              <div class="sb-sidenav-collapse-arrow">
                <i class="fas fa-angle-down" />
              </div>
            </a>
            <div
              class="collapse"
              id="pagesCollapseError"
              aria-labelledby="headingOne"
              data-bs-parent="#sidenavAccordionPages"
            >
              <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="401.html">401 Page</a>
                <a class="nav-link" href="404.html">404 Page</a>
                <a class="nav-link" href="500.html">500 Page</a>
              </nav>
            </div>
          </nav>
        </div>
        <div class="sb-sidenav-menu-heading">Addons</div>
        <a class="nav-link" href="charts.html">
          <div class="sb-nav-link-icon"><i class="fas fa-chart-area" /></div>
          Charts
        </a>
        <a class="nav-link" href="tables.html">
          <div class="sb-nav-link-icon"><i class="fas fa-table" /></div>
          Tables
        </a>
      </div>
    </div>
    <div class="sb-sidenav-footer">
      <div class="small">Logged in as:</div>
      Start Bootstrap
    </div>
  </nav>
</div>
