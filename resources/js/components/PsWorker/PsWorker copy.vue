<style scoped>

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item active" aria-current="page">Home</li>
            </ol>
        </nav>
        
        <div class="row mt-3 mb-3 pl-3">
            <h5>{{ currentUser.full_name }}</h5>
        </div>

                <ul class="nav nav-tabs">
                        <li class="nav-item">
                        <router-link
                            :to="{ name: 'pssWorkerHome' }"
                            class="nav-link tab-header" active-class="active">   
                            Home
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'pssWorkerReferrals' }"
                            class="nav-link tab-header" active-class="active">   
                            December Cases
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'pssWorkerReferralsStats' }"
                            class="nav-link tab-header" active-class="active">   
                            All Cases
                        </router-link>
                    </li>
                </ul>
            <router-view :key="$route.path"></router-view>
    

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