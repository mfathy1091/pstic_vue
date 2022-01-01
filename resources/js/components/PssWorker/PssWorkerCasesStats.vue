<style scoped>
.badge{
	font-size: 0.7rem;
	margin-left: 2px;
	
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
	<div>
        <div class="card-body bg-white">
            <div class="row ml-2">
				<button class="btn btn-success btn-sm mr-2">
				<!-- <button class="btn btn-success btn-sm mr-2" @click="showCreateRoleModal" v-if="$can('role_create')"> -->
					<i class="fas fa-plus-circle"></i><span><b> Referral</b></span>
				</button>
				<button class="btn btn-secondary btn-sm" @click="getPswReferrals">
					<i class="fas fa-sync-alt"></i>
				</button>
            </div>
            <div class="form-inline  mt-3 ml-2">
                
                <select v-model="filter.month_id" @change="getPswReferrals" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>All Months...</option>
					<option v-for='month in months' :value='month.id' :key="month.id">{{ month.name }}</option>
                </select>
                
            </div>
            <div class="form-inline  mt-3 ml-2">
                <span class="mr-2">from</span>
                <select v-model="filter.month_id" @change="getPswReferrals" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>All Months...</option>
					<option v-for='month in months' :value='month.id' :key="month.id">{{ month.name }}</option>
                </select>
                <span class="mr-2">to</span>
                <select v-model="filter.month_id" @change="getPswReferrals" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>All Months...</option>
					<option v-for='month in months' :value='month.id' :key="month.id">{{ month.name }}</option>
                </select>


            </div>
            <div class="form-inline mt-3">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-light border">All</button>
                    <button type="button" class="btn btn-light border">New <span class="badge badge-primary">10</span></button>
                    <button type="button" class="btn btn-light border">Ongoing <span class="badge badge-warning">20</span></button>
                    <button type="button" class="btn btn-light border">Inactive <span class="badge badge-secondary">6</span></button>
                    <button type="button" class="btn btn-light border">Closed <span class="badge badge-dark">4</span></button>
                </div>
            </div>
            
                <div class="tab-content">
                    <div class="row mt-3 table-responsive">
                        <p v-show="!pswReferrals.length" class="font-italic ml-5">You have no Cases!</p>

                        <table v-show="pswReferrals.length" class="border table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Case Number</th>
                                    <th>Direct Beneficiaries</th>
                                    <th>Source</th>
                                    <th>Referral Date</th>
                                    <th>Current Status</th>
                                    <th>Assigned Worker</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody v-if="pswReferrals">
                                <tr v-for="referral in this.pswReferrals" :key="referral.id">
                                    <td>{{ referral.casee.file_number }}</td>
                                    <td></td>
                                    <td>{{ referral.referral_source.name  }}</td>
                                    <td>{{ referral.referral_date | myDateShort }}</td>
                                    <td>
                                        <span v-show="referral.current_record.status.name == 'Inactive'" class="badge badge-pill badge-secondary">{{ referral.current_record.status.name }}</span>
                                        <span v-show="referral.current_record.status.name == 'Active'" class="badge badge-pill badge-success">{{ referral.current_record.status.name }}</span>
                                        <span v-show="referral.current_record.status.name == 'Closed'" class="badge badge-pill badge-dark">{{ referral.current_record.status.name }}</span>
                                        <span v-show="referral.current_record.status.name != 'Closed'">
                                            <span v-show="referral.current_record.is_new == 1" class="badge badge-pill badge-info">New</span>
                                            <span v-show="referral.current_record.is_new == 0" class="badge badge-pill badge-warning">Ongoing</span>
                                        </span>
                                    </td>
                                    <td>{{ referral.current_assigned_psw.full_name }}</td>

                                    <td>
                                        <router-link
                                        :to="{name: 'referralDetails', params: { caseeId: referral.casee.id, referralId: referral.id }}"
                                        class="fa fa-eye blue align-middle mr-2">
                                        </router-link>
                                        <a class="clickable" @click="showEditReferralModal(referral)">
                                            <i class="fas fa-pencil-alt blue mr-2"></i>
                                        </a>
                                        
                                        <a class="clickable">
                                            <i class="fa fa-trash red"></i>
                                        </a>

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
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import router from '../../router'
import axiosMixin from '../../mixins/axiosMixin'

export default {
	components: { 
		Multiselect,
	},
    mixins: [axiosMixin],

	data() {
		return {
            months: [],
            pswReferrals: [],
            searchText: '',
            searchForm: {
                fileNumber: '',
            },
            format: '',
            regex: '^',
            mask: 'XXX-XXCXXXXX',
            filter: {
                is_new: '',
                status_id: '',
                month_id: '12',
                user_id: 'current_user'
            }
		}
	},
	methods: {

        getPswReferrals(){
			this.$Progress.start();
			axios.get('/api/referrals', { params: { is_new: this.filter.is_new, status_id: this.filter.status_id, month_id: this.filter.month_id, user_id: this.filter.user_id} })
			.then((response) => {
				// success
				this.pswReferrals = response.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
				console.log(e);
			})
		},

	},

    computed:{
        currentUser: {
            get(){
                return this.$store.state.currentUser.user;
            }
        }
    },
    watch: {
        currentUser (n, o) {
            // this.filter.user_id = _.cloneDeep(n).id;
        }
    },

	created() {
        this.getPswReferrals();
		this.getMonths();
	}
}
</script>