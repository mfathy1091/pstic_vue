
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
	<div class="p-0">
        <div class="card-body">
            <div class="form-inline mr-2 ml-2 mt-3">
                
                <router-link class="btn btn-success btn-sm mr-2"
                :to="{name: 'addPsIntake'}">
                <i class="fas fa-plus-circle"></i><span><b> Intake</b></span>
                </router-link>

                <button class="btn btn-secondary btn-sm mr-2" @click="getPsBeneficiaries">
					<i class="fas fa-sync-alt"></i>
				</button>

                <select v-model="filter.user_id" @change="getPsBeneficiaries" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value='-1' disabled>Worker...</option>
					<option v-for='user in users' :value='user.id' :key="user.id">{{ user.full_name }}</option>
                </select>

                <select v-model="filter.status_id" @change="getPsBeneficiaries" class="custom-select m-1" id="inlineFormCustomSelectPref">
                    <option value='1+2'>New + Ongoing</option>
                    <option value='1'>New</option>
                    <option value='2'>Ongoing</option>
                    <option value='3'>Inactive</option>
                    <option value='4'>Closed</option>
                </select>
                
            </div>
            
            <br>

            <div class="card-header bg-white">
                <div class="row mt-3 table-responsive m-0">
                    <p v-show="!beneficiaries.length" class="font-italic ml-5">You have no Cases!</p>

                    <table v-show="beneficiaries.length" class="border table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Intake Status</th>
                                <th>Referral Date</th>
                                <th>Name</th>
                                <th>Nationality</th>
                                <th>Age</th>
                                <th>Is Direct</th>
                                <th>Assigned Worker</th>
                                
                            </tr>
                        </thead>
                        <tbody v-if="beneficiaries">
                            <tr v-for="beneficiary in this.beneficiaries" :key="beneficiary.id">
                            <td>{{ beneficiary.referral_date }}</td>
                                <td>
                                    <span v-show="beneficiary.intake_status == 'Ongoing'" class="badge badge-pill badge-warning">Ongoing</span>
                                    <span v-show="beneficiary.intake_status == 'New'" class="badge badge-pill badge-info">New</span>
                                    <span v-show="beneficiary.intake_status == 'Closed'" class="badge badge-pill badge-dark">Closed</span>
                                    <span v-show="beneficiary.intake_status == 'Inactive'" class="badge badge-pill badge-secondary">Inactive</span>
                                </td>
                                <td>{{ beneficiary.name  }}</td>
                                <td>{{ beneficiary.nationality  }}</td>
                                <td>{{ beneficiary.age }}</td>
                                <td>{{ beneficiary.is_direct }}</td>
                                <td>{{ beneficiary.assigned_worker }}</td>

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
import router from '../../../router'
import axiosMixin from '../../../mixins/axiosMixin'

export default {
	components: { 
		Multiselect,
	},
    mixins: [axiosMixin],

	data() {
		return {
            psIntakeEditMode: false,
			selectedPsIntake: {},
            users: [],
            beneficiaries: [],
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
                status_id: '1+2',
                user_id: 'current_user' 
            }

		}
	},
	methods: {  
        getPsBeneficiaries(){
			this.$Progress.start();
            this.$store.state.main.showLoading = true;
			axios.get('/api/ps/beneficiaries/', { params: { status_id: this.filter.status_id } })
			.then((response) => {
				// success
				this.beneficiaries = response.data.data;
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
            this.getPsBeneficiaries()
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
        this.getPsBeneficiaries();
		this.getMonths();
        this.getCurrentMonth();

        Fire.$on('beneficiariesChanged', () => {
            this.getPsBeneficiaries();
		});
	}
}
</script>