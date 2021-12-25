<template>
<!-- Modal -->
		<div class="modal fade" id="nationalityModal" tabindex="-1" aria-labelledby="nationalityModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 v-show="!editMode" class="modal-title" id="nationalityModalLabel">Create New Nationality</h5>
						<h5 v-show="editMode" class="modal-title" id="nationalityModalLabel">Edit Nationality</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editMode ? updateNationality() : createNationality()">
						<div class="modal-body">
							<div class="form-group">
								<label for="name" class="form-label">Name</label>
								<input id="name" v-model="nationalityForm.name" type="text" name="name" class="form-control">
								<HasError :form="nationalityForm" field="name" />
							</div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							<button v-show="!editMode" type="submit" class="btn btn-success">Create</button>
							<button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
						</div>
					</form>
				</div>
			</div>
		</div>
</template>

<script>
import Form from 'vform'
import axiosMixin from '../../../mixins/axiosMixin'

export default {
	mixins: [axiosMixin],
    props:{
        editMode: Boolean,
        selectedNationality: Object,
    },
	data() {
		return {
			nationalityForm: new Form({
				id: '',
				name: '',
			})
		}
	},
    watch: {
        selectedNationality (next, prev){
            this.nationalityForm.fill(this.selectedNationality)
        }
    },

	methods: {

		createNationality() {
			this.$Progress.start();
			this.nationalityForm.post('/api/nationalities')
			.then(() => {
				// success
				Fire.$emit('nationalitiesChanged');

				$('#nationalityModal').modal('hide')
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

		updateNationality(){
            
			this.$Progress.start();
			this.nationalityForm.put('/api/nationalities/'+this.nationalityForm.id)
			.then(() => {
				// success
				Fire.$emit('nationalitiesChanged');
				$('#nationalityModal').modal('hide')
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