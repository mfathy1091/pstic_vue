import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

import ShowIndividual from './components/ShowIndividual'

let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/roles', component: require('./components/Roles.vue').default },
    { path: '/nationalities', component: require('./components/Nationalities.vue').default },
    { path: '/referral-sources', component: require('./components/ReferralSources.vue').default },
    { path: '/add-referral', component: require('./components/AddReferral.vue').default },

    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/individuals/:id', component: ShowIndividual }
]

export default new Router({
    mode: 'history',
    routes
})