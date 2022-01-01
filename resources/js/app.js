require('./bootstrap');

import Vue from 'vue';
import router from './router'
import store from './store'

import moment from 'moment';





import { ValidationObserver } from 'vee-validate';
import { ValidationProvider } from 'vee-validate/dist/vee-validate.full.esm';

Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);


// import Permissions from './mixins/Permissions';
// Vue.mixin(Permissions);
import Chartkick from 'vue-chartkick'
import Chart from 'chart.js'

Vue.use(Chartkick.use(Chart))


import VueProgressBar from 'vue-progressbar'
const options = {
    color: '#bffaf3',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
}

Vue.use(VueProgressBar, options)

// import { Form, HasError, AlertError } from 'vform'
import Form from 'vform'

import {
    Button,
    HasError,
    AlertError,
    AlertErrors,
    AlertSuccess
} from 'vform/src/components/bootstrap5'

Window.Form = Form
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

// import it
import Swal from 'sweetalert2'
import { subject } from '@casl/ability';

window.Swal = Swal;
// register it
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

// make Toast available globally
window.Toast = Toast;


Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('myDateFull', function(date){
    // return moment(date).format('Do MMMM YYYY');
    return moment(date).format('MMMM YYYY');
});

Vue.filter('myDateShort', function(date){
    return moment(date).format('D/M/YYYY');
});

window.Fire = new Vue();




// Golbal mixin for abilities
Vue.mixin({
    methods: {
        
        $can(permissionName) {
            var abilities = this.$store.state.currentUser.abilities;
            if(abilities.length > 0){
                return abilities.indexOf(permissionName) !== -1;
            }
        },
    },
});


Vue.component('login-form', require("./components/LoginForm.vue").default);
Vue.component('app-container', require("./components/AppContainer.vue").default);

const app = new Vue({
    el: '#app',
    router,
    store,
});



