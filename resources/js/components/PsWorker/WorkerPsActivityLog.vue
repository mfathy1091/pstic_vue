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
	<div>

        <div class="form-inline mr-2 ml-2 mt-3">
            <span class="mr-2">From</span>
            <input v-model="filter.start_date" id="activity_date" type="text" name="activity_date" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD">
            <span class="mr-2 ml-2">To</span>
            <input v-model="filter.end_date" id="activity_date" type="text" name="activity_date" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD">

            <select v-model="filter.is_direct" class="custom-select ml-3 mr-sm-2" id="inlineFormCustomSelectPref">
                <option value=''>Direct / Indirect ...</option>
                <option value='1'>Direct</option>
                <option value='0'>Indirect</option>
            </select>

            <button class="btn btn-secondary btn-sm m-1 mr-5" @click="executeFilter">
                <i class="fas fa-bolt"></i>
            </button>
            
        </div>
            
            <p v-if="!pswBeneficiaries.length" class="font-italic ml-5 mt-5">There are no records!</p>
    
            <div v-if="pswBeneficiaries.length" class="row mt-3 table-responsive m-1">
                <table class=" table table-hover border table-sm">
                    <thead>
                        <tr>
                            <th>Case</th>
                            <th>Individual Number</th>
                            <th>Name</th>
                            <th>Is Direct</th>
                            <th>Provided Services</th>
                        </tr>
                    </thead>
                    <tbody v-if="pswBeneficiaries">
                        <tr v-for="referralBeneficiary in this.pswBeneficiaries" :key="referralBeneficiary.id">
                            <td>{{ referralBeneficiary.file_number }}</td>
                            <td>{{ referralBeneficiary.file_individual_number }}</td>
                            <td>{{ referralBeneficiary.name }}</td>
                            <td>{{ referralBeneficiary.is_direct }}</td>
                            <td>
                                <div class="list-unstyled">
                                    <li v-for="provided_service in referralBeneficiary.provided_services" :key="provided_service.id">
                                        <span>{{ provided_service.service_type.name }} </span>
                                        <span> | {{ provided_service.provision_date }}</span>
                                    </li>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
            currentMonth: '',
            statusesCount: '',
            months: [],
            pswBeneficiaries: [],
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
                is_direct: '1',
                start_date: '',
                end_date: '',
                is_direct: '',
                user_id: 'current_user'
            }
		}
	},
	methods: {
        executeFilter(){
            this.getPswBeneficiaries();
            // this.getActiveCount();
        },

        getPswBeneficiaries(){
			this.$Progress.start();
			axios.get('/api/beneficiaries/monthly_referral_beneficiaries', { params: { is_new: this.filter.is_new, is_direct: this.filter.is_direct, start_date: this.filter.start_date, end_date: this.filter.end_date, user_id: this.filter.user_id} })
			.then((response) => {
				// success
				this.pswBeneficiaries = response.data.data;
                this.statusesCount = response.data.statusesCount;
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
				console.log(e);
			})
		},

        filterByStatus(is_direct){
            this.filter.is_direct = is_direct;
            this.getPswBeneficiaries()
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