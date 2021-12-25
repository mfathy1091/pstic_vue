<template>
<!-- Modal -->
		<div class="modal fade" id="emergencyModal" tabindex="-1" aria-labelledby="emergencyModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 v-show="!emergencyEditMode" class="modal-title" id="emergencyModalLabel">Create New Emergency</h5>
						<h5 v-show="emergencyEditMode" class="modal-title" id="emergencyModalLabel">Edit Emergency</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="emergencyEditMode ? updateEmergency() : createEmergency()">
						<div class="modal-body">

							<div class="form-group">
								<label for="worker_name" class="form-label">Worker Name</label>
								<input id="worker_name" type="text" name="worker_name" class="form-control" autocomplete="off" :value="currentUser.full_name" disabled>
								<!-- <HasError :form="emergencyForm" field="worker_name" /> -->
							</div>

							<div class="form-group">
								<label for="emergency_date" class="form-label">Emergency Date</label>
								<input v-model="emergencyForm.emergency_date" id="emergency_date" type="text" name="emergency_date" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD">
								<!-- <HasError :form="emergencyForm" field="emergency_date" /> -->
							</div>
							
							<div class="form-group">
								<label for="location" class="form-label">Emergency Month</label>
								<select v-model="emergencyForm.record_id" name="location" id="location" class="form-control">
									<option value='0' disabled selected>Choose</option>
									<option :value="record.id" v-for="record in referral.records" :key="record.id">{{ record.month.name }}</option>
								</select>
								<!-- <HasError :form="emergencyForm" field="location" /> -->
							</div>

							<div class="form-group">
								<label for="location" class="form-label">Emergency Type</label>
								<select v-model="emergencyForm.emergency_type_id" name="location" id="location" class="form-control">
									<option value='0' disabled selected>Choose</option>
									<option :value="emergencyType.id" v-for="emergencyType in emergencyTypes" :key="emergencyType.id">{{ emergencyType.name }}</option>
								</select>
								<!-- <HasError :form="emergencyForm" field="location" /> -->
							</div>

							<div class="form-group">
								<label for="comment" class="form-label">Comment</label>
								<textarea class="form-control" rows="3" v-model="emergencyForm.comment" name="comment"></textarea>
								<!-- <HasError :form="emergencyForm" field="comment" /> -->
							</div>

							<div class="form-group" v-if="referral">
								<label class="typo__label">Affected Beneficiaries</label>
								<multiselect 
								v-model="emergencyForm.beneficiaries" 
								:options="referral.beneficiaries" 
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
							<button v-show="!emergencyEditMode" type="submit" class="btn btn-success">Create</button>
							<button v-show="emergencyEditMode" type="submit" class="btn btn-primary">Update</button>
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
        emergencyEditMode: {
            type: Boolean,
            required: true
        },
		selectedEmergency: {
            // type: Object,
            required: true
        },
    },
	data() {
		return {
			referral: "",
			emergencyTypes: [],
			emergencyForm: new Form({
				id: '',
				record_id: '',
				referral_id: '',
				casee_id: '',
                emergency_date: '',
                comment: '',
                emergency_type_id: '',
				beneficiaries: [],
			})
		}
	},
    watch: {
        selectedEmergency (next, prev){
            this.emergencyForm.fill(this.selectedEmergency);
			this.emergencyForm.referral_id = this.$route.params.referralId
			this.emergencyForm.casee_id = this.$route.params.caseeId
        }
    },

	methods: {


        getEmergencyTypes() {
			this.$Progress.start();
			axios.get('/api/emergency-types/')
			.then((response) => {
				// success
                this.emergencyTypes = response.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
                console.log(e);
			})
		},

        createEmergency() {
			this.$Progress.start();
			// this.emergencyForm.record_id = this.recordId
			this.emergencyForm.post('/api/emergencies')
			.then((response) => {
				// success
				$('#emergencyModal').modal('hide')
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

		updateEmergency(){
			this.$Progress.start();
			this.emergencyForm.put('/api/emergencies/'+this.emergencyForm.id)
			.then(() => {
				// success
				Fire.$emit('referralChanged');
				$('#emergencyModal').modal('hide')
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
        this.getEmergencyTypes();
		console.log(this.$route.params.referralId);
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