
const routes = [
  {
    path: '/',
    redirect: '/analyzer'
  },
  {
    path: '/search',
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: '', component: () => import('pages/search.vue') }
    ]
  },
  {
    path: '/reference',
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: '', component: () => import('pages/learn.vue') }
    ]
  },
  {
    path: '/types',
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: '', component: () => import('pages/example.vue') }
    ]
  },
  {
    path: '/download',
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: '', component: () => import('pages/download.vue') }
    ]
  },
  {
    path: '/help',
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: '', component: () => import('pages/help.vue') }
    ]
  },
  {
    path: '/learn',
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: '', component: () => import('pages/wizard.vue') }
    ]
  },
  {
    path: '/analyzer',
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Index.vue') }
    ]
  },
  {
    path: '/about',
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: '', component: () => import('pages/About.vue') }
    ]
  },
  {
    path: '/prosody',
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Prosody.vue') }
    ]
  }
]

// Always leave this as last one
if (process.env.MODE !== 'ssr') {
  routes.push({
    path: '*',
    component: () => import('pages/Error404.vue')
  })
}

export default routes
