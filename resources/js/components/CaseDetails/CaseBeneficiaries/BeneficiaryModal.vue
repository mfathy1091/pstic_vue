<template>
    <div class="modal fade" id="beneficiaryModal" tabindex="-1" aria-labelledby="beneficiaryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="!beneficiaryEditMode" class="modal-title" id="beneficiaryModalLabel">Add New Beneficiary</h5>
                    <h5 v-show="beneficiaryEditMode" class="modal-title" id="beneficiaryModalLabel">Edit Beneficiary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="beneficiaryEditMode ? updateBeneficiary() : createBeneficiary()">
                    <div class="modal-body">
							
							<div class="form-group">
								<label for="name" class="form-label">Name</label>
								<input id="name" v-model="BeneficiaryForm.name" type="text" name="name" class="form-control">
								<HasError :form="BeneficiaryForm" field="name" />
							</div>

							<div class="form-group">
								<label for="passport_number" class="form-label">Passport Number</label>
								<input id="passport_number" v-model="BeneficiaryForm.passport_number" type="text" name="passport_number" class="form-control">
								<HasError :form="BeneficiaryForm" field="passport_number" />
							</div>

							<div class="form-group">
								<label for="age" class="form-label">Age</label>
								<input id="age" v-model="BeneficiaryForm.age" type="number" name="age" class="form-control" onwheel="return false;">
								<HasError :form="BeneficiaryForm" field="age" />
							</div>

							<div class="form-group">
								<label for="gender_id" class="form-label">Gender</label>
								<select name="gender_id" v-model="BeneficiaryForm.gender_id" id="gender_id" class="form-control" :class="{ 'is-invalid': BeneficiaryForm.errors.has('gender_id') }">
									<option value='0' disabled>Choose...</option>
									<option value="1">Male</option>
									<option value="2">Female</option>
								</select>
								<has-error :form="BeneficiaryForm" field="gender_id"></has-error>
							</div>

							<div class="form-group">
								<label for="nationality_id" class="form-label">Nationality</label>
								<select name="nationality_id" v-model="BeneficiaryForm.nationality_id" id="nationality_id" class="form-control" :class="{ 'is-invalid': BeneficiaryForm.errors.has('nationality_id') }">
									<option value='0' disabled>Choose...</option>
									<option v-for='nationaity in nationalities' :value='nationaity.id' :key="nationaity.id">{{ nationaity.name }}</option>
								</select>
								<has-error :form="BeneficiaryForm" field="nationality_id"></has-error>
							</div>

							<div class="form-group">
								<label for="phone_number" class="form-label">Phone Number</label>
								<input id="phone_number" v-model="BeneficiaryForm.phone_number" type="text" name="phone_number" class="form-control">
								<HasError :form="BeneficiaryForm" field="phone_number" />
							</div>

                            <hr class="col-8 mt-5 mb-5">

                            <div class="form-group">
								<label for="relationship_id" class="form-label">Relationship to PA</label>
								<select name="relationship_id" v-model="BeneficiaryForm.relationship_id" id="relationship_id" class="form-control" :class="{ 'is-invalid': BeneficiaryForm.errors.has('relationship_id') }">
									<option value='0' disabled>Choose...</option>
									<option v-for='relationship in relationships' :value='relationship.id' :key="relationship.id">{{ relationship.name }}</option>
								</select>
								<HasError :form="BeneficiaryForm" field="relationship_id" />
							</div>
							
							<div class="form-group">
								<label for="beneficiary_id" class="form-label">File Beneficiary ID</label>
								<input id="beneficiary_id" v-model="BeneficiaryForm.beneficiary_id" type="text" name="beneficiary_id" class="form-control">
								<HasError :form="BeneficiaryForm" field="beneficiary_id" />
							</div>

							<div class="form-group form-check">
								<input v-model="BeneficiaryForm.is_active" type="checkbox" class="form-check-input" id="is_active">
								<label class="form-check-label" for="is_active">Is Active?</label>
							</div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-show="!beneficiaryEditMode" type="submit" class="btn btn-success">Create</button>
                        <button v-show="beneficiaryEditMode" type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import axiosMixin from '../../../mixins/axiosMixin'	

export default {
	mixins: [axiosMixin],
	components: { Multiselect },
    props:{
        beneficiaryEditMode: Boolean,
        selectedBeneficiary: Object,
    },
	data() {
		return {
            genders: [],
            nationalities: [],
            beneficiaryStatuses: [],
            relationships: [],


			BeneficiaryForm: new Form({
				id: '',
                casee_id: '',
				name: '',
                passport_number: '',
				age: '',
                gender_id: '',
                nationality_id: '',
                phone_number: '',
				is_active: true,
				is_registered: '',
                file_individual_number: '',
                relationship_id: '',

			})
		}
	},
    watch: {
        selectedBeneficiary (next, prev){
            this.BeneficiaryForm.fill(this.selectedBeneficiary)
			this.BeneficiaryForm.casee_id = this.$route.params.caseeId;
        }
    },

	methods: {

		createBeneficiary() {
			this.$Progress.start();
			this.BeneficiaryForm.post('/api/beneficiaries')
			.then((res) => {
				// success
				$('#beneficiaryModal').modal('hide')
				Fire.$emit('caseeBeneficiariesChanged');
								
				Toast.fire({
					icon: 'success',
					title: 'Added successfully'
				})
				this.$Progress.finish();
			})
			.catch((e) => {
				this.$Progress.fail();
				console.log(e)
			})
			
		},

        async getAll(method, url, dataObj){
			try {
                return await axios({
                    method: method,
                    url: url,
                    data:dataObj,
                });
            } catch (e) {
                return e.response
            }	  
		},


		updateBeneficiary(){
            
			this.$Progress.start();
			this.BeneficiaryForm.put('/api/beneficiaries/'+this.BeneficiaryForm.id)
			.then(() => {
				// success
				Fire.$emit('caseeBeneficiariesChanged');
				$('#beneficiaryModal').modal('hide')
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
        this.getNationalities();
		this.getBeneficiaryStatuses();
		this.getRelationships();
	}
}
</script>