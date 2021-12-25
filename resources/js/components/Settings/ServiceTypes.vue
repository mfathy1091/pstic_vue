<template>
	<div>
		<div class="card-body">
			<div class="form-inline ml-2">
				<button class="btn btn-success btn-sm mr-2" @click="showCreateServiceTypeModal">
					<i class="fas fa-plus-circle"></i><span><b> Service Type</b></span>
				</button>

				<button class="btn btn-secondary btn-sm mr-5" @click="getServiceTypes">
					<i class="fas fa-sync-alt"></i>
				</button>

				<select @change="getNationalities" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value='-1' disabled>Worker...</option>
					<!-- <option v-for='user in users' :value='user.id' :key="user.id">{{ user.name }}</option> -->
				</select>                
			</div>

			<div class="row mt-3">
				<table class="border table table-hover">
					<thead>
						<tr>
							<th>Name</th>
							<th>Modify</th>
						</tr>
					</thead>
					<tbody v-if="serviceTypes">
						<tr v-for="serviceType in serviceTypes" :key="serviceType.id">
							<td>{{ serviceType.name }}</td>
							<td>
								<span class="clickable mr-2" @click="showEditServiceTypeModal(serviceType)">
									<i class="fas fa-pencil-alt blue"></i>
								</span>
								
								<span class="clickable mr-2" @click="deleteServiceType(serviceType.id)">
									<i class="fa fa-trash red"></i>
								</span>
							</td>
						</tr>
				</tbody>
			</table>
		</div>
	</div>

		<!-- Modal -->
		<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 v-show="!editMode" class="modal-title" id="serviceModalLabel">Create New Service Type</h5>
						<h5 v-show="editMode" class="modal-title" id="serviceModalLabel">Edit Service</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editMode ? updateServiceType() : createServiceType()">
						<div class="modal-body">
							<div class="form-group">
								<label for="name" class="form-label">Name</label>
								<input id="name" v-model="form.name" type="text" name="name" class="form-control">
								<HasError :form="form" field="name" />
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
	</div>
</template>
<script>
import Form from 'vform'
import axiosMixin from '../../mixins/axiosMixin'

export default {
	mixins: [axiosMixin],
	data() {
		return {
			editMode: false,
			serviceTypes: [],
			form: new Form({
				id: '',
				name: '',
			})
		}
	},
	methods: {

		showCreateServiceTypeModal(){
			this.editMode = false;
			this.form.reset()
			$('#serviceModal').modal('show')
		},
		createServiceType() {
			this.$Progress.start();
			this.form.post('/api/service-types')
			.then(() => {
				// success
				Fire.$emit('servicesTypesChanged');

				$('#serviceModal').modal('hide')
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

		showEditServiceTypeModal(service){
			this.editMode = true;
			this.form.reset()
			$('#serviceModal').modal('show')
			this.form.fill(service)
		},
		updateServiceType(){
			this.$Progress.start();
			this.form.put('/api/service-types/'+this.form.id)
			.then(() => {
				// success
				Fire.$emit('servicesTypesChanged');
				$('#serviceModal').modal('hide')
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

		deleteServiceType(id){
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			})
			.then((result) => {
				if (result.isConfirmed) {
					this.$Progress.start();
					this.form.delete('/api/service-types/'+id)
					.then(() => {
						// success
						Fire.$emit('servicesTypesChanged');
						Swal.fire(
							'Deleted!',
							'It has been deleted.',
							'success'
						)
						this.$Progress.finish();
					})
					.catch(() => {
						// error
						Swal("Failed!", "There was something wrong.", "warning");
					});
				}
			})
		},
	},

	created() {
		this.getServiceTypes();
		
		Fire.$on('servicesTypesChanged', () => {
			this.getServiceTypes();
		});

		

		
	}
}
</script>