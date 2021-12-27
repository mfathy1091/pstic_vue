<template>
<!-- Modal -->
		<div class="modal fade" id="caseeModal" tabindex="-1" aria-labelledby="caseeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 v-show="!caseeEditMode" class="modal-title" id="caseeModalLabel">Create New Activity</h5>
						<h5 v-show="caseeEditMode" class="modal-title" id="caseeModalLabel">Edit Activity</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="caseeEditMode ? updateCase() : createCase()">
						<div class="modal-body">

							<ValidationProvider name="File Number" rules="required|length:12" v-slot="{ errors }">
								<div class="form-group">
									<input v-model="caseForm.file_number" type="search" :placeholder="mask" class="form-control form-control-sidebar" aria-label="Search">
									<div class="input-group-append">
									</div>
								</div>
								<span class="text-danger">{{ errors[0] }}</span>
							</ValidationProvider>


							<div class="form-group form-check">
								<input v-model="caseForm.is_family" type="checkbox" class="form-check-input" id="is_family">
								<label class="form-check-label" for="is_active">Is Family?</label>
							</div>

							<div class="form-group" v-show="false">
								<label for="created_user_id" class="form-label">Worker Name</label>
								<input id="created_user_id" type="text" name="created_user_id" class="form-control" autocomplete="off" :value="currentUser.full_name" disabled>
								<!-- <HasError :form="caseForm" field="created_user_id" /> -->
							</div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							<button v-show="!caseeEditMode" type="submit" class="btn btn-success">Create</button>
							<button v-show="caseeEditMode" type="submit" class="btn btn-primary">Update</button>
						</div>
					</form>
				</div>
			</div>
		</div>
</template>

<script>
import Form from 'vform'
import axiosMixin from '../../mixins/axiosMixin'
import Multiselect from 'vue-multiselect'

export default {
	mixins: [axiosMixin],
	components: { Multiselect },
    props:{
		// recordId: {
        //     type: Number,
        //     required: true
        // },
        caseeEditMode: {
            type: Boolean,
            required: true
        },
		selectedCasee: {
            // type: Object,
            required: true
        },
    },
	data() {
		return {
			format: '',
            regex: '^',
            mask: 'XXX-XXCXXXXX',
			caseForm: new Form({
				id: '',
				file_number: '',
				is_family: '',
				created_user_id: '',
			})
		}
	},

	watch: {
        selectedCasee (next, prev){
            if(this.caseeEditMode){ // edit
                this.caseForm.fill(this.selectedCasee)
				
				// this.caseForm
				// $this.caseForm.provided_services.forEach(myFunction);

				// function myFunction(item) {
				// 	sum += item;
				// }
			}
			else
			{ // create
                this.resetCaseForm();
            }
            
        }
    },

	methods: {
		resetCaseForm() { // for create
			this.caseForm.id = ''
			this.caseForm.file_number = ''
			this.caseForm.is_family = false
			this.caseForm.created_user_id = ''

        },

        createCase() {
			this.$Progress.start();
			this.caseForm.post('/api/casees')
			.then((response) => {
				// success
				$('#caseeModal').modal('hide')
				Fire.$emit('caseesChanged');
								
				Toast.fire({
					icon: 'success',
					title: 'Added successfully'
				})
				this.$Progress.finish();
			})
			.catch((e) => {
				this.$Progress.fail();
                Toast.fire({
                    icon: 'error',
                    title: e
                })
			})
		},

		updateCase(){
			this.$Progress.start();
			this.caseForm.put('/api/casees/'+this.caseForm.id)
			.then(() => {
				// success
				Fire.$emit('caseesChanged');
				$('#caseeModal').modal('hide')
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

    created (){

		
    },
    computed:{
        currentUser: {
            get(){
                return this.$store.state.currentUser.user;
            }
        }
    },
}
</script>