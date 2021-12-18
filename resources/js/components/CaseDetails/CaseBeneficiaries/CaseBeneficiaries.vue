<style scoped>
.clickable {
    cursor: pointer
}
</style>
<template>
    <div>
        <div class="card-body bg-white pt-2 pl-2 p-0" v-if="casee">
            <div class="row m-3">
				<button class="btn btn-success btn-sm mr-2" @click="showCreateBeneficiaryModal">
					<i class="fas fa-plus-circle"></i><span><b> Beneficiary</b></span>
				</button>

                <button class="btn btn-secondary btn-sm" @click="getCaseeBeneficiaries(caseeId)">
					<i class="fas fa-sync-alt"></i>
				</button>
			</div>


            <div class="row m-0" v-if="caseeBeneficiaries.length">
                <p v-if="!caseeBeneficiaries.length" class="ml-5 text-primary">
                    <b>This case has no linked beneficiaries!</b>
                </p>

                <!-- beneficiaries -->
                <div v-for="beneficiary in caseeBeneficiaries" :key="beneficiary.id">

                    <div class="card individual-card" style="width: 18rem;">
                        <div class="card-body">
                            <h4 class="card-title">{{ beneficiary.name }}</h4>
                            <div class="float-right">
                                <a class="clickable" @click="showEditBeneficiaryModal(beneficiary)">
                                    <i class="fas fa-pencil-alt blue mr-2"></i>
                                </a>
                                
                                <a class="clickable">
                                    <i class="fa fa-trash red"></i>
                                </a>

                                <div class="dropdown">
                                    <button class="btn btn-tool" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <button class="dropdown-item" type="button">Edit</button>
                                        <button class="dropdown-item" type="button">
                                            <span class="text-red">Delete</span>
                                        </button>
                                        <div class="dropdown-divider"></div>
                                        <button class="dropdown-item" type="button">
                                            <span class="text-orange">Deativate</span>
                                        </button>
                                    </div>
                                </div>
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
                        </div>
                    </div>
                    
                </div>

            </div>
        </div>

		<BeneficiaryModal v-if="casee" :caseeId="casee.id"
		:v-if="selectedBeneficiary.id"
		:beneficiaryEditMode='beneficiaryEditMode' 
		:selectedBeneficiary='selectedBeneficiary' 
		v-on:caseebeneficiariesChanged="getCaseeBeneficiaries(caseeId)">
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
            casee: '',
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
				Fire.$emit('caseebeneficiariesChanged');
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
    },
    created() {
        this.getCasee(this.caseeId);
        this.getCaseeBeneficiaries(this.caseeId);

        Fire.$on('caseebeneficiariesChanged', () => {
            this.getCaseeBeneficiaries(this.caseeId);
        });
    }
}
</script>