import {createRouter, createWebHistory, RouteLocationNormalized} from 'vue-router'
import routes from './routes'

const router = createRouter({
    history: createWebHistory('/'),
    routes
})

// Глобальные хуки
router.beforeEach((to: RouteLocationNormalized, from, next) => {
    const meta = to.meta as { title?: string }
    document.title = meta.title ?? 'Vue App'
    next()
})

export default router
