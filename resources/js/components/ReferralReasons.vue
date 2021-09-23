<template>
	<div class="container-fluid">
		<div class="row mt-5">
			<div class="col-md-12">
				<div class="card">
				<div class="card-header">
					<h3 class="card-title">Referral Reasons</h3>

					<div class="card-tools">
						<button class="btn btn-success" @click="showCreateReferralReasonModal">
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
						<tbody v-if="this.referralReasons">
								<tr v-for="referralReason in referralReasons" :key="referralReason.id">
									<td>{{ referralReason.id }}</td>
									<td>{{ referralReason.name }}</td>
									<td>
										<a href="#" @click="showEditReferralReasonModal(referralReason)">
											<i class="fa fa-edit blue"></i>
										</a>
										
										<a href="#" @click="deleteReferralReason(referralReason.id)">
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
		<div class="modal fade" id="referralReasonModal" tabindex="-1" aria-labelledby="referralReasonModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 v-show="!editMode" class="modal-title" id="referralReasonModalLabel">Create New referralReason</h5>
						<h5 v-show="editMode" class="modal-title" id="referralReasonModalLabel">Edit referralReason</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editMode ? updateReferralReason() : createReferralReason()">
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
			referralReasons: {},
			form: new Form({
				id: '',
				name: '',
			})
		}
	},
	methods: {

		showCreateReferralReasonModal(){
			this.editMode = false;
			this.form.reset()
			$('#referralReasonModal').modal('show')
		},
		createReferralReason() {
			this.$Progress.start();
			this.form.post('/api/referral_reasons')
			.then(() => {
				// success
				Fire.$emit('referralReasonsChanged');

				$('#referralReasonModal').modal('hide')
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

		showEditReferralReasonModal(referralReason){
			this.editMode = true;
			this.form.reset()
			$('#referralReasonModal').modal('show')
			this.form.fill(referralReason)
		},
		updateReferralReason(){
			this.$Progress.start();
			this.form.put('/api/referral_reasons/'+this.form.id)
			.then(() => {
				// success
				Fire.$emit('referralReasonsChanged');
				$('#referralReasonModal').modal('hide')
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

		deleteReferralReason(id){
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
					this.form.delete('/api/referral_reasons/'+id)
					.then(() => {
						// success
						Fire.$emit('referralReasonsChanged');
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
		

		this.getReferralReasons();
		
		Fire.$on('referralReasonsChanged', () => {
			this.getReferralReasons();
		});

		
	}
}
</script>