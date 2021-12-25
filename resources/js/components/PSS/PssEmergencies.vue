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
					<option value='-1' disabled>Worker...</option>
					<option v-for='user in users' :value='user.id' :key="user.id">{{ user.name }}</option>
                </select>
                
                <select v-model="filter.month_id" @change="getEmergencies" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value='-1' disabled>Month...</option>
					<option v-for='month in months' :value='month.id' :key="month.id">{{ month.name }}</option>
                </select>
                
            </div>
            
            <div class="row mt-3">
                <p v-show="!emergencies.length" class="font-italic ml-5">You have no referrals!</p>

                <table v-show="emergencies.length" class="border table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Case</th>
                            <th>Affected Beneficiaries</th>
                            <th>Emergency Date</th>
                            <th>Emergency Type</th>
                            <th>Assigned Worker</th>
                        </tr>
                    </thead>
                    <tbody v-if="emergencies">
                        <tr v-for="emergency in this.emergencies" :key="emergency.id">
                            <!-- <td><span>{{ emergency.casee.file_number }}</span></td> -->
                            <td>
                                <div class="list-unstyled">
                                    <li v-for="beneficiary in emergency.beneficiaries" :key="beneficiary.id">
                                        <span>{{ beneficiary.name }}</span>
                                    </li>
                                </div>
                            </td>
                            <td></td>
                            <td>{{ emergency.emergency_date | myDate }}</td>
                            <td>{{ emergency.emergency_type.name }}</td>
                            <td>{{ emergency.user.name }}</td>
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
                is_new: '-1',
                status_id: '-1',
                month_id: '-1',
                user_id: '-1'
            }
		}
	},
	methods: {

        getEmergencies(){
			this.$Progress.start();
			// axios.get('/api/emergencies', { params: { is_new: this.filter.is_new, status_id: this.filter.status_id, month_id: this.filter.month_id, user_id: this.filter.user_id} })
			axios.get('/api/emergencies')
            .then((response) => {
				// success
				this.emergencies = response.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
				console.log(e);
			})
		},

	},

	created() {
        this.getUsers();
        this.getEmergencies();
		this.getMonths();
	}
}
</script>