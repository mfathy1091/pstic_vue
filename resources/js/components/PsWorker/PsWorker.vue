<style scoped>

.tab-header.active{
    border-color: #2196F3 !important;
    border-bottom-style: solid;
    font-weight: bold;
    color: #2196F3 !important;
    /* background-color: #f7f7f7 !important; */
}
.tab-header{
    color: #000000 !important;
}

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>
        
        <div class="row mt-3 mb-3 pl-3">
            <h5>{{ currentUser.full_name }}</h5>
        </div>
        <div class="card">
            <div class="card-header bg-white p-0">
                <ul class="nav">
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'psWorkerHome' }"
                            class="nav-link tab-header" active-class="active">   
                            Home
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'psWorkerCases' }"
                            class="nav-link tab-header" active-class="active">   
                            My Cases
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'psWorkerBeneficiaries' }"
                            class="nav-link tab-header" active-class="active">   
                            My Beneficiaries
                        </router-link>
                    </li>
                    
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'psWorkerEmergencies' }"
                            class="nav-link tab-header" active-class="active">   
                            My Emergencies
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