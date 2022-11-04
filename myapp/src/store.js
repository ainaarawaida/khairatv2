import { writable, get } from 'svelte/store'

export let data = writable({
    khai_user:{
        data : {
        stage_daftar: '0'
        }
    },
    store:{
        dataid:""
    }
})

export let getdata = get(data);