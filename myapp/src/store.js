import { writable } from 'svelte/store'

export let data = writable({
    khai_user:{
        data : {
        stage_daftar: '0'
        }
    },
    store:[]
})