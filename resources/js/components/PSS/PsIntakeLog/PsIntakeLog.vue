<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
	<div class="p-0">
        <div class="card-body">
            
            <div class="row">
                <div class="card text-center col bg-white m-1 p-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <span class="card-text">Active</span>
                                <br>
                                <span class="info-box-number font-weight-bold">{{ statusesCounts.countActive }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <span class="badge badge-pill badge-info">New</span>
                                <br>
                                <span class="info-box-number font-weight-bold">{{ statusesCounts.countNew }}</span>
                            </div>
                            <div class="border-right"></div>
                            <div class="col">
                                <span class="badge badge-pill badge-warning">Ongoing</span>
                                <br>
                                <span class="info-box-number font-weight-bold">{{ statusesCounts.countOngoing }}</span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card text-center col bg-secondary m-1 p-0">
                    <div class="card-body">
                        <span class="card-text">Inactive</span>
                        <br>
                        <span class="info-box-number font-weight-bold">{{ statusesCounts.countInactive }}</span>
                    </div>
                </div>

                <div class="card text-center col bg-dark m-1 p-0">
                    <div class="card-body">
                        <span class="card-text">Closed</span>
                        <br>
                        <span class="info-box-number font-weight-bold">{{ statusesCounts.countClosed }}</span>
                    </div>
                </div>
            </div>

            <div class="form-inline mr-2 ml-2 mt-3">
                
                <router-link class="btn btn-success btn-sm mr-2"
                :to="{name: 'addPsIntake'}">
                <i class="fas fa-plus-circle"></i><span><b> Intake</b></span>
                </router-link>

                <button class="btn btn-secondary btn-sm mr-2" @click="getPsIntakes">
					<i class="fas fa-sync-alt"></i>
				</button>

                <select v-model="psIntakesFilter.month" @change="getPsIntakes" class="custom-select m-1">
                    <option value='2022-01-01'>January 2022</option>
                    <option value='2022-02-01'>February 2022</option>
                    <option value='2022-03-01'>March 2022</option>
                    <option value='2022-04-01'>April 2022</option>
                    <option value='2022-05-01'>May 2022</option>
                    <option value='2022-06-01'>June 2022</option>
                </select>

                <select v-model="psIntakesFilter.status" @change="getPsIntakes" class="custom-select m-1">
                    <option value='All'>All</option>
                    <option value='Active'>Active</option>
                    <option value='Inactive'>Inactive</option>
                    <option value='Closed'>Closed</option>
                </select>

                <select v-model="psIntakesFilter.is_new" @change="getPsIntakes" class="custom-select m-1">
                    <option value='All'>All</option>
                    <option value='1'>New</option>
                    <option value='0'>Ongoing</option>
                </select>

                <br>

                <multiselect 
                    v-model="psIntakesFilter.users" 
                    :options="users" 
                    :multiple="true" 
                    :close-on-select="true" 
                    :clear-on-select="true" 
                    :preserve-search="false" 
                    placeholder="Pick some" 
                    label="full_name" 
                    track-by="full_name" 
                    :preselect-first="true">
                    <!-- <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template> -->
                </multiselect>
                <!-- <pre class="language-json"><code>{{ value  }}</code></pre> -->

                <select v-model="psIntakesFilter.user_id" @change="getPsIntakes" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value='-1' disabled>PS Worker...</option>
					<option v-for='user in users' :value='user.id' :key="user.id">{{ user.full_name }}</option>
                </select>

            </div>
            
            <br>

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
                                    <span v-show="psIntake.status == 'Closed'" class="badge badge-pill badge-dark">Closed</span>
                                    <span v-show="psIntake.status == 'Active'&& psIntake.is_new == '0'" class="badge badge-pill badge-success">Active</span>
                                    <span v-show="psIntake.status == 'Active' && psIntake.is_new == '1'" class="badge badge-pill badge-success">Active: NEW</span>
                                    <span v-show="psIntake.status == 'Inactive'&& psIntake.is_new == '0'" class="badge badge-pill badge-secondary">Inactive</span>
                                    <span v-show="psIntake.status == 'Inactive' && psIntake.is_new == '1'" class="badge badge-pill badge-secondary">Inactive: NEW</span>
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
import moment from 'moment'

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
            months: [],

            psIntakes: [],
            statusesCounts: {},
            psIntakesFilter: {
                month: moment().startOf('month').format('YYYY-MM-DD'),
                status: 'All',
                is_new: 'All',
                user_id: 'All' 
            },

            loading:true,
            


		}
	},
	methods: {  
        getPsIntakes(){
			this.$Progress.start();
            this.$store.state.main.showLoading = true;
			axios.get('/api/ps-intakes', { params: this.psIntakesFilter })
			.then((response) => {
				// success
				this.psIntakes = response.data.psIntakes;
                this.statusesCounts = response.data.statusesCounts;
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
            // this.psIntakesFilter.user_id = _.cloneDeep(n).id;
        },

    },
    mounted() {

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