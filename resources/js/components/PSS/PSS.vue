<style scoped>

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item active" aria-current="page">Intake</li>
            </ol>
        </nav>
        
        <div class="row mt-3 mb-3 pl-3">
            <h5>Intake</h5>
        </div>

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'pssReferrals' }"
                            class="nav-link tab-header" active-class="active">   
                            PSS Referrals
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'pssEmergencies' }"
                            class="nav-link tab-header" active-class="active">   
                            PSS Emergencies
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'pssActivities' }"
                            class="nav-link tab-header" active-class="active">   
                            PSS Activities
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
    name: 'Home',
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