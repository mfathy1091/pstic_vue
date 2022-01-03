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

import NotFound from '../components/NotFound'

const routes = [

    { path: '/cases', name: "cases", props: true, component: () => import(/* webpackChunkName: "home" */ '../components/Cases/Cases.vue'), children: [] },

    { path: '/pss', name: "pss", props: true, component: () => import(/* webpackChunkName: "pss" */ '../components/PSS/PSS.vue'), children: [
        { path: "pss-intake", name: "pssIntake", props: true, component: () => import(/* webpackChunkName: "pssIntake" */ '../components/PSS/PssIntake.vue') },
        { path: "pss-emergencies", name: "pssEmergencies", props: true, component: () => import(/* webpackChunkName: "pssEmergencies" */ '../components/PSS/PssEmergencies.vue') },
        { path: "pss-services", name: "pssServices", props: true, component: () => import(/* webpackChunkName: "pssServices" */ '../components/PSS/PssServices.vue') },
        { path: "pss-beneficiaries", name: "pssBeneficiaries", props: true, component: () => import(/* webpackChunkName: "pssBeneficiaries" */ '../components/PSS/PssBeneficiaries.vue') },
        { path: "pss-records", name: "pssRecords", props: true, component: () => import(/* webpackChunkName: "pssRecords" */ '../components/PSS/PssRecords.vue') },
    ] },

    
    
    { path: '/pss-worker', name: "pssWorker", props: true, component: () => import(/* webpackChunkName: "pssWorker" */ '../components/PssWorker/PssWorker.vue'), children: [
        { path: "home", name: "pssWorkerHome", props: true, component: () => import(/* webpackChunkName: "pssWorkerHome" */ '../components/PssWorker/PssWorkerHome.vue') },
        { path: "pss-referrals", name: "pssWorkerReferrals", props: true, component: () => import(/* webpackChunkName: "pssWorkerReferrals" */ '../components/PssWorker/PssWorkerCases.vue') },
        { path: "pss-referrals-stats", name: "pssWorkerReferralsStats", props: true, component: () => import(/* webpackChunkName: "pssWorkerReferralsStats" */ '../components/PssWorker/PssWorkerCasesStats.vue') },
        { path: "pss-emergencies", name: "pssWorkerEmergencies", props: true, component: () => import(/* webpackChunkName: "pssWorkerEmergencies" */ '../components/PssWorker/PssWorkerEmergencies.vue') },

    ] },
    

    { path: '/cases/:caseeId', name: "caseDetails", props: true, component: () => import(/* webpackChunkName: "caseDetails" */ '../components/CaseDetails/CaseDetails.vue'), children: [
        { path: "beneficiaries", name: "caseBeneficiaries", props: true, component: () => import(/* webpackChunkName: "caseBeneficiaries" */ '../components/CaseDetails/CaseBeneficiaries/CaseBeneficiaries.vue') },
        { path: "pss-intakes", name: "casePssIntakes", props: true, component: () => import(/* webpackChunkName: "casePssIntakes" */ '../components/CaseDetails/CasePssIntakes/CasePssIntakes.vue') , children: [] },
        { path: "housing-intakes", name: "caseHousingIntakes", props: true, component: () => import(/* webpackChunkName: "caseHousingIntakes" */ '../components/CaseDetails/CaseHousingIntakes/CaseHousingIntakes.vue') },

    ] },

    { path: '/cases/:caseeId/referrals/:referralId', name: 'pssIntakeDetails', props: true, component: () => import(/* webpackChunkName: "pssIntakeDetails" */ '../components/PssIntakeDetails/PssIntakeDetails.vue'), children: [
        // {path: "records/:recordId", name: "RecordDetails", props: true, component: () => import(/* webpackChunkName: "RecordDetails" */ '../components/ReferralDetails/RecordDetails.vue') }
    ] },

    { path: '/user-settings', name: "userSettings", props: true, component: () => import(/* webpackChunkName: "userSetttings" */ '../components/UserSettings/UserSettings.vue'), children: [
        { path: "users", name: "users", props: true, component: () => import(/* webpackChunkName: "users" */ '../components/UserSettings/Users/Users.vue') },
        { path: "roles", name: "roles", props: true, component: () => import(/* webpackChunkName: "roles" */ '../components/UserSettings/Roles/Roles.vue') },

    ] },

    { path: '/settings', name: "settings", props: true, component: () => import(/* webpackChunkName: "setttings" */ '../components/Settings/Settings.vue'), children: [
        { path: "nationalities", name: "nationalities", props: true, component: () => import(/* webpackChunkName: "nationalities" */ '../components/Settings/Nationalities/Nationalities.vue') },
        { path: "referral_sources", name: "referralSources", props: true, component: () => import(/* webpackChunkName: "referralSources" */ '../components/Settings/ReferralSources.vue') },
        { path: "referral_reasons", name: "referralReasons", props: true, component: () => import(/* webpackChunkName: "referralReasons" */ '../components/Settings/ReferralReasons.vue') },
        { path: "service_types", name: "serviceTypes", props: true, component: () => import(/* webpackChunkName: "serviceTypes" */ '../components/Settings/ServiceTypes.vue') },

    ] },

    {
        path: "/dashboard", name: "dashboard", component: Dashboard,
        children: []
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
    




    // { 
    //     path: '/referrals/:referralId', 
    //     name: 'showReferral',
    //     component: () => import(/* webpackChunkName: "showReferral" */ '../components/showReferral/Container'),
    //     props: true,

    // },



    { path: '/referrals/create', component: require('../components/AddReferral.vue').default },



    { path: '/profile', name: 'profile', component: require('../components/Profile.vue').default },
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