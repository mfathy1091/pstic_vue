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

            <span class="mr-2">From</span>
            <input v-model="filter.start_date" id="activity_date" type="text" name="activity_date" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD">
            <span class="mr-2 ml-2">To</span>
            <input v-model="filter.end_date" id="activity_date" type="text" name="activity_date" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD">

            <button class="btn btn-secondary btn-sm m-1 mr-5" @click="executeFilter">
                <i class="fas fa-bolt"></i>
            </button>
        </div>
    
        <br>

            <div class="card-header bg-white">



            <div class="row mt-3 table-responsive m-0">
                <p v-show="!pswReferrals.length" class="font-italic ml-5">Selected Period Has No Active Cases!</p>

                <table v-show="pswReferrals.length" class="border table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Case Number</th>
                            <th>Direct Beneficiaries</th>
                            <th>Source</th>
                            <th>Referral Date</th>
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
                start_date: '',
                end_date: '',
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
			axios.get('/api/referrals/monthly', { params: { is_new: this.filter.is_new, status_id: this.filter.status_id, start_date: this.filter.start_date, end_date: this.filter.end_date, user_id: this.filter.user_id} })
			.then((response) => {
				// success
				this.pswReferrals = response.data.data;
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

        filterByStatus(status_id){
            this.filter.status_id = status_id;
            this.getPswReferrals()
        },

        // getCurrentMonth(){
        //     this.$Progress.start();
		// 	axios.get('/api/months/current' )
		// 	.then((response) => {
		// 		this.currentMonth = response.data.data;
        //         this.filter.month =response.data.data;
		// 		this.$Progress.finish();
		// 	})
		// 	.catch((e) => {
		// 		this.$Progress.fail();
		// 		console.log(e);
		// 	})
        // }

	},

    watch: {
        currentUser (n, o) {
            // this.filter.user_id = _.cloneDeep(n).id;
        },
        // 'filter.month_id'(next, prev) {
        //     this.currentMonth = 1;
        // }
    },

	created() {
        // this.executeFilter();
		this.getMonths();
        this.getCurrentMonth();
        
	}
}
</script>