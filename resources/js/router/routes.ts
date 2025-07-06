import { AppRouteRecordRaw } from './types'
import { RouteName } from '@/enums/route.enum'

const routes: AppRouteRecordRaw[] = [
    {
        path: '/',
        name: RouteName.DASHBOARD,
        component: () => import('@/views/Dashboard.vue'),
        meta: {
            title: 'Админка'
        }
    },
    {
        path: '/payments',
        name: RouteName.PAYMENTS,
        component: () => import('@/views/payments/Payments.vue'),
        meta: {
            title: 'Платежи'
        }
    },
    {
        path: '/users',
        name: RouteName.USERS,
        component: () => import('@/views/users/Users.vue'),
        meta: {
            title: 'Пользователи'
        }
    },
]

export default routes
