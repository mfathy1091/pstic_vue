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
            <div class="form-inline ml-2">
                <button class="btn btn-secondary btn-sm mr-2" @click="getReferrals">
					<i class="fas fa-sync-alt"></i>
				</button>

                <select v-model="filter.user_id" @change="getReferrals" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value='-1' disabled>Worker...</option>
					<option v-for='user in users' :value='user.id' :key="user.id">{{ user.full_name }}</option>
                </select>

                <select v-model="filter.is_new" @change="getReferrals" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                    <option value="-1" disabled>New / Ongoing...</option>
                    <option value="1">New</option>
                    <option value="0">Ongoing</option>
                </select>
                <select v-model="filter.year_id" @change="getEmergencies" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value=''>All Years...</option>
                    <option value='2021'>2021</option>
					<!-- <option v-for='month in months' :value='month.id' :key="month.id">{{ month.name }}</option> -->
                </select>
                
                <select v-model="filter.month_id" @change="getReferrals" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value='-1' disabled>Month...</option>
					<option v-for='month in months' :value='month.id' :key="month.id">{{ month.name }}</option>
                </select>
                
                <select v-model="filter.status_id" @change="getReferrals" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                    <option value="-1" disabled>Status...</option>
                    <option value="1">Active</option>
                    <option value="2">Inactive</option>
                    <option value="3">Closed</option>
                </select>
            </div>
            
            <div class="row mt-3">
                <p v-show="!pswRecords.length" class="font-italic ml-5">You have no PSS intake!</p>

                <table v-show="pswRecords.length" class="border table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Case</th>
                            <th>Affected Beneficiaries</th>
                            <th>Source</th>
                            <th>Referral Date</th>
                            <th>Current Month Status</th>
                            <th>Assigned Worker</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody v-if="pswRecords">
                        <tr v-for="record in this.pswRecords" :key="record.id">
                            <td><span>{{ record.referral.casee.file_number }}</span></td>
                            <td>
                                <div class="list-unstyled">
                                    <li v-for="beneficiary in record.referral.beneficiaries" :key="beneficiary.id">
                                        <span>{{ beneficiary.name }}</span>
                                    </li>
                                </div>
                            </td>
                            <td>{{ record.referral.referral_source.name  }}</td>
                            <td>{{ record.referral.referral_date | myDateShort }}</td>
                            <td>
                                <span>{{ record.month.name }}</span>
                                <span v-show="record.status.name == 'Inactive'" class="badge badge-pill badge-secondary">{{ record.status.name }}</span>
                                <span v-show="record.status.name == 'Active'" class="badge badge-pill badge-success">{{ record.status.name }}</span>
                                <span v-show="record.status.name == 'Closed'" class="badge badge-pill badge-dark">{{ record.status.name }}</span>
                                <span v-show="record.is_new == 1" class="badge badge-pill badge-info">New</span>
                                <span v-show="record.is_new == 0" class="badge badge-pill badge-warning">Ongoing</span>
                            </td>
                            <td>{{ record.referral.current_assigned_psw.full_name }}</td>
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
            pswRecords: [],
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
                year_id: '2021',
                month_id: '13',
                user_id: '-1'
            }
		}
	},
	methods: {

        getReferrals(){
			this.$Progress.start();
			axios.get('/api/records/search', { params: { is_new: this.filter.is_new, status_id: this.filter.status_id, month_id: this.filter.month_id, user_id: this.filter.user_id} })
			.then((response) => {
				// success
				this.pswRecords = response.data.data;
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
        this.getReferrals();
		this.getMonths();
	}
}
</script>