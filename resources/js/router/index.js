// (1) import and use
import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)


import ShowIndividual from '../components/ShowIndividual'
import AddReferral from '../components/AddReferral'
import ShowReferral from '../components/ShowReferral'
import ShowFile from '../components/ShowFile'
import Forms from '../views/Forms'
import usecom from '../vuex/usecom'
//import LoginForm from './components/LoginForm'

import Dashboard from "../components/dashboard/Container"
import Users from "../components/users/Container"

const routes = [
    {
        path: "/dashboard",
        name: "dashboard",
        component: Dashboard,
        children: []
    },
    {
        path: "/users",
        name: "Users",
        component: Users,
        children: []
    },
    
    
    { path: '/roles', component: require('../components/Roles.vue').default },
    { path: '/nationalities', component: require('../components/Nationalities.vue').default },
    { path: '/referral_sources', component: require('../components/ReferralSources.vue').default },
    { path: '/referral_reasons', component: require('../components/ReferralReasons.vue').default },
    { path: '/servicetypes', component: require('../components/Servicetypes.vue').default },

    { path: '/profile', component: require('../components/Profile.vue').default },
    { path: '/individuals/check', component: require('../components/CheckIndividual.vue').default },
    { path: '/individuals/create_registered', component: require('../components/AddRegisteredIndividual.vue').default },
    { path: '/individuals/:id', component: ShowIndividual },
    
    
    { path: '/referrals/create/:file_id', component: AddReferral, name: 'referral-create'},
    { path: '/referrals/:referral_id', component: ShowReferral },
    { path: '/files/:file_id', component: ShowFile },
    //{ path: '/login', component: LoginForm },
];


// (2) export router
export default new VueRouter({
    routes: routes
});