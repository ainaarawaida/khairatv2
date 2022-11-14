<script>
  import { Progress } from "sveltestrap";
  import { onMount } from "svelte";
  import { onDestroy } from "svelte";

  import { data } from "../store.js";
  let unsubscribe;
  let khai_user;
  khai_user = {
    data: {
      stage_daftar: 0,
    },
  };

  onMount(async () => {
    let myPromise = new Promise(function (myResolve, myReject) {
      unsubscribe = data.subscribe((value) => {
        khai_user = value.khai_user;
        myResolve(value); // when successful
      });
    });
    khai_user = (await myPromise).khai_user;
  });
  onDestroy(() => {
    unsubscribe;
  });
  // $: console.log("haha", khai_user.data.stage_daftar);
</script>

<main>
  <div class="container-fluid px-4">
    <div class="row">
      <div class="col">
        <div class="text-center"><h1>Langkah Turutan</h1></div>
        <div class="row">
          <div
            class="text-center col-sm p-2 m-1 bg-{khai_user.data.stage_daftar >=
            1
              ? 'success'
              : 'danger'} text-white progress-bar-striped"
          >
            1.Daftar
          </div>
          <div
            class="text-center col-sm p-2 m-1 bg-{khai_user.data.stage_daftar >=
            2
              ? 'success'
              : 'danger'} text-white progress-bar-striped"
          >
            2.Pilih Pakej
          </div>
          <div
            class="text-center col-sm p-2 m-1 bg-{khai_user.data.stage_daftar >=
            3
              ? 'success'
              : 'danger'} text-white progress-bar-striped"
          >
            3.Kemaskini Maklumat Ahli
          </div>
          <div
            class="text-center col-sm p-2 m-1 bg-{khai_user.data.stage_daftar >=
            4
              ? 'success'
              : 'danger'} text-white progress-bar-striped"
          >
            4.Kelulusan Admin
          </div>
          <div
            class="text-center col-sm p-2 m-1 bg-{khai_user.data.stage_daftar >=
            5
              ? 'success'
              : 'danger'} text-white progress-bar-striped"
          >
            5.Bayaran
          </div>
          <div
            class="text-center col-sm p-2 m-1 bg-{khai_user.data.stage_daftar >=
            6
              ? 'success'
              : 'danger'} text-white progress-bar-striped"
          >
            6.Kematian
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
