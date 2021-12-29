<style scoped>

</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
	<div>
        <div class="card-body">
            <div class="form-inline ml-2">
                <button class="btn btn-secondary btn-sm mr-2" @click="getEmergencies">
					<i class="fas fa-sync-alt"></i>
				</button>

                <select v-model="filter.user_id" @change="getEmergencies" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>All Workers...</option>
					<option v-for='user in users' :value='user.id' :key="user.id">{{ user.full_name }}</option>
                </select>

                <select v-model="filter.year_id" @change="getEmergencies" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>All Years...</option>
                    <option value='2021'>2021</option>
					<!-- <option v-for='month in months' :value='month.id' :key="month.id">{{ month.name }}</option> -->
                </select>

                <select v-model="filter.month_id" @change="getEmergencies" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>All Months...</option>
					<option v-for='month in months' :value='month.id' :key="month.id">{{ month.name }}</option>
                </select>
                
                <select v-model="filter.direct_only" @change="getEmergencies" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>All Beneficiaries...</option>
                    <option value='1'>Direct Only</option>
                </select>

            </div>
            
            <div class="row mt-3">
                <p v-show="!emergencies.length" class="font-italic ml-5">You have no referrals!</p>

                <table v-show="emergencies.length" class="border table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Case</th>
                            <th>Emergency Date</th>
                            <th>Affected Beneficiaries</th>
                            <th>Emergency Type</th>
                            <th>Assigned Worker</th>
                        </tr>
                    </thead>
                    <tbody v-if="emergencies">
                        <tr v-for="emergency in this.emergencies" :key="emergency.id">
                            <td><span>{{ emergency.casee.file_number }}</span></td>
                            <td>
                                <div class="list-unstyled">
                                    <li v-for="beneficiary in emergency.beneficiaries" :key="beneficiary.id">
                                        <span>{{ beneficiary.name }}</span>
                                    </li>
                                </div>
                            </td>
                            <td>{{ emergency.emergency_date | myDateShort }}</td>
                            <td>
                                <div class="list-unstyled">
                                    <li v-for="emergencyType in emergency.emergency_types" :key="emergencyType.id">
                                        <span>{{ emergencyType.name }}</span>
                                    </li>
                                </div>
                            </td>
                            <td>{{ emergency.user.full_name }}</td>
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
            emergencies: [],
            searchText: '',
            searchForm: {
                fileNumber: '',
            },
            format: '',
            regex: '^',
            mask: 'XXX-XXCXXXXX',
            filter: {
                year_id: '2021',
                month_id: '',
                user_id: '',
                direct_only: '',
            }
		}
	},
	methods: {



	},

	created() {
        this.getUsers();
        this.getEmergencies();
		this.getMonths();
	}
}
</script>