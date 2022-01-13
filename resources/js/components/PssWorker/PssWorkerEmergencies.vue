<style scoped>

.tab-header.active{
    border-color: #2196F3 !important;
    border-bottom-style: solid;
    font-weight: bold;
    color: #2196F3 !important;
    /* background-color: #f7f7f7 !important; */
}
.cases-active.active{
    border-color: #38c172 !important;
    border-bottom-style: solid;
    font-weight: bold;
    color: #38c172 !important;
}
.cases-inactive.active{
    border-color: #e3342f !important;
    border-bottom-style: solid;
    font-weight: bold;
    color: #e3342f !important;
}
.cases-closed.active{
    border-color: #000000 !important;
    border-bottom-style: solid;
    font-weight: bold;
    color: #000000 !important;
}

.tab-header{
    color: #000000 !important;
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
	<div class="p-0">

        <div class="form-inline mr-2 ml-2 mt-3">
            <button class="btn btn-success btn-sm m-1">
            <!-- <button class="btn btn-success btn-sm mr-2" @click="showCreateRoleModal" v-if="$can('role_create')"> -->
                <i class="fas fa-plus-circle"></i><span><b> Referral</b></span>
            </button>
            <button class="btn btn-secondary btn-sm m-1" @click="getPswReferrals">
                <i class="fas fa-sync-alt"></i>
            </button>

            <select v-model="filter.month_id" @change="getPswReferrals" class="custom-select m-1" id="inlineFormCustomSelectPref">
                <option value=''>All Months...</option>
                <option v-for='month in months' :value='month.id' :key="month.id">{{ month.name }}</option>
            </select>
            
        </div>

    
        <br>

        <div class="card mb-0">
            <div class="card-header bg-white">
            <ul class="nav nav-fill">
                <li class="nav-item" role="presentation">
                    
                    <a class="nav-link p-0 tab-header cases-active active" href="#" @click="filterByStatus(1)" role="tab" aria-controls="home" aria-selected="true" data-toggle="tab">
                        <h5>Active <span class="badge badge-success">6</span></h5>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link p-0 tab-header cases-inactive" href="#" @click="filterByStatus(2)" role="tab" aria-controls="home" aria-selected="true" data-toggle="tab">
                        <h5>Inactive <span class="badge badge-danger">6</span></h5>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link p-0 tab-header cases-closed" href="#" @click="filterByStatus(3)" role="tab" aria-controls="home" aria-selected="true" data-toggle="tab">
                        <h5>Closed <span class="badge badge-dark">6</span></h5>
                    </a>
                </li>
            </ul>

            <br>
            <div class="form-inline">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
                    <label class="form-check-label" for="exampleRadios1">
                        New + Ongoing
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
                    <label class="form-check-label" for="exampleRadios2">
                        New
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="option3">
                    <label class="form-check-label" for="exampleRadios3">
                        Ongoing
                    </label>
                </div>
            </div>
            <div class="row mt-3 table-responsive m-0">
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
                status_id: '1',
                month_id: '13',
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

        filterByStatus(status_id){
            this.filter.status_id = status_id;
            this.getPswReferrals()
        }

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
        this.getPswReferralsCountByStatus();
		this.getMonths();
	}
}
</script>