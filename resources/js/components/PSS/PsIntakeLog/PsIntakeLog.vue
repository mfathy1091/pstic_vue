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
                :to="{name: 'addPsIntake'}">
                <i class="fas fa-plus-circle"></i><span><b> Intake</b></span>
                </router-link>

                <button class="btn btn-secondary btn-sm mr-2" @click="getPsIntakes">
					<i class="fas fa-sync-alt"></i>
				</button>

                <select v-model="filter.user_id" @change="getPsIntakes" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value='-1' disabled>Worker...</option>
					<option v-for='user in users' :value='user.id' :key="user.id">{{ user.full_name }}</option>
                </select>

                <select v-model="filter.month" @change="getPsIntakes" class="custom-select m-1">
                    <option value='2022-01-01'>January 2022</option>
                    <option value='2022-02-01'>February 2022</option>
                    <option value='2022-03-01'>March 2022</option>
                    <option value='2022-04-01'>April 2022</option>
                    <option value='2022-05-01'>May 2022</option>
                    <option value='2022-06-01'>June 2022</option>
                </select>
                <select v-model="filter.status_id" @change="getPsIntakes" class="custom-select m-1">
                    <option value='New + Ongoing'>New + Ongoing</option>
                    <option value='New'>New</option>
                    <option value='Ongoing'>Ongoing</option>
                    <option value='Inactive'>Inactive</option>
                    <option value='Closed'>Closed</option>
                </select>
                
            </div>
            
            <br>

            <div class="form-inline mr-2 ml-2 mt-3">
                New Intakes: {{ PsIntakesCountsByStatuses.new }}
                <br>
                Ongoing Intakes: {{ PsIntakesCountsByStatuses.ongoing }}
                <br>
                Inactive Intakes: {{ PsIntakesCountsByStatuses.closed }}
            </div>

            <div class="card-header bg-white">
                <div class="row mt-3 table-responsive m-0">
                    <p v-show="!psIntakes.length" class="font-italic ml-5">You have no Cases!</p>

                    <table v-show="psIntakes.length" class="border table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Status</th>
                                <th>Referral Date</th>
                                <th>Case Number</th>
                                <th>Beneficiaries</th>
                                <th>Source</th>
                                <th>Assigned Worker</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody v-if="psIntakes">
                            <tr v-for="psIntake in this.psIntakes" :key="psIntake.id">
                                <td>{{ psIntake.month }}</td>
                                <td>
                                    <span v-show="psIntake.status == 'New'" class="badge badge-pill badge-info">New</span>
                                    <span v-show="psIntake.status == 'Ongoing'" class="badge badge-pill badge-warning">Ongoing</span>
                                    <span v-show="psIntake.status == 'Closed'" class="badge badge-pill badge-dark">Closed</span>
                                    <span v-show="psIntake.status == 'Inactive'" class="badge badge-pill badge-secondary">Inactive</span>
                                </td>
                                <td>{{ psIntake.referral_date | myDateShort }}</td>
                                <td>{{ psIntakes.file_number }}</td>
                                <td>
                                    <div class="list-unstyled">
                                        <li v-for="beneficiary in psIntake.beneficiaries" :key="beneficiary.id">
                                            <div>
                                                <span>{{ beneficiary.name }}</span>
                                            </div>
                                        </li>
                                    </div>
                                </td>
                                <td>{{ psIntake.referral_source.name  }}</td>
                                <td>{{ psIntake.current_assigned_psw.full_name }}</td>

                                <td>
                                    <router-link
                                    :to="{name: 'psIntakeDetails', params: {psIntakeId: psIntake.id }}"
                                    class="fa fa-eye blue align-middle mr-2">
                                    </router-link>
                                </td>
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
            psIntakes: [],
            PsIntakesCountsByStatuses: {
                new: '1',
                ongoing: '2',
                closed: '3',
            },
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
                month: '2022-05-01',
                status_id: 'New + Ongoing',
                user_id: 'current_user' 
            }

		}
	},
	methods: {  
        getPsIntakes(){
			this.$Progress.start();
            this.$store.state.main.showLoading = true;
			axios.get('/api/ps-intakes', { params: { month: this.filter.month, status_id: this.filter.status_id } })
			.then((response) => {
				// success
				this.psIntakes = response.data.data;
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

        getPsIntakesCountsByStatuses()
        {
            this.$Progress.start();
            this.$store.state.main.showLoading = true;
			axios.get('/api/ps-intakes/countsByStatuses')
			.then((response) => {
				// success
				this.PsIntakesCountsByStatuses = response.data.data;
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


	},
        watch: {
        currentUser (n, o) {
            // this.filter.user_id = _.cloneDeep(n).id;
        },

    },

	created() {
        this.getUsers();
        this.getPsIntakes();

        Fire.$on('psIntakesChanged', () => {
            this.getPsIntakes();
		});
	}
}
</script>