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
                <i class="fas fa-plus-circle"></i><span><b> PSS Intake</b></span>
            </button>
            <button class="btn btn-secondary btn-sm m-1" @click="executeFilter">
                <i class="fas fa-sync-alt"></i>
            </button>

            <select v-model="filter.month" @change="executeFilter" class="custom-select m-1" id="inlineFormCustomSelectPref">
                <option value=''>All Months...</option>
                <option v-for='month in months' :value='month' :key="month.id">{{ month.name }}</option>
            </select>
            
        </div>
    
        <br>

        <div class="card-header bg-white">
            <ul class="nav nav-fill">
                <li class="nav-item" role="presentation">
                    
                    <a class="nav-link p-0 tab-header cases-active active" href="#" @click="filterByStatus(1)" role="tab" aria-controls="home" aria-selected="true" data-toggle="tab">
                        <h5>Active 
                            <span v-if="statusesCount.Active" class="badge badge-success">{{ statusesCount.Active }}</span>
                            <span v-if="!statusesCount.Active" class="badge badge-success">0</span>
                        </h5>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link p-0 tab-header cases-inactive" href="#" @click="filterByStatus(2)" role="tab" aria-controls="home" aria-selected="true" data-toggle="tab">
                        <h5>Inactive 
                            <span v-if="statusesCount.Inactive" class="badge badge-danger">{{ statusesCount.Inactive }}</span>
                            <span v-if="!statusesCount.Inactive" class="badge badge-danger">0</span>
                        </h5>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link p-0 tab-header cases-closed" href="#" @click="filterByStatus(3)" role="tab" aria-controls="home" aria-selected="true" data-toggle="tab">
                        <h5>Closed 
                            <span v-if="statusesCount.Closed" class="badge badge-dark">{{ statusesCount.Closed }}</span>
                            <span v-if="!statusesCount.Closed" class="badge badge-dark">0</span>
                        </h5>
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
                            <th>Status in {{ filter.month.name }}</th>
                            <th>Assigned Worker</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody v-if="pswReferrals">
                        <tr v-for="referral in this.pswReferrals" :key="referral.id">
                            <td>{{ referral.file_number }}</td>
                            <td></td>
                            <td>{{ referral.referral_source.name  }}</td>
                            <td>{{ referral.referral_date | myDateShort }}</td>
                            <td>
                                <span v-show="referral.status_id == '2'" class="badge badge-pill badge-secondary">Inactive</span>
                                <span v-show="referral.status_id == '1'" class="badge badge-pill badge-success">Active</span>
                                <span v-show="referral.status_id == '3'" class="badge badge-pill badge-dark">Closed</span>
                                <span v-show="referral.status_id != '3'">
                                    <span v-show="referral.is_new == 1" class="badge badge-pill badge-info">New</span>
                                    <span v-show="referral.is_new == 0" class="badge badge-pill badge-warning">Ongoing</span>
                                </span>
                            </td>
                            <td>{{ referral.current_assigned_psw.full_name }}</td>

                            <td>
                                <router-link
                                :to="{name: 'pssIntakeDetails', params: { caseeId: caseeId, referralId: referral.id }}"
                                class="fa fa-eye blue align-middle mr-2">
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>


        </div>




    </div>
</template>
<script>
import { HalfCircleSpinner } from 'epic-spinners'
import { mapGetters } from 'vuex';
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import router from '../../router'
import axiosMixin from '../../mixins/axiosMixin'

export default {
	components: { 
		Multiselect,
	},
    mixins: [axiosMixin],

    computed: {
        
        isLoading() {
            return this.$store.state.main.showLoading;
        },
        currentUser: {
            get(){
                return this.$store.state.currentUser.user;
            }
        }
    },

	data() {
		return {
            loading:true,
            currentMonth: '',
            statusesCount: '',
            months: [],
            pswReferrals: [],
            searchText: '',
            activeCount: '',
            searchForm: {
                fileNumber: '',
            },
            format: '',
            regex: '^',
            mask: 'XXX-XXCXXXXX',
            filter: {
                is_new: '',
                status_id: '1',
                month: {
                    id: 13,
                    name: 'January',
                },
                user_id: 'current_user'
            }
		}
	},
	methods: {
        executeFilter(){
            this.getPswReferrals();
        },

        getPswReferrals(){
			this.$Progress.start();
            this.$store.state.main.showLoading = true;
			axios.get('/api/referrals', { params: { is_new: this.filter.is_new, status_id: this.filter.status_id, month_id: this.filter.month.id, user_id: this.filter.user_id} })
			.then((response) => {
				// success
				this.pswReferrals = response.data.data;
                this.statusesCount = response.data.statusesCount;
				this.$Progress.finish();
                this.$store.state.main.showLoading = false;
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
                this.$store.state.main.showLoading = false;
				console.log(e);
			})
		},

        filterByStatus(status_id){
            this.filter.status_id = status_id;
            this.getPswReferrals()
        },

        getCurrentMonth(){
            this.$Progress.start();
			axios.get('/api/months/current' )
			.then((response) => {
				this.currentMonth = response.data.data;
                this.filter.month =response.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				this.$Progress.fail();
				console.log(e);
			})
        }

	},

    watch: {
        currentUser (n, o) {
            // this.filter.user_id = _.cloneDeep(n).id;
        },
        'filter.month_id'(next, prev) {
            this.currentMonth = 1;
        }
    },

	created() {
        this.executeFilter();
		this.getMonths();
        this.getCurrentMonth();
        
	}
}
</script>