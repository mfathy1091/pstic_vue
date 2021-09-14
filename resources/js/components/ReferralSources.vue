<template>
	<div class="container-fluid">
		<div class="row mt-5">
			<div class="col-md-12">
				<div class="card">
				<div class="card-header">
					<h3 class="card-title">Referral Sources</h3>

					<div class="card-tools">
						<button class="btn btn-success" @click="showCreateReferralSourceModal">
							Add New <i class="fas fa-referralSource-plus fa-fw"></i>
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
								<tr v-for="referralSource in referralSources" :key="referralSource.id">
									<td>{{ referralSource.id }}</td>
									<td>{{ referralSource.name }}</td>
									<td>
										<a href="#" @click="showEditreferralSourceModal(referralSource)">
											<i class="fa fa-edit blue"></i>
										</a>
										
										<a href="#" @click="deleteReferralSource(referralSource.id)">
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
		<div class="modal fade" id="referralSourceModal" tabindex="-1" aria-labelledby="referralSourceModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 v-show="!editMode" class="modal-title" id="referralSourceModalLabel">Create New ReferralSource</h5>
						<h5 v-show="editMode" class="modal-title" id="referralSourceModalLabel">Edit ReferralSource</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editMode ? updateReferralSource() : createReferralSource()">
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
			referralSources: {},
			form: new Form({
				id: '',
				name: '',
			})
		}
	},
	methods: {
		getReferralSources(){			
			this.$Progress.start();
			axios.get("/api/referral-sources").then(({data}) => (this.referralSources = data.data));
			this.$Progress.finish();
		},

		showCreateReferralSourceModal(){
			this.editMode = false;
			this.form.reset()
			$('#referralSourceModal').modal('show')
		},
		createReferralSource() {
			this.$Progress.start();
			this.form.post('/api/referral-sources')
			.then(() => {
				// success
				Fire.$emit('referralSourcesChanged');

				$('#referralSourceModal').modal('hide')
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

		showEditreferralSourceModal(referralSource){
			this.editMode = true;
			this.form.reset()
			$('#referralSourceModal').modal('show')
			this.form.fill(referralSource)
		},
		updateReferralSource(){
			this.$Progress.start();
			this.form.put('/api/referral-sources/'+this.form.id)
			.then(() => {
				// success
				Fire.$emit('referralSourcesChanged');
				$('#referralSourceModal').modal('hide')
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

		deleteReferralSource(id){
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
					this.form.delete('/api/referral-sources/'+id)
					.then(() => {
						// success
						Fire.$emit('referralSourcesChanged');
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
		

		this.getReferralSources();
		
		Fire.$on('referralSourcesChanged', () => {
			this.getReferralSources();
		});

		
	}
}
</script>