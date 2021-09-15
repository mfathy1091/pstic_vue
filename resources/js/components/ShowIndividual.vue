<template>
    <div>

        <!-- Individual Section -->
        <div class="card mt-3" v-if="this.individual">
            <div class="card-header">
                <h5 class="m-0">
                    {{ this.individual.name }}
                </h5>
            </div>

            <div class="card-body">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Individual ID</th>
                                <th>File Number</th>
                                <th>Passport #</th>                     
                                <th>Relationship</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Nationality</th>
                                <th>Current Phone #</th>
                                <th>Modify</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td>{{ this.individual.name }}</td>
                                    <td>{{ this.individual.individual_id }}</td>
                                    <td>{{ this.individual.file.number }}</td>
                                    <td>{{ this.individual.passport_number }}</td>
                                    <td>{{ this.individual.relationship.name}}</td>
                                    <td>{{ this.individual.age }}</td>
                                    <td>{{ this.individual.gender.name}}</td>
                                    <td>{{ this.individual.nationality.name}}</td>
                                    <td>{{ this.individual.current_phone_number }}</td>
                                    <td>
                                        <!-- <a href="#" @click="showEditIndividualModal(this.individual)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        
                                        <a href="#" @click="deleteIndividual(this.individual.id)">
                                            <i class="fa fa-trash red"></i>
                                        </a> -->
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Referrals Section -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">
                    Referrals
                </h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="gotToCreatePage">
                        <!-- @click="showCreateReferralModal" -->
                        Add New
                    </button>
                    
                </div>
                
            </div>

            <div class="card-body">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Referral Source</th>
                                <th>Referral Date</th>
                                <th>Modify</th>
                            </tr>
                        </thead>
                        <tbody v-if="individualReferrals">
                                <tr v-for="referral in individualReferrals" :key="referral.id">
                                    <td>{{referral.referral_source.name}}</td>
                                    <td>{{referral.referral_date}}</td>
                                    <td>
                                        <a href="#" @click="goToShowReferralPage(referral)">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <!-- <a href="#" @click="showEditIndividualModal(this.individual)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        
                                        <a href="#" @click="deleteIndividual(this.individual.id)">
                                            <i class="fa fa-trash red"></i>
                                        </a> -->
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
import router from '../router'

export default {
    data() {
        return {
            individual: '',
            individualReferrals: [],
        }
    },
    methods: {
        getIndividual(){
			this.$Progress.start();
			axios.get("/api/individuals/"+this.$route.params.id)
            .then(({data}) => {
                this.individual = data.data
            });
			this.$Progress.finish();
            
        },
        
        getIndividualReferrals(){
            this.$Progress.start();
            axios.get('/api/individuals/'+this.$route.params.id+'/referrals', { params: { individual_id: this.$route.params.id } })
            .then(({data}) => {
                this.individualReferrals = data.data
            });
            this.$Progress.finish();
		},
		
        gotToCreatePage(){
			router.push({ path: '/individuals/'+this.$route.params.id+'/referrals/create' })
		},

        goToShowReferralPage(referral){
           // console.log('hello');
		    router.push({ path: '/referrals/'+referral.id })
		},
    },
    created (){
        this.getIndividual()
        this.getIndividualReferrals()
    }
}
</script>
