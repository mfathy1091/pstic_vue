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
import Search from "../components/search/Container"

import ShowReferral from '../components/showReferral/Container'
import NotFound from '../components/NotFound'
import Home from '../components/Home'

const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
        props: true,
    },
    { 
        path: '/details/:referralId', 
        name: 'referralDetails',
        props: true,
        component: () => import(/* webpackChunkName: "referralDetails" */ '../components/ReferralDetails/Container.vue'),
        
        children: [
            {
                path: "records/:recordId",
                name: "RecordDetails",
                props: true,
                component: () => import(/* webpackChunkName: "RecordDetails" */ '../components/ReferralDetails/RecordDetails.vue'),
                
            }
        ]
        

    },


 
    {
        path: "/dashboard", name: "dashboard", component: Dashboard,
        children: []
    },
    {
        path: "/search", name: "search", component: Search,
        children: []
    },
    {
        path: '/emergencies',
        name:'emergencies',
        component: () => import(/* webpackChunkName: "statistics" */ '../components/Statistics/Container.vue'),
        props: true
    },
    
    {
        path: '/records/:recordId/activities/create',
        name:'create-activity',
        component: () => import(/* webpackChunkName: "create-activity" */ '../components/CreateActivity/Container.vue'),
        props: true
    },

    {
        path: '/records/:recordId/emergencies/create',
        name:'create-emergency',
        component: () => import(/* webpackChunkName: "create-emergency" */ '../components/CreateEmergency/Container.vue'),
        props: true
    },
    

    {
        path: '/casees/:caseeId',
        name:'show-casee',
        component: () => import(/* webpackChunkName: "show-casee" */ '../components/showCasee/Container.vue'),
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

    { 
        path: '/referrals/:referralId', 
        name: 'showReferral',
        component: () => import(/* webpackChunkName: "showReferral" */ '../components/showReferral/Container'),
        props: true,

    },



    { path: '/referrals/create', component: require('../components/AddReferral.vue').default },

    { path: '/nationalities', component: require('../components/Nationalities/Container.vue').default },
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