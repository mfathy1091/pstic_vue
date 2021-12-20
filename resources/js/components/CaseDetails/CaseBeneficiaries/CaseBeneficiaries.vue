<style scoped>
.clickable {
    cursor: pointer
}
</style>
<template>
    <div>
        <div class="card-body">
            <div class="row ml-2">
				<button class="btn btn-success btn-sm mr-2" @click="showCreateBeneficiaryModal">
					<i class="fas fa-plus-circle"></i><span><b> Beneficiary</b></span>
				</button>

                <button class="btn btn-secondary btn-sm" @click="getCaseeBeneficiaries(caseeId)">
					<i class="fas fa-sync-alt"></i>
				</button>
			</div>


            <div class="row mt-3">
					<p v-if="!caseeBeneficiaries.length" class="font-italic ml-5">This case has no linked beneficiaries!</p>

                <!-- beneficiaries -->
                <div v-for="beneficiary in caseeBeneficiaries" :key="beneficiary.id">

                    <div class="card individual-card" style="width: 18rem;">
                        <div class="overlay bg-white-50" v-show="beneficiary.is_active == 0">
                            <div class="p-4 text-center">
                                <h3 class="bg-white text-secondary">Inactive</h3>
                                <a class="clickable text-primary p-2 bg-white" @click="activateBeneficiary(beneficiary)">Click here re-activate</a>
                            </div>
                        </div>
                        <div class="card-body">

                            <h4 class="card-title">{{ beneficiary.name }}</h4>
                                
                            <div class="float-right">
                                <a class="clickable" @click="showEditBeneficiaryModal(beneficiary)">
                                    <i class="fas fa-pencil-alt blue mr-2"></i>
                                </a>
                                
                                <a class="clickable">
                                    <i class="fa fa-trash red" @click="deleteBeneficiary(beneficiary)"></i>
                                </a>

                            </div>
                            


                            <br>
                            <br>

                            <div class="mb-0 fa-ul">
                                <li><span class="text-sm"></span>{{ beneficiary.age }} years; {{ beneficiary.gender.name }}; {{ beneficiary.nationality.name }}</li>
                                <li><span class="text-sm"></span><i class="fas fa-phone-square-alt"></i> {{ beneficiary.phone_number}}</li>
                                <li><span class="text-sm"></span><b>Passport #:</b> {{ beneficiary.passport_number }}</li>

                            </div>
                            <hr>
                            <span class="text-sm"><b>File Details</b></span>

                            <ul class="mb-0 fa-ul">
                                <li><span class="fa-li"></span><b>File Individual number:</b> {{ beneficiary.file_individual_number }}</li>
                                <li><span class="fa-li"></span><b>Relationship:</b> {{ beneficiary.relationship.name}}</li>
                            </ul>
                            <a class="clickable orange" @click="deactivateBeneficiary(beneficiary)">Deactivate</a>

                        </div>
                        
                    </div>
                    
                </div>

            </div>
        </div>

		<BeneficiaryModal
		:v-if="selectedBeneficiary.id"
		:beneficiaryEditMode='beneficiaryEditMode' 
		:selectedBeneficiary='selectedBeneficiary' 
		v-on:caseeBeneficiariesChanged="getCaseeBeneficiaries(caseeId)">
		</BeneficiaryModal>

    </div>
</template>

<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import BeneficiaryModal from './BeneficiaryModal'
import axiosMixin from '../../../mixins/axiosMixin'

export default {
    name: 'Caseebeneficiaries',
    mixins: [axiosMixin], 

    components: {
        Multiselect,
        BeneficiaryModal,
    },

    props: {
        caseeId: {
            // type: Number, 
            required: true   
        }
    },

    data(){
        return {
            caseeBeneficiaries: [],
            beneficiaryEditMode: false,
			selectedBeneficiary: {},
        }
    },
    methods: {

        unlinkIndividual(beneficiary_id){
            this.$Progress.start();
			axios.put('/api/beneficiaries/' + beneficiary_id + '/unlink' )
			.then(() => {
				// success
				Fire.$emit('caseeBeneficiariesChanged');
				// $('#individualModal').modal('hide')
				Swal.fire(
					'Unlinked!',
					'Unlinked successfully.',
					'success'
				)
				this.$Progress.finish();
			})
			.catch(() => {
				// error
				this.$Progress.fail();
			})
        },

        showCreateBeneficiaryModal(){
			this.beneficiaryEditMode = false;
			this.selectedBeneficiary = {};
			$('#beneficiaryModal').modal('show')
		},

        showEditBeneficiaryModal(beneficiary){
			this.beneficiaryEditMode = true;
			this.selectedBeneficiary = beneficiary;
			$('#beneficiaryModal').modal('show')
		},

        activateBeneficiary(beneficiary)
            {
                Swal.fire({
				title: 'Are you sure?',
				// text: "You won't be able to include this beneficiary in new referrals!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, re-activate it!'
			})
			.then((result) => {
				if (result.isConfirmed) {
					this.$Progress.start();
					axios.put('/api/beneficiaries/'+beneficiary.id + '/activate')
					.then(() => {
						// success
						Fire.$emit('caseeBeneficiariesChanged');
						Swal.fire(
							'Activated!',
							'It has been activated.',
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

        deactivateBeneficiary(beneficiary)
            {
                Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to include this beneficiary in new referrals!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, deactivate it!'
			})
			.then((result) => {
				if (result.isConfirmed) {
					this.$Progress.start();
					axios.put('/api/beneficiaries/'+beneficiary.id + '/deactivate')
					.then(() => {
						// success
						Fire.$emit('caseeBeneficiariesChanged');
						Swal.fire(
							'Deactivated!',
							'It has been deactivated.',
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

        deleteBeneficiary(beneficiary){
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
					axios.delete('/api/beneficiaries/'+beneficiary.id)
					.then(() => {
						// success
						Fire.$emit('caseeBeneficiariesChanged');
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
        this.getCaseeBeneficiaries(this.caseeId);

        Fire.$on('caseeBeneficiariesChanged', () => {
            this.getCaseeBeneficiaries(this.caseeId);
        });
    }
}
</script>