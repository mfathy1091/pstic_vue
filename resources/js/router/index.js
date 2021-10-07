// (1) import and use
import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)


import ShowIndividual from '../components/ShowIndividual'
import AddReferral from '../components/AddReferral'

import ShowFile from '../components/ShowFile'
import Forms from '../views/Forms'
import usecom from '../vuex/usecom'
//import LoginForm from './components/LoginForm'

import Dashboard from "../components/dashboard/Container"
import Users from "../components/users/Container"
import ShowReferral from '../components/showReferral/Container'
import NotFound from '../components/NotFound'

const routes = [
    {
        path: "/dashboard", name: "dashboard", component: Dashboard,
        children: []
    },
    {
        path: "/users", name: "Users", component: Users,
        children: []
    },
    // {path: '/files/:file_id/referrals/create', name: 'referral-create', component: AddReferral},
    {
        path: "/files", component: ShowReferral,
        children: [
            {
                path: "show", component: ShowFile,
                // children: [
                //     {path: '/referrals/create', name: 'referral-create', component: AddReferral},
                //     {path: "/referrals/:referral_id", name: "show-referral", component: ShowReferral},
                // ]
            },

                //path: '/referrals/create/:file_id', name: 'referral-create', component: AddReferral},
        ]
    },

    { path: '/files/:file:id/referrals/create', component: require('../components/AddReferral.vue').default },
    { path: '/roles', component: require('../components/Roles.vue').default },
    { path: '/nationalities', component: require('../components/Nationalities.vue').default },
    { path: '/referral_sources', component: require('../components/ReferralSources.vue').default },
    { path: '/referral_reasons', component: require('../components/ReferralReasons.vue').default },
    { path: '/servicetypes', component: require('../components/Servicetypes.vue').default },

    { path: '/profile', component: require('../components/Profile.vue').default },
    { path: '/individuals/check', component: require('../components/CheckIndividual.vue').default },
    { path: '/individuals/create_registered', component: require('../components/AddRegisteredIndividual.vue').default },
    { path: '/individuals/:id', component: ShowIndividual },
    { path: '/404', component: NotFound },  
    // { path: '*', redirect: '/404' },  
    { path: '*', component: NotFound },
    
    
    
    
    //{ path: '/login', component: LoginForm },
];


// (2) export router
export default new VueRouter({
    routes: routes
});