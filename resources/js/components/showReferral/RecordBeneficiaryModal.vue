<template>
<!-- Modal -->
		<div class="modal fade" id="recordBeneficiaryModal" tabindex="-1" aria-labelledby="recordBeneficiaryModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="recordBeneficiaryModalLabel">Edit Record Beneficiary</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="updateRecordBeneficiary()">
						<div class="modal-body">
							<!-- <div class="form-group">
								<label for="name" class="form-label">Name</label>
								<input id="name" v-model="recordBeneficiaryForm.name" type="text" name="name" class="form-control">
								<HasError :form="recordBeneficiaryForm" field="name" />
							</div> -->

							<span v-if="selectedRecordBeneficiary.individual">{{ this.selectedRecordBeneficiary.individual.name }}</span>
							<select name="recordBeneficiaryStatuses"  v-model="recordBeneficiaryForm.status" class="form-control">
								<option :value='0'>Exclude</option>
								<option :value="1">Direct</option>
								<option :value='2'>Indirect</option>
							</select>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Update</button>
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
        selectedRecordBeneficiary: Object,
    },
	data() {
		return {
			recordBeneficiaryForm: new Form({
				id: '',
				individual_id: '',
				record_id: '',
				status: '',
			})
		}
	},
    watch: {
        selectedRecordBeneficiary (next, prev){
            this.recordBeneficiaryForm.fill(this.selectedRecordBeneficiary)
        }
    },

	methods: {
		updateRecordBeneficiary(){
            
			this.$Progress.start();
			this.recordBeneficiaryForm.put('/api/record-beneficiaries/'+this.recordBeneficiaryForm.id)
			.then(() => {
				// success
				Fire.$emit('recordBeneficiariesChanged');
				$('#recordBeneficiaryModal').modal('hide')
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