import Vue from 'vue'
import VueRouter from 'vue-router'

import routes from './routes'

Vue.use(VueRouter)

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation
 */

export default function (/* { store, ssrContext } */) {
  const Router = new VueRouter({
    scrollBehavior: () => ({ x: 0, y: 0 }),
    routes,

    // Leave these as is and change from quasar.conf.js instead!
    // quasar.conf.js -> build -> vueRouterMode
    // quasar.conf.js -> build -> publicPath
    mode: process.env.VUE_ROUTER_MODE,
    base: process.env.VUE_ROUTER_BASE
  })

  const titles = {
    '/analyzer': 'அவலோகிதம் — யாப்பு ஆராய்வு',
    '/ai': 'அவலோகிதம் — AI வெண்பா',
    '/venpa-fixer': 'அவலோகிதம் — வெண்பா திருத்தி',
    '/search': 'அவலோகிதம் — சொல் தேடல்',
    '/types': 'அவலோகிதம் — பா வகைகள்',
    '/learn': 'அவலோகிதம் — கற்றுக்கொள்',
    '/reference': 'அவலோகிதம் — கையேடு',
    '/prosody': 'அவலோகிதம் — யாப்பிலக்கணம்',
    '/about': 'அவலோகிதம் — பற்றி',
    '/help': 'அவலோகிதம் — உதவி',
    '/download': 'அவலோகிதம் — பதிவிறக்கம்'
  }

  Router.afterEach((to) => {
    const base = to.path.startsWith('/poem/') ? 'அவலோகிதம் — பா' : titles[to.path]
    document.title = base || 'அவலோகிதம்'
    if (window.gtag) window.gtag('config', 'G-7XW3KE2BGM', { page_path: to.fullPath, page_title: document.title })
  })

  return Router
}
