<style scoped>
.clickable {
    cursor: pointer
}
</style>
<template>

    <div>
        <div class="card-body bg-white pt-2 p-0" v-if="casee">
            <div class="row m-3">
				<button class="btn btn-success btn-sm mr-2" @click="showCreateReferralModal">
				<!-- <button class="btn btn-success btn-sm mr-2" @click="showCreateRoleModal" v-if="$can('role_create')"> -->
					<i class="fas fa-plus-circle"></i><span><b> Referral</b></span>
				</button>
				<button class="btn btn-secondary btn-sm" @click="getCaseeReferrals">
					<i class="fas fa-sync-alt"></i>
				</button>
			</div>

            <p v-if="!casee.referrals.length" class="ml-5 text-primary">
                <b>This case has no linked referrals!</b>
            </p>

            <table class="table table-hover table-striped table-sm border">
                <thead>
                    <tr>
                        <th>Source</th>
                        <th>Referral Date</th>
                        <th>Current Month Status</th>
                        <th>Assigned Worker</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="referral in this.caseeReferrals" :key="referral.id">
                        <td>{{ referral.referral_source.name  }}</td>
                        <td>{{ referral.referral_date | myDate }}</td>
                        <td>
                            <div class="list-unstyled">
                                <li v-for="(record, i) in referral.records" :key="record.id">
                                    <div v-show="i==0">
                                        <span>{{ record.month.name }}</span>
                                        <span v-show="record.status.name == 'Inactive'" class="badge badge-pill badge-secondary">{{ record.status.name }}</span>
                                        <span v-show="record.status.name == 'Active'" class="badge badge-pill badge-success">{{ record.status.name }}</span>
                                        <span v-show="record.status.name == 'Closed'" class="badge badge-pill badge-dark">{{ record.status.name }}</span>
                                        <span v-show="record.is_new == 1" class="badge badge-pill badge-info">New</span>
                                    </div>

                                </li>
                            </div>

                        </td>
                        <td>{{ referral.current_assigned_psw.name }}</td>

                        <td>
                            <a class="clickable">
                                <i class="fa fa-edit blue"></i>
                            </a>
                            
                            <a class="clickable">
                                <i class="fa fa-trash red"></i>
                            </a>
                            <router-link
                            :to="{name: 'referralDetails', params: { caseeId: caseeId, referralId: referral.id }}"
                            class="fa fa-eye blue align-middle"
                            >
                            
                            </router-link>
                        </td>
                    </tr>
                </tbody>
            </table>

            

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
        caseeId: {
            // type: Number, 
            required: true
        }
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

        getCaseeReferrals(){
            this.$Progress.start();
            axios.get('/api/casees/'+this.caseeId+'/referrals', { params: { casee_id: this.caseeId } })
            .then(({data}) => {
                this.caseeReferrals = data.data
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