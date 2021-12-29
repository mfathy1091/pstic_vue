<style scoped>
.badge{
	font-size: 0.7rem;
	margin-left: 2px;
	
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
	<div>
        <div class="card-body">
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
                
                <select v-model="filter.is_new" @change="getPswReferrals" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                    <option value="-1" disabled>New / Ongoing...</option>
                    <option value="1">New</option>
                    <option value="0">Ongoing</option>
                </select>
                
                <select v-model="filter.month_id" @change="getPswReferrals" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value='-1' disabled>Month...</option>
					<option v-for='month in months' :value='month.id' :key="month.id">{{ month.name }}</option>
                </select>
                
                <select v-model="filter.status_id" @change="getPswReferrals" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                    <option value="-1" disabled>Status...</option>
                    <option value="1">Active</option>
                    <option value="2">Inactive</option>
                    <option value="3">Closed</option>
                </select>
            </div>
            
            <div class="row mt-3">
                <p v-show="!pswReferrals.length" class="font-italic ml-5">You have no referrals!</p>

                <table v-show="pswReferrals.length" class="border table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Source</th>
                            <th>Referral Date</th>
                            <th>Current Month Status</th>
                            <th>Assigned Worker</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody v-if="pswReferrals">
                        <tr v-for="referral in this.pswReferrals" :key="referral.id">
                            <td>{{ referral.referral_source.name  }}</td>
                            <td>{{ referral.referral_date | myDateShort }}</td>
                            <td>
                                <div class="list-unstyled">
                                    <li v-for="record in referral.records" :key="record.id">
                                        <div>
                                            <span>{{ record.month.name }}</span>
                                            <span v-show="record.status.name == 'Inactive'" class="badge badge-pill badge-secondary">{{ record.status.name }}</span>
                                            <span v-show="record.status.name == 'Active'" class="badge badge-pill badge-success">{{ record.status.name }}</span>
                                            <span v-show="record.status.name == 'Closed'" class="badge badge-pill badge-dark">{{ record.status.name }}</span>
                                            <span v-show="record.is_new == 1" class="badge badge-pill badge-info">New</span>
                                        </div>

                                    </li>
                                </div>

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
                is_new: '-1',
                status_id: '-1',
                month_id: '-1',
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