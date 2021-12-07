<style scoped>
.clickable {
    cursor: pointer
}
</style>
<template>
    <div>
        <div class="card card-row" v-if="this.casee">
            <div class="card-header">
                <h3 class="card-title">
                    Referrals Linked to {{ this.casee.number }}
                </h3>

                <div class="card-tools">
                    <button class="btn btn-primary btn-sm" @click="showCreateReferralModal">
                        <i class="fas fa-link"></i> Add Referral
                    </button>
                </div>
                
            </div>
            <div class="card-body row">
                <p v-if="!this.casee.referrals.length" class="ml-5 text-primary">
                    <b>This case has no linked referrals!</b>
                </p>

                    <!-- Referrals -->
                    <div v-for="referral in this.caseeReferrals" :key="referral.id">
                        <div class="card individual-card" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <span class="btn btn-tool clickable text-blue" @click="goToReferralPage(referral)">
                                        {{ referral.referral_source.name }}  |  {{ referral.referral_date }}
                                    </span>
                                    
                                    
                                </h5>
                                <div class="dropdown">
                                
                                    <button class="btn btn-tool float-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <button class="dropdown-item" type="button">Edit</button>
                                        <button class="dropdown-item" type="button">
                                            <span class="text-red">Delete</span>
                                        </button>
                                    </div>
                                </div>

                                <br>
                                <br>

                                <span class="text-muted text-sm"><b>Monthly Records</b></span>
                                <ul class="mb-0 fa-ul text-muted">
                                    <li v-for="record in referral.records" :key="record.id">
                                        <span class="fa-li"></span><b>{{ record.month.name }}</b> {{ record.status.name }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                    </div>

            </div>
        </div>
        <!-- Referral Modal -->
		<ReferralModal v-if="casee" :caseeId="casee.id"
		:v-if="selectedReferral.id"
		:referralEditMode='referralEditMode' 
		:selectedReferral='selectedReferral' 
		v-on:referralsChanged="getCasee()">
		</ReferralModal>

    </div>
</template>

<script>
import Form from 'vform'
import ReferralModal from './ReferralModal'
import Multiselect from 'vue-multiselect'
import router from '../../router'

export default {
    name: 'caseeReferrals',
    components: {
        Multiselect,
        ReferralModal,
        
    },

    props: {
        caseeId:Number,
    },
    data(){
        return {
            casee: '',
            caseeReferrals: [],
            referralEditMode: false,
			selectedReferral: {},
        }
    },
    methods: {
        getCasee(){
			this.$Progress.start();
			axios.get("/api/casees/"+this.caseeId)
            .then(({data}) => {
                this.casee = data.data
            });
			this.$Progress.finish();
        },
        unlinkIndividual(beneficiary_id){
            this.$Progress.start();
			axios.put('/api/beneficiaries/' + beneficiary_id + '/unlink' )
			.then(() => {
				// success
				Fire.$emit('caseebeneficiariesChanged');
				// $('#individualModal').modal('hide')
				Swal.fire(
					'Unlinked!',
					'Unlinked successfully.',
					'success'
				)
				this.$Progress.finish();
			})
			.catch(() => {
				// error
				this.$Progress.fail();
			})
        },
        getCaseeReferrals(){
            this.$Progress.start();
            axios.get('/api/casees/'+this.caseeId+'/referrals', { params: { casee_id: this.caseeId } })
            .then(({data}) => {
                this.caseeReferrals = data.referrals
            });
            this.$Progress.finish();
		},
        
        showCreateReferralModal(){
			this.referralEditMode = false;
			this.selectedReferral = {};
			$('#referralModal').modal('show')
		},

        showEditReferralModal(referral){
            console.log('hi')
			this.referralEditMode = true;
			this.selectedReferral = referral;
			$('#referralModal').modal('show')
		},

        goToReferral(referral){

        },
        goToReferralPage(referral){
			router.push({ path: '/referrals/'+referral.id })
		},

    },
    created() {
        this.getCasee();
        this.getCaseeReferrals();

        Fire.$on('caseebeneficiariesChanged', () => {
            this.getCasee();
        });

        Fire.$on('caseeReferralsChanged', () => {
			console.log('new');
            this.getCaseeReferrals();
		});
    }
}
</script>