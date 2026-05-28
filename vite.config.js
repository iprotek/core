import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  //plugins: [vue()],
  resolve: {
      preserveSymlinks: true
  },
  build: {
    // Raise the limit to 1000kB (1MB)
    chunkSizeWarningLimit: 10000,
    sourcemap: true
  },
  plugins: [
    //base: 'https://cdn.yourdomain.com/',
    laravel({
      input: [
            //'resources/js/manage/dashboard2.js'
            'resources/js/manage/sms-sender.js'
          ],
      refresh: true
    }),
    vue({
        template: {
            transformAssetUrls: {
                // This is the magic line. 
                // Setting includeAbsolute to false tells Vite 
                // NOT to try and resolve /images/temp-image.png
                //includeAbsolute: false,
            },
        },
    })
  ]
})