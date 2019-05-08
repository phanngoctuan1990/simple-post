import Vue from 'vue'
import Router from 'vue-router'

import Post from '../components/post/index'
import Dashboard from '../components/Dashboard'
import LoginView from '../components/auth/Login'
import NotFound from '../components/NotFound'

Vue.use(Router)

const router = new Router({
    routes: [
        {
            path: '/',
            name: 'Dashboard',
            component: Dashboard,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/login',
            name: 'Login',
            component: LoginView
        },
        {
            path: '/posts',
            name: 'Post',
            component: Post,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '*',
            component: NotFound
        }
    ],
    mode: 'history',
    redirect: {
        "*": '/'
    }
})

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth) {
        const authUser = JSON.parse(window.localStorage.getItem('authUser'))
        if (!(authUser && authUser.access_token)) {
            next({name: 'Login'})
        }
    }
    if (to.name == 'Login') {
        const authUser = JSON.parse(window.localStorage.getItem('authUser'))
        if (authUser && authUser.access_token) {
            next({name: 'Dashboard'})
        }
    }
    next()
})

export default router