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

                <select v-model="filter.year_id" @change="getBeneficiaries" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>All Years...</option>
                    <option value='2021'>2021</option>
					<!-- <option v-for='month in months' :value='month.id' :key="month.id">{{ month.name }}</option> -->
                </select>

                <select v-model="filter.month_id" @change="getBeneficiaries" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>All Months...</option>
					<option v-for='month in months' :value='month.id' :key="month.id">{{ month.name }}</option>
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
                            <th>Assigned Worker</th>
                        </tr>
                    </thead>
                    <tbody v-if="beneficiaries">
                        <tr v-for="beneficiary in this.beneficiaries" :key="beneficiary.id">
                            <td>{{ beneficiary.casee.file_number }}</td>
                            <td>{{ beneficiary.name }}</td>
                            <td>
                                <div class="list-unstyled">
                                    <li v-for="provided_service in beneficiary.provided_services" :key="provided_service.id">
                                        <span>{{ provided_service.service_type.name }}</span>
                                    </li>
                                </div>
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
            users: [],
            months: [],
            budgets: [],
            beneficiaries: [],
            filter: {
                year_id: '2021',
                month_id: '',
                user_id: '',
                direct_only: '',
                budget_id: '',
            }
		}
	},
	methods: {
		getBeneficiaries(){
			this.$Progress.start();
			axios.get('/api/beneficiaries/', { params: { user_id: this.user_id, is_active: this.filter.is_active, budget_id: this.filter.budget_id } } )
			.then((response) => {
				this.beneficiaries = response.data.data;
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
		this.getMonths();
        this.getBudgets();
        this.getBeneficiaries();
	}
}
</script>