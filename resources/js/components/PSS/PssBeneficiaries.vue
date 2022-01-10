<style scoped>

</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
	<div>
        <div class="card-body">
            <div class="form-inline ml-2">
                <button class="btn btn-secondary btn-sm mr-2" @click="getBeneficiaries">
					<i class="fas fa-sync-alt"></i>
				</button>

                <select v-model="filter.user_id" @change="getBeneficiaries" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>All Workers...</option>
					<option v-for='user in users' :value='user.id' :key="user.id">{{ user.full_name }}</option>
                </select>

                <select v-model="filter.year" @change="getBeneficiaries" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>Choose Year...</option>
                    <option value='2021'>2021</option>
                    <option value='2022'>2022</option>
					<!-- <option v-for='month in months' :value='month.id' :key="month.id">{{ month.name }}</option> -->
                </select>

                <select v-model="filter.month" @change="getBeneficiaries" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>Choose Month...</option>
					<option v-for='month in months' :value='month' :key="month">{{ month }}</option>
                </select>
                
                <select v-model="filter.direct_only" @change="getBeneficiaries" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>All Beneficiaries...</option>
                    <option value='1'>Direct Only</option>
                </select>
                <select v-model="filter.budget_id" @change="getUsers" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>Select Budget</option>
					<option v-for='budget in budgets' :value='budget.id' :key="budget.id">{{ budget.name }}</option>
                </select>   

            </div>
            
            <div class="row mt-3">
                <p v-show="!beneficiaries.length" class="font-italic ml-5">No data found!</p>

                <table v-show="beneficiaries.length" class="border table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Case</th>
                            <th>Name</th>
                            <th>Provided Services</th>
                        </tr>
                    </thead>
                    <tbody v-if="beneficiaries">
                        <tr v-for="beneficiary in this.beneficiaries" :key="beneficiary.id">
                            <td>{{ beneficiary.casee.file_number }}</td>
                            <td>{{ beneficiary.name }}</td>
                            <td>
                                <div class="list-unstyled">
                                    <li v-for="provided_service in beneficiary.provided_services" :key="provided_service.id">
                                        <span>{{ provided_service.service_type.name }} </span>
                                        <span> | {{ provided_service.provision_date }}</span>
                                    </li>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mt-3">
                <h5>Statistics</h5>
                <table class="table table table-sm shade_last_row" style="text-align: center;">
                    <thead class="table-light">
                        <tr>
                            <th>Nationality</th>
                            <th>Total # of Beneficiaries</th>
                            <th>0-5 M</th>
                            <th>0-5 F</th>
                            <th>6-9 M</th>
                            <th>6-9 F</th>
                            <th>10-14 M</th>
                            <th>10-14 F</th>
                            <th>15-17 M</th>
                            <th>15-17 F</th>
                            <th>18-24 M</th>
                            <th>18-24 F</th>
                            <th>25-49 M</th>
                            <th>25-49 F</th>
                            <th>50-59 M</th>
                            <th>50-59 F</th>
                            <th>+60 M</th>
                            <th>+60 F</th>
                        </tr>
                    </thead>
                    <tbody v-if="stats">
                        <tr v-for="stat in this.stats" :key="stat.id">
                            <td>{{ stat.nationality.name }}</td>
                            <td>{{ stat.total }}</td>
                            <td>{{ stat.age_0_5_m }}</td>
                            <td>{{ stat.age_0_5_f }}</td>
                            <td>{{ stat.age_6_9_m }}</td>
                            <td>{{ stat.age_6_9_f }}</td>
                            <td>{{ stat.age_10_14_m }}</td>
                            <td>{{ stat.age_10_14_f }}</td>
                            <td>{{ stat.age_15_17_m }}</td>
                            <td>{{ stat.age_15_17_f }}</td>
                            <td>{{ stat.age_18_24_m }}</td>
                            <td>{{ stat.age_18_24_f }}</td>
                            <td>{{ stat.age_25_49_m }}</td>
                            <td>{{ stat.age_25_49_f }}</td>
                            <td>{{ stat.age_50_59_m }}</td>
                            <td>{{ stat.age_50_59_f }}</td>
                            <td>{{ stat.age_gt_60_m }}</td>
                            <td>{{ stat.age_gt_60_f }}</td>
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
            users: [],
            months: [],
            budgets: [],
            beneficiaries: [],
            stats: [],
            filter: {
                year: '2021',
                month: '',
                user_id: '',
                direct_only: '',
                budget_id: '',
            }
		}
	},
	methods: {
		getBeneficiaries(){
			this.$Progress.start();
			axios.get('/api/beneficiaries/search', { params: { year: this.filter.year, month: this.filter.month, user_id: this.user_id, is_active: this.filter.is_active, budget_id: this.filter.budget_id } } )
			.then((response) => {
				this.beneficiaries = response.data.data;
                this.months = response.data.months;
				this.$Progress.finish();
			})
			.catch((e) => {
				this.$Progress.fail();
				console.log(e);
			})
		},

		getBeneficiariesStats(){
			this.$Progress.start();
			axios.get('/api/beneficiaries/stats' )
			.then((response) => {
				this.stats = response.data.stats;
                // this.months = response.data.months;
				this.$Progress.finish();
			})
			.catch((e) => {
				this.$Progress.fail();
				console.log(e);
			})
		},
	},

	created() {
        this.getUsers();
        this.getBudgets();
        this.getBeneficiaries();
        this.getBeneficiariesStats();
	}
}
</script>