// (1) import and use
import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)


import ShowIndividual from '../components/ShowIndividual'
import AddReferral from '../components/AddReferral'

import Forms from '../views/Forms'
import usecom from '../vuex/usecom'
//import LoginForm from './components/LoginForm'

import Dashboard from "../components/Dashboard/Dashboard"
import Cases from "../components/Cases/Container"

import NotFound from '../components/NotFound'

const routes = [



    {
        path: "/cases", name: "cases", component: Cases,
        children: []
    },


    { path: '/home', name: "home", props: true, component: () => import(/* webpackChunkName: "home" */ '../components/Home/Home.vue'), children: [
        { path: "worker-pss-referrals", name: "workerPssReferrals", props: true, component: () => import(/* webpackChunkName: "workerPssReferrals" */ '../components/Home/WorkerPssReferrals.vue') },

    ] },
    
    { path: '/cases/:caseeId', name: "caseDetails", props: true, component: () => import(/* webpackChunkName: "caseDetails" */ '../components/CaseDetails/CaseDetails.vue'), children: [
        { path: "beneficiaries", name: "caseBeneficiaries", props: true, component: () => import(/* webpackChunkName: "caseBeneficiaries" */ '../components/CaseDetails/CaseBeneficiaries/CaseBeneficiaries.vue') },
        { path: "referrals", name: "caseReferrals", props: true, component: () => import(/* webpackChunkName: "caseReferrals" */ '../components/CaseDetails/CaseReferrals.vue') , children: [] },
        { path: "housing-referrals", name: "caseHousingReferrals", props: true, component: () => import(/* webpackChunkName: "caseHousingReferrals" */ '../components/CaseDetails/CaseHousingReferrals/CaseHousingReferrals.vue') },
        { path: 'referrals/:referralId', name: 'referralDetails', props: true, component: () => import(/* webpackChunkName: "referralDetails" */ '../components/ReferralDetails/ReferralDetails.vue'), children: [
            {path: "records/:recordId", name: "RecordDetails", props: true, component: () => import(/* webpackChunkName: "RecordDetails" */ '../components/ReferralDetails/RecordDetails.vue') }
        ] },
    ] },



    

    {
        path: "/dashboard", name: "dashboard", component: Dashboard,
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

    // { 
    //     path: '/referrals/:referralId', 
    //     name: 'showReferral',
    //     component: () => import(/* webpackChunkName: "showReferral" */ '../components/showReferral/Container'),
    //     props: true,

    // },



    { path: '/referrals/create', component: require('../components/AddReferral.vue').default },

    { path: '/nationalities', component: require('../components/Nationalities/Container.vue').default },
    { path: '/referral_sources', component: require('../components/ReferralSources.vue').default },
    { path: '/referral_reasons', component: require('../components/ReferralReasons.vue').default },
    { path: '/servicetypes', component: require('../components/Servicetypes.vue').default },

    { path: '/profile', component: require('../components/Profile.vue').default },
    { path: '/beneficiaries/check', component: require('../components/AddReferral/CheckIndividual.vue').default },
    { path: '/beneficiaries/create_registered', component: require('../components/AddRegisteredIndividual.vue').default },
    { path: '/beneficiaries/:id', component: ShowIndividual },
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