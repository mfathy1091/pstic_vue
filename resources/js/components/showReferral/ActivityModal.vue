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
								<label for="name" class="form-label">Activity Date</label>
								<input id="name" v-model="activityForm.activity_date" type="text" name="activity_date" class="form-control">
								<HasError :form="activityForm" field="activity_date" />
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

export default {
	mixins: [axiosMixin],
    props:{
        activityEditMode: Boolean,
        selectedActivity: Object,
    },
	data() {
		return {
			activityForm: new Form({
				id: '',
				activity_date: '',
			})
		}
	},
    watch: {
        selectedActivity (next, prev){
            this.activityForm.fill(this.selectedActivity)
        }
    },

	methods: {

		createActivity() {
			this.$Progress.start();
			this.activityForm.post('/api/activities')
			.then(() => {
				// success
				Fire.$emit('recordsChanged');

				$('#activityModal').modal('hide')
				Toast.fire({
					icon: 'success',
					title: 'Added successfully'
				})
				
				this.$Progress.finish();
			})
			.catch(() => {
				// error
				this.$Progress.fail();
			})
		},

		updateActivity(){
            
			this.$Progress.start();
			this.activityForm.put('/api/nationalities/'+this.activityForm.id)
			.then(() => {
				// success
				Fire.$emit('recordsChanged');
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

	created() {
	}
}
</script>