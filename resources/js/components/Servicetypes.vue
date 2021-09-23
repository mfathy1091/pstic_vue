<template>
	<div class="container-fluid">
		<div class="row mt-5">
			<div class="col-md-12">
				<div class="card">
				<div class="card-header">
					<h3 class="card-title">Service Types</h3>

					<div class="card-tools">
						<button class="btn btn-success" @click="showCreateServiceModal">
							Add New
						</button>
					</div>
					
				</div>
				<!-- /.card-header -->
				<div class="card-body table-responsive p-0">
					<table class="table table-hover text-nowrap">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Modify</th>
							</tr>
						</thead>
						<tbody>
								<tr v-for="service in services" :key="service.id">
									<td>{{ service.id }}</td>
									<td>{{ service.name }}</td>
									<td>
										<a href="#" @click="showEditServiceModal(service)">
											<i class="fa fa-edit blue"></i>
										</a>
										
										<a href="#" @click="deleteService(service.id)">
											<i class="fa fa-trash red"></i>
										</a>
									</td>
								</tr>
						</tbody>
					</table>
				</div>
				<!-- /.card-body -->
				</div>
				<!-- /.card -->
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
					<form @submit.prevent="editMode ? updateService() : createService()">
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
import axiosMixin from '../mixins/axiosMixin'

export default {
	mixins: [axiosMixin],
	data() {
		return {
			editMode: false,
			services: {},
			form: new Form({
				id: '',
				name: '',
			})
		}
	},
	methods: {

		showCreateServiceModal(){
			this.editMode = false;
			this.form.reset()
			$('#serviceModal').modal('show')
		},
		createService() {
			this.$Progress.start();
			this.form.post('/api/services')
			.then(() => {
				// success
				Fire.$emit('servicesChanged');

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

		showEditServiceModal(service){
			this.editMode = true;
			this.form.reset()
			$('#serviceModal').modal('show')
			this.form.fill(service)
		},
		updateService(){
			this.$Progress.start();
			this.form.put('/api/services/'+this.form.id)
			.then(() => {
				// success
				Fire.$emit('servicesChanged');
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

		deleteService(id){
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
					this.form.delete('/api/services/'+id)
					.then(() => {
						// success
						Fire.$emit('servicesChanged');
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
		this.getServices();
		
		Fire.$on('servicesChanged', () => {
			this.getServices();
		});

		

		
	}
}
</script>