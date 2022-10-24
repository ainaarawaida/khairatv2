

<script>
   import { onMount } from 'svelte';
   import { data } from './store.js'

   
  //  import { loadScript } from './document.js';
    import './assets/js/fontawesome.js';
    import './assets/js/bootstrap.bundle.min.js';

    // import './assets/js/luqscripts.js';
    import Header from './parts/Header.svelte';
    import Sidebar from './parts/Sidebar.svelte';
    import RightSidebar from './parts/RightSidebar.svelte';
    import Dashboard from './parts/Dashboard.svelte';
    import Footer from './parts/Footer.svelte';

    //Maklumat Kariah
    import MaklumatKariah from './parts/MaklumatKariah/MaklumatKariah.svelte';
    import MaklumatProfil from './parts/MaklumatProfil.svelte';
    
    //Ahli khairat
    import SenaraiAhli from './parts/AhliKhairat/SenaraiAhli.svelte';
    import DaftarAhli from './parts/AhliKhairat/DaftarAhli.svelte';
    import KemaskiniAhli from './parts/AhliKhairat/KemaskiniAhli.svelte';

    //test
    // import Test from './Test.svelte';

    import { setContext } from 'svelte';

    let khai_user ;

    const unsubscribe = data.subscribe(value => {
        khai_user = value.khai_user;
    });
    

    let loading = false ;
    let mybaseurl =
    import.meta.env.MODE !== "development"
      ? window.location.origin
      : "http://demo.test";
    setContext('mybaseurl', mybaseurl) ;

    let myapiurl = `${mybaseurl}/wp-json/api/v1/data`;
    setContext('myapiurl', myapiurl) ;

    let mypluginurl =
    import.meta.env.MODE !== "development"
      ? `${import.meta.env.BASE_URL}`
      : `/`;
    setContext('mypluginurl', mypluginurl) ;

    let activeMenu = 'Dashboard';
    let passdata ; 
    const menuChange = (e, data) => {
      passdata = {} ;
      if(Array.isArray(e.detail) === true){
        // console.log('tabChange',   e.detail[1]);
        passdata = {passdata: e.detail[1]} ; 
        activeMenu = e.detail[0];
      }else{
        activeMenu = e.detail ;
      }
      
    };


    onMount(async () => {
     
      loading = true ;
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

        khai_user = JSON.parse(await apidata).khai_user ; 
        
        data.update(currentPolls => {
          return {...currentPolls,khai_user};
        });

        loading = false ;
        if(khai_user.data.kariah_id == ""){
          activeMenu = "Maklumat Kariah" ;
        }else if(khai_user.data.noKadPengenalanBaru == "" || khai_user.data.noKadPengenalanBaru == undefined){
          activeMenu = "Maklumat Profil" ;
        }

       
       



    
     
	  });

$: console.log("data",khai_user); 
$: khai_user.data.stage_daftar = khai_user.data.stage_daftar ;

</script>
<svelte:head>
  <link href="{mypluginurl}css/bootstrap.min.css" rel="stylesheet" />
  <link href="{mypluginurl}css/styles.css" rel="stylesheet" />
</svelte:head>


{#if loading === true}
<div class="d-flex justify-content-center"> <img class="" src="{mypluginurl}assets/img/loading.gif" alt=""></div>
{:else}
{#if khai_user.data.stage_daftar != '0' && khai_user.data.kariah_id != undefined && khai_user.data.kariah_id != "" && khai_user.data.noKadPengenalanBaru != ""}
<Header {activeMenu} on:menuChange={menuChange}/>
{/if}

<div id="layoutSidenav">
  {#if khai_user.data.stage_daftar != '0' && khai_user.data.kariah_id != undefined && khai_user.data.kariah_id != "" && khai_user.data.noKadPengenalanBaru != ""}
 <Sidebar {activeMenu} on:menuChange={menuChange} />
 {/if}
  <div id="layoutSidenav_content">
    {#if activeMenu === 'Dashboard'}
      <Dashboard/>
    {:else if activeMenu === 'Test'}
      <!-- <Test/> -->
    {:else if activeMenu === 'Maklumat Profil'}
      <MaklumatProfil/>

    {:else if activeMenu === 'Maklumat Kariah'}
      <MaklumatKariah on:menuChange={menuChange}/>
    {:else if activeMenu === 'Ahli Khairat - Senarai Ahli'}
      <SenaraiAhli {passdata} on:menuChange={menuChange}/>
    {:else if activeMenu === 'Ahli Khairat - Kemaskini Ahli'}
      <KemaskiniAhli {passdata} on:menuChange={menuChange}/>
    {:else if activeMenu === 'Ahli Khairat - Daftar Ahli'}
      <DaftarAhli on:menuChange={menuChange}/>

    {/if}

    <Footer/>
  </div>
</div>
{/if}