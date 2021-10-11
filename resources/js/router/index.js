// (1) import and use
import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)


import ShowIndividual from '../components/ShowIndividual'
import AddReferral from '../components/AddReferral'

import Forms from '../views/Forms'
import usecom from '../vuex/usecom'
//import LoginForm from './components/LoginForm'

import Dashboard from "../components/dashboard/Container"

import ShowReferral from '../components/showReferral/Container'
import NotFound from '../components/NotFound'

const routes = [
    {
        path: "/dashboard", name: "dashboard", component: Dashboard,
        children: []
    },



    {
        path: '/files/:fileId',
        name:'show-file',
        component: () => import(/* webpackChunkName: "show-file" */ '../components/showFile/Container.vue'),
        props: true
    },
    { 
        path: '/users', 
        name: 'users',
        component: () => import(/* webpackChunkName: "users" */ "../components/users/Container"),
        props: true
    },
    { 
        path: '/roles', 
        name: 'roles',
        component: () => import(/* webpackChunkName: "roles" */ "../components/roles/Container"),
        props: true
    },



    { path: '/referrals/create', component: require('../components/AddReferral.vue').default },

    { path: '/nationalities', component: require('../components/Nationalities.vue').default },
    { path: '/referral_sources', component: require('../components/ReferralSources.vue').default },
    { path: '/referral_reasons', component: require('../components/ReferralReasons.vue').default },
    { path: '/servicetypes', component: require('../components/Servicetypes.vue').default },

    { path: '/profile', component: require('../components/Profile.vue').default },
    { path: '/individuals/check', component: require('../components/AddReferral/CheckIndividual.vue').default },
    { path: '/individuals/create_registered', component: require('../components/AddRegisteredIndividual.vue').default },
    { path: '/individuals/:id', component: ShowIndividual },
    { path: '/404', component: NotFound },
    // { path: '*', redirect: '/404' },
    { path: '*', component: NotFound },
    
    //{ path: '/login', component: LoginForm },
];


// (2) export router
export default new VueRouter({
    //mode: 'history',
    routes: routes
});