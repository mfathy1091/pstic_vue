<style scoped>

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>    
        <div class="row mt-3 mb-3 pl-3">
            <h5>PSS</h5>
        </div>

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'psIntakeLog' }"
                            class="nav-link tab-header" active-class="active">   
                            Intake Log
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'psIntakeLogHistory' }"
                            class="nav-link tab-header" active-class="active">   
                            Intake Log History
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'ps-beneficiaries' }"
                            class="nav-link tab-header" active-class="active">   
                            Beneficiaries
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'disabilities' }"
                            class="nav-link tab-header" active-class="active">   
                            Services
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'pssRecords' }"
                            class="nav-link tab-header" active-class="active">   
                            PSS Records
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
                            :to="{ name: 'pssServices' }"
                            class="nav-link tab-header" active-class="active">   
                            PSS Services
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'pssBeneficiaries' }"
                            class="nav-link tab-header" active-class="active">   
                            PSS Beneficiaries
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