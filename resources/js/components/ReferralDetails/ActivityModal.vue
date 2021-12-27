<template>
<!-- Modal -->
		<div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 v-show="!activityEditMode" class="modal-title" id="activityModalLabel">Create New Activity</h5>
						<h5 v-show="activityEditMode" class="modal-title" id="activityModalLabel">Edit Activity</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="activityEditMode ? updateActivity() : createActivity()">
						<div class="modal-body">

							<div class="form-group">
								<label for="worker_name" class="form-label">Worker Name</label>
								<input id="worker_name" type="text" name="worker_name" class="form-control" autocomplete="off" :value="currentUser.full_name" disabled>
								<!-- <HasError :form="activityForm" field="worker_name" /> -->
							</div>

							<div class="form-group">
								<label for="activity_date" class="form-label">Activity Date</label>
								<input v-model="activityForm.activity_date" id="activity_date" type="text" name="activity_date" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD">
								<!-- <HasError :form="activityForm" field="activity_date" /> -->
							</div>
							
							<div class="form-group">
								<label for="location" class="form-label">Activity Month</label>
								<select v-model="activityForm.record_id" name="location" id="location" class="form-control">
									<option value='0' disabled selected>Choose</option>
									<option :value="record.id" v-for="record in referral.records" :key="record.id">{{ record.month.name }}</option>
								</select>
								<!-- <HasError :form="activityForm" field="location" /> -->
							</div>

							<div class="form-group">
								<label for="comment" class="form-label">Comment</label>
								<textarea class="form-control" rows="3" v-model="activityForm.comment" name="comment"></textarea>
								<!-- <HasError :form="activityForm" field="comment" /> -->
							</div>
							<hr>

							<div class="form-group">
								<label for="location" class="form-label">Beneficiary</label>
								<select v-model="activityForm.beneficiary_id" name="location" id="location" class="form-control">
									<option value='' selected>Choose..</option>
									<option :value="beneficiary.id" v-for="beneficiary in referralBeneficiaries" v-bind:key="beneficiary.id">{{ beneficiary.name }}</option>
								</select>
								<!-- <HasError :form="activityForm" field="location" /> -->
							</div>

							<label class="typo__label">Provided Services</label>
							
							<div>
								<multiselect 
								v-model="activityForm.service_types" 
								:options="serviceTypes" 
								:multiple="true" 
								:close-on-select="false" 
								:clear-on-select="false" 
								:preserve-search="true" 
								placeholder="Pick some" 
								label="name" 
								track-by="name" 
								:preselect-first="true">
									<!-- <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template> -->
								</multiselect>
								<!-- <pre class="language-json"><code>{{ value  }}</code></pre> -->
							</div>


						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							<button v-show="!activityEditMode" type="submit" class="btn btn-success">Create</button>
							<button v-show="activityEditMode" type="submit" class="btn btn-primary">Update</button>
						</div>
					</form>
				</div>
			</div>
		</div>
</template>

<script>
import Form from 'vform'
import axiosMixin from '../../mixins/axiosMixin'
import Multiselect from 'vue-multiselect'

export default {
	mixins: [axiosMixin],
	components: { Multiselect },
    props:{
		// recordId: {
        //     type: Number,
        //     required: true
        // },
        activityEditMode: {
            type: Boolean,
            required: true
        },
		selectedActivity: {
            // type: Object,
            required: true
        },
    },
	data() {
		return {
			referral: "",
			serviceTypes: [],
			referralBeneficiaries: [],
			activityForm: new Form({
				id: '',
				record_id: '',
				referral_id: '',
				casee_id: '',
                activity_date: '',
                comment: '',
				beneficiary_id: '',
                service_types: [],
				provided_services: [],
			})
		}
	},

	watch: {
        selectedActivity (next, prev){
            if(this.activityEditMode){ // edit
                this.activityForm.fill(this.selectedActivity)
				
				// this.activityForm
				// $this.activityForm.provided_services.forEach(myFunction);

				// function myFunction(item) {
				// 	sum += item;
				// }
			}
			else
			{ // create
                this.resetUserForm();
            }
            
        }
    },

	methods: {
		resetUserForm() { // for create
			this.activityForm.id = ''
			this.activityForm.record_id = ''
			this.activityForm.referral_id = this.$route.params.referralId
			this.activityForm.casee_id = this.$route.params.caseeId
			this.activityForm.activity_date = ''
			this.activityForm.comment = ''
			this.activityForm.service_types = []
			// this.activityForm.beneficiariesServices = []
			// this.appendToBeneficiariesArray()
        },
		appendToBeneficiariesArray(){
			for (let i = 0; i < 3; i++) {
				this.activityForm.beneficiariesServices.push({
					beneficiary:'',
					service_types: [],
				})
			}


			// let beneficiaries = this.referral.beneficiaries;
			// console.log(beneficiaries);
		},
        addToBeneficiariesArray(beneficiary)
        {
			
            let status = document.getElementById('status'+beneficiary.id).value;

            // check if the beneficiary already in the array
            let found = this.recordForm.recordBeneficiaries.find(o => o.beneficiary_id === beneficiary.id);
            
            // if aleady in the array; just update the status
            if(found){
                // if status is execlude; remove the beneficiary
                if($status == -1){
                    this.recordForm.recordBeneficiaries.remove(found);
                }
                found.status = status;
            }
            // if not found; added it to the array
            else{
                //if($status == 1 || $status == 0){
                    let recordBeneficiary = {
                        "beneficiary_id": beneficiary.id,
                        "status": status,
                    };
                    this.recordForm.recordBeneficiaries.push(recordBeneficiary);
                //}

            } 
            console.log(found);
            

        },

        getServiceTypes() {
			this.$Progress.start();
			axios.get('/api/service-types/')
			.then((response) => {
				// success
                this.serviceTypes = response.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
                console.log(e);
			})
		},

        createActivity() {
			this.$Progress.start();
			// this.activityForm.record_id = this.recordId
			this.activityForm.post('/api/activities')
			.then((response) => {
				// success
				$('#activityModal').modal('hide')
				Fire.$emit('referralChanged');
								
				Toast.fire({
					icon: 'success',
					title: 'Added successfully'
				})
				this.$Progress.finish();
			})
			.catch((e) => {
				this.$Progress.fail();
                Toast.fire({
                    icon: 'error',
                    title: e
                })
			})
		},

		updateActivity(){
			this.$Progress.start();
			this.activityForm.put('/api/activities/'+this.activityForm.id)
			.then(() => {
				// success
				Fire.$emit('referralChanged');
				$('#activityModal').modal('hide')
				Swal.fire(
					'Updated!',
					'It has been updated.',
					'success'
				)
				this.$Progress.finish();
			})
			.catch(() => {
				// error
				this.$Progress.fail();
			})
		},

	},

    created (){
		this.getReferral(this.$route.params.referralId)
        this.getServiceTypes();
		this.getReferralBeneficiaries(this.$route.params.referralId);
		
    },
    computed:{
        currentUser: {
            get(){
                return this.$store.state.currentUser.user;
            }
        }
    },
}
</script>