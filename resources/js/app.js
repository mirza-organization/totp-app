import './bootstrap';

import { createApp, h } from 'vue'
import { createInertiaApp, Link, usePage } from '@inertiajs/vue3'
import { TailwindPagination } from 'laravel-vue-pagination';
/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'
/* import the fontawesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
/* import all solid icons */
import { fas } from '@fortawesome/free-solid-svg-icons'
import { far } from '@fortawesome/free-regular-svg-icons'
import { fab } from '@fortawesome/free-brands-svg-icons'

// Add all solid icons to the library
library.add(fas, far, fab)

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .component('font-awesome-icon', FontAwesomeIcon)
      .component('Link', Link)
      .component('usePage', usePage)
      .component('TailwindPagination', TailwindPagination)
      .mount(el)
  },
})