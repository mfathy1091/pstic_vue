require('./bootstrap');

import Vue from 'vue';
import router from './router'
import store from './store'

import moment from 'moment';

Vue.mixin({
    data(){
        return{
            abilities: [],
        }
    },
    methods: {
        getAbilities(){
            axios.get("/api/abilities")
            .then(({data}) => {
                this.abilities = data.data
                console.log(this.abilities)
                console.log(this.$can('user_list'))
                }
            );
        },
        $can(permissionName) {
            return this.abilities.indexOf(permissionName) !== -1;
        },
    },
    mounted() {
        // this.getAbilities()
    },
    created(){

        
    }
});


import { ValidationObserver } from 'vee-validate';
import { ValidationProvider } from 'vee-validate/dist/vee-validate.full.esm';

Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);


// import Permissions from './mixins/Permissions';
// Vue.mixin(Permissions);

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

Vue.filter('myDate', function(date){
    return moment(date).format('Do MMMM YYYY');
});

window.Fire = new Vue();



const app = new Vue({
    el: '#app',
    router,
    store,
});


var permissions = []



Vue.prototype.$Perms = [1, 2, 3]


// console.log('can read Post', ability.can('read', 'Post')) // true
// console.log('can read User', ability.can('read', 'User')) // true
// console.log('can update User', ability.can('update', 'User')) // true
// console.log('can delete User', ability.can('delete', 'User')) // false
// console.log('cannot delete User', ability.cannot('delete', 'User')) // true


// axios.get('/api/abilities').then(response => {

//     ability.update(
//         [
//             {subject: 'all', actions: response.data.data}
//         ]
//     )
// })


