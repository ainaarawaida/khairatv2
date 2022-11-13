import { defineConfig } from 'vite'
import { svelte } from '@sveltejs/vite-plugin-svelte'
import { chunkSplitPlugin } from 'vite-plugin-chunk-split';

// https://vitejs.dev/config/
export default defineConfig({
  base: '/wp-content/plugins/khairat/myapp/dist/',
  plugins: [svelte(), chunkSplitPlugin({
    strategy: 'unbundle'
  })]
})
