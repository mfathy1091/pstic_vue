import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

import ShowIndividual from './components/ShowIndividual'
import AddReferral from './components/AddReferral'
import ShowReferral from './components/ShowReferral'
import Forms from './views/Forms'
import usecom from './vuex/usecom'

let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/roles', component: require('./components/Roles.vue').default },
    { path: '/nationalities', component: require('./components/Nationalities.vue').default },
    { path: '/referral_sources', component: require('./components/ReferralSources.vue').default },
    { path: '/referral_reasons', component: require('./components/ReferralReasons.vue').default },

    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/individuals/check', component: require('./components/CheckIndividual.vue').default },
    { path: '/individuals/:id', component: ShowIndividual },
    { path: '/individuals/:id/referrals/create', component: AddReferral },
    { path: '/referrals/:id', component: ShowReferral },
    { path: '/forms', component: Forms },
    { path: '/testvuex', component: usecom },
]

export default new Router({
    mode: 'history',
    routes,
})