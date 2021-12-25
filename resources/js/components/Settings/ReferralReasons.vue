<template>
	<div>
		<div class="card-body">
			<div class="form-inline ml-2">
				<button class="btn btn-success btn-sm mr-2" @click="showCreateReferralReasonModal">
					<i class="fas fa-plus-circle"></i><span><b> Referral Reasons</b></span>
				</button>

				<button class="btn btn-secondary btn-sm mr-5" @click="getReferralReasons">
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
					<tbody v-if="referralReasons">
						<tr v-for="referralReason in referralReasons" :key="referralReason.id">
							<td>{{ referralReason.name }}</td>
							<td>
								<span class="clickable ml-2" @click="showEditReferralReasonModal(referralReason)">
									<i class="fas fa-pencil-alt blue"></i>
								</span>
								
								<span class="clickable ml-2" @click="deleteReferralReason(referralReason.id)">
									<i class="fa fa-trash red"></i>
								</span>
							</td>
						</tr>
					</tbody>
				</table>
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
import axiosMixin from '../../mixins/axiosMixin'

export default {
	mixins: [axiosMixin],
	data() {
		return {
			editMode: false,
			referralReasons: [],
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