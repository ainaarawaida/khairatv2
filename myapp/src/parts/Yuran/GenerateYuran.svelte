<script>
  import { onMount } from "svelte";
  import { getContext } from "svelte";
  import { data } from "../../store.js";
  let myapiurl = getContext("myapiurl");
  onMount(async () => {
    const date = new Date();
    const today = date.getDay();
    if (today == 2) {
      generateyuran();
    }
  });

  let generateyuran = async () => {
    let apidata = new Promise(function (myResolve, myReject) {
      let dataArray = new FormData();
      dataArray.append("action", "GenerateYuran");
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
  };
</script>
