<style scoped>

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
            </ol>
        </nav>
        
        <div class="row mt-3 mb-3 pl-3">
            <h5>Settings</h5>
        </div>

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'nationalities' }"
                            class="nav-link tab-header" active-class="active">   
                            Nationalities
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'referralSources' }"
                            class="nav-link tab-header" active-class="active">   
                            Referral Sources
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'referralReasons' }"
                            class="nav-link tab-header" active-class="active">   
                            Referral Reasons
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'serviceTypes' }"
                            class="nav-link tab-header" active-class="active">   
                            Service Types
                        </router-link>
                    </li>
                </ul>
            </div>
            <router-view :key="$route.path"></router-view>
        </div>
    

    </div>
</template>

<script>
import axiosMixin from '../../mixins/axiosMixin'	

export default {
	mixins: [axiosMixin],
    name: 'UserSettings',
    components: {
    },

    data() {
        return {

        }
        
    },
    methods: {

    },

    created (){

    },


    computed:{
        currentUser: {
            get(){
                return this.$store.state.currentUser.user;
            }
        }
    },
}
</script>