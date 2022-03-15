<style scoped>

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item active" aria-current="page">PSS</li>
            </ol>
        </nav>
        
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