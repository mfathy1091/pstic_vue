<style scoped>
.clickable {
    cursor: pointer
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>  
        <!-- Referral Details Section -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="m-0">
                    Create New Emergency
                </h5>
            </div>
            <form @submit.prevent="emergencyEditMode ? updateEmergency() : createEmergency()">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                Date / Time
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="worker_name" class="form-label">Worker Name</label>
                                    <input id="worker_name" type="text" name="worker_name" class="form-control" autocomplete="off" :value="currentUser.name" disabled>
                                    <!-- <HasError :form="emergencyForm" field="worker_name" /> -->
                                </div>

                                <div class="form-group">
                                    <label for="emergency_date" class="form-label">Emergency Date</label>
                                    <input v-model="emergencyForm.emergency_date" id="emergency_date" type="text" name="emergency_date" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD">
                                    <!-- <HasError :form="emergencyForm" field="emergency_date" /> -->
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

                            </div>
                            <div class="col">
                                3 of 3
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                Services
                            </div>
                            
                            <div class="col">
                                3 of 3
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
                    <button v-show="!emergencyEditMode" type="submit" class="btn btn-success">Create</button>
                    <button v-show="emergencyEditMode" type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>


    </div>
</template>

<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import axiosMixin from '../../mixins/axiosMixin'	

export default {
	mixins: [axiosMixin],
    name: 'CreateActivity',
    components: {
        Multiselect,
    },
    props: {
        recordId: {
            type: String,
            required: true
        }
    },
    data() {
        return{
            emergencyEditMode: false,
            record: {},
            emergencyTypes: [],
            recordBeneficiaryForm: new Form({
				id: '',
				emergencyTypes: [],
			}),
			emergencyForm: new Form({
				id: '',
				record_id: '',
                emergency_date: '',
                comment: '',
                emergency_type_id: '',
			})
        }
    },
    methods: {
        getRecord() {
			this.$Progress.start();
			axios.get('/api/records/' + this.recordId)
			.then((data) => {
				// success
                this.record = data.data.data;
                this.emergencyForm.record_id = this.record.id
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
                console.log(e);
			})
		},

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
			this.emergencyForm.post('/api/emergencies')
			.then((response) => {
				// success
				// $('#referralModal').modal('hide')
				// Fire.$emit('caseeReferralsChanged');
				
				// this.createdReferral = res.data.data
				
				Toast.fire({
					icon: 'success',
					title: 'Added successfully'
				})
				
				this.$Progress.finish();

				// router.push({ path: '/referrals/'+this.createdReferral.id })
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
			// this.$Progress.start();
			// this.emergencyForm.put('/api/user/'+this.emergencyForm.id)
			// .then(() => {
			// 	// success
			// 	Fire.$emit('usersChanged');
			// 	$('#referralModal').modal('hide')
			// 	Swal.fire(
			// 		'Updated!',
			// 		'It has been updated.',
			// 		'success'
			// 	)
			// 	this.$Progress.finish();
			// })
			// .catch(() => {
			// 	this.$Progress.fail();
			// })
		},

    },

    created (){
        this.getRecord();
        this.getEmergencyTypes();
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