import {RouteMeta, RouteRecordRaw} from 'vue-router'

declare module 'vue-router' {
    interface RouteMeta {
        title?: string
        requiresAuth?: boolean
    }
}

export type AppRouteRecordRaw = RouteRecordRaw & {
    meta?: RouteMeta
}
