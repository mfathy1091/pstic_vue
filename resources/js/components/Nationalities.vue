<template>
	<div class="container-fluid">
		<div class="row mt-5">
			<div class="col-md-12">
				<div class="card">
				<div class="card-header">
					<h3 class="card-title">Nationalities</h3>

					<div class="card-tools">
						<button class="btn btn-success" @click="showCreateNationalityModal">
							Add New <i class="fas fa-nationality-plus fa-fw"></i>
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
								<tr v-for="nationality in nationalities" :key="nationality.id">
									<td>{{ nationality.id }}</td>
									<td>{{ nationality.name }}</td>
									<td>
										<a href="#" @click="showEditNationalityModal(nationality)">
											<i class="fa fa-edit blue"></i>
										</a>
										
										<a href="#" @click="deleteNationality(nationality.id)">
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

export default {
	data() {
		return {
			editMode: false,
			nationalities: {},
			form: new Form({
				id: '',
				name: '',
			})
		}
	},
	methods: {
		loadNationalities(){
			this.$Progress.start();
			axios.get("/api/nationalities")
			.then(({data}) => {
				this.nationalities = data.data
			});
			this.$Progress.finish();
		},

		showCreateNationalityModal(){
			this.editMode = false;
			this.form.reset()
			$('#nationalityModal').modal('show')
		},
		createNationality() {
			this.$Progress.start();
			this.form.post('/api/nationalities')
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

		showEditNationalityModal(nationality){
			this.editMode = true;
			this.form.reset()
			$('#nationalityModal').modal('show')
			this.form.fill(nationality)
		},
		updateNationality(){
			this.$Progress.start();
			this.form.put('/api/nationalities/'+this.form.id)
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

		deleteNationality(id){
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
					this.form.delete('/api/nationalities/'+id)
					.then(() => {
						// success
						Fire.$emit('nationalitiesChanged');
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
		// console.log($getPermissions());
		

		this.loadNationalities();
		
		Fire.$on('nationalitiesChanged', () => {
			this.loadNationalities();
		});

		
	}
}
</script>