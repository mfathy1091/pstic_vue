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
        <div class="card-body">
            <div class="form-inline mr-2 ml-2 mt-3">
                
                <router-link class="btn btn-success btn-sm mr-2"
                :to="{name: 'addPsIntakeLog'}">
                <i class="fas fa-plus-circle"></i><span><b> Intake</b></span>
                </router-link>

                <button class="btn btn-secondary btn-sm mr-2" @click="executeFilter">
					<i class="fas fa-sync-alt"></i>
				</button>

                <select v-model="filter.user_id" @change="executeFilter" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value='-1' disabled>Worker...</option>
					<option v-for='user in users' :value='user.id' :key="user.id">{{ user.full_name }}</option>
                </select>
                <select v-model="filter.month" @change="executeFilter" class="custom-select m-1" id="inlineFormCustomSelectPref">
                    <option value=''>All Months...</option>
                    <option v-for='month in months' :value='month' :key="month.id">{{ month.name }}</option>
                </select>
                <select class="custom-select m-1" id="inlineFormCustomSelectPref">
                    <option value='1'>New + Ongoing</option>
                    <option value='1'>New</option>
                    <option value='2'>Ongoing</option>
                    <option value='3'>Inactive</option>
                    <option value='4'>Closed</option>
                </select>
                
            </div>
            
            <br>

            <div class="card-header bg-white">
                <div class="row mt-3 table-responsive m-0">
                    <p v-show="!referrals.length" class="font-italic ml-5">You have no Cases!</p>

                    <table v-show="referrals.length" class="border table table-hover table-sm">
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
                        <tbody v-if="referrals">
                            <tr v-for="referral in this.referrals" :key="referral.id">
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
                                    :to="{name: 'psIntakeDetails', params: {referralId: referral.id }}"
                                    class="fa fa-eye blue align-middle mr-2">
                                    </router-link>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>


            </div>
            </div>
        <PsIntakeModal
		:v-if="selectedPsIntake.id"
		:psIntakeEditMode='psIntakeEditMode' 
		:selectedPsIntake='selectedPsIntake' 
		v-on:referralsChanged="getCasee()">
		</PsIntakeModal>

    </div>
</template>
<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import router from '../../router'
import axiosMixin from '../../mixins/axiosMixin'
import PsIntakeModal from './PsIntakeModal'

export default {
	components: { 
		Multiselect,
        PsIntakeModal,
	},
    mixins: [axiosMixin],

	data() {
		return {
            psIntakeEditMode: false,
			selectedPsIntake: {},
            users: [],
            referrals: [],
            loading:true,
            currentMonth: '',
            statusesCount: '',
            months: [],
            searchText: '',
            activeCount: '',
            searchForm: {
                fileNumber: '',
            },
            format: '',
            regex: '^',
            mask: 'XXX-XXCXXXXX',
            filter: {
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
            this.getReferrals();
        },
        getReferrals(){
			this.$Progress.start();
            this.$store.state.main.showLoading = true;
			axios.get('/api/referrals', { params: {status_id: this.filter.status_id, month_id: this.filter.month.id, user_id: this.filter.user_id} })
			.then((response) => {
				// success
				this.referrals = response.data.data;
                // this.statusesCount = response.data.statusesCount;
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
        
        showCreatePsIntake(){
			this.psIntakeEditMode = false;
			this.selectedPsIntake = {};
			$('#psIntakeModal').modal('show')
		},

        showEditPsIntake(referral){
			this.psIntakeEditMode = true;
			this.selectedPsIntake = referral;
			$('#psIntakeModal').modal('show')
		},

        filterByStatus(status_id){
            this.filter.status_id = status_id;
            this.getReferrals()
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
        this.getUsers();
        this.executeFilter();
		this.getMonths();
        this.getCurrentMonth();

        Fire.$on('psIntakesChanged', () => {
            this.getReferrals();
		});
	}
}
</script>