import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

import ShowIndividual from './components/ShowIndividual'
import AddReferral from './components/AddReferral'
import ShowReferral from './components/ShowReferral'
import ShowFile from './components/ShowFile'
import Forms from './views/Forms'
import usecom from './vuex/usecom'

let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/roles', component: require('./components/Roles.vue').default },
    { path: '/nationalities', component: require('./components/Nationalities.vue').default },
    { path: '/referral_sources', component: require('./components/ReferralSources.vue').default },
    { path: '/referral_reasons', component: require('./components/ReferralReasons.vue').default },
    { path: '/servicetypes', component: require('./components/Servicetypes.vue').default },

    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/individuals/check', component: require('./components/CheckIndividual.vue').default },
    { path: '/individuals/create_registered', component: require('./components/AddRegisteredIndividual.vue').default },
    { path: '/individuals/:id', component: ShowIndividual },
    
    
    { path: '/referrals/create/:file_id', component: AddReferral, name: 'referral-create'},
    { path: '/referrals/:referral_id', component: ShowReferral },
    { path: '/files/:file_id', component: ShowFile },

]

export default new Router({
    mode: 'history',
    routes,
})