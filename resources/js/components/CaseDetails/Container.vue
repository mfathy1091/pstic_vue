<style scoped>
.clickable {
    cursor: pointer
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>  
        <!-- Referral Details Section -->
        <div class="card mt-3" v-if="this.casee">
            <div class="card-header">
                <h5 class="m-0">
                    File Details: {{ this.casee.file_number }}
                <span @click="showEditCaseeModal"
                id='clickableAwesomeFont' class="ml-5">
                    <i class="fa fa-edit blue"></i>
                </span>
                </h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col mb-4">
                        <h6 class="card-subtitle mb-2 text-muted">File details</h6>
                        <div class="ml-4">
                            <li>File Number: {{ this.casee.file_number }}</li>
                            <li>File Owner: </li>
                            <li>UNHCR Card: </li>
                        </div>
                    </div>
                    <div class="col mb-4" >
                        <h6 class="card-subtitle mb-2 text-muted">Linked beneficiaries</h6>
                        <!-- <div class="ml-4" v-for="reason in this.referral.reasons" :key="reason.id">
                            <li>{{ reason.name  }}</li>
                        </div> -->
                    </div> 
                </div>
            </div>
        </div>
        <!-- Case Modal -->
		<div class="modal fade" id="caseeModal" tabindex="-1" aria-labelledby="caseeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 v-show="!caseeEditMode" class="modal-title" id="caseeModalLabel">Create New Case</h5>
						<h5 v-show="caseeEditMode" class="modal-title" id="caseeModalLabel">Edit Casee</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
                    
                    <!-- Casee fileNumber Field -->
                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form @submit.prevent="caseeEditMode ? handleSubmit(updateCasee) : handleSubmit(createCasee)">
                            <div class="modal-body">
                                <ValidationProvider name="Casee fileNumber" rules="required|length:12" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="fileNumber" class="form-label">Enter Casee fileNumber</label>
                                    <input v-model="caseeForm.fileNumber" type="text" :placeholder="mask" class="form-control">
                                    <span class="text-danger">{{ errors[0] }}</span>
                                </div>
                                </ValidationProvider>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button v-show="!caseeEditMode" type="submit" class="btn btn-success">Create</button>
                                <button v-show="caseeEditMode" type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </ValidationObserver>

                </div>  
			</div>
		</div>
        <!-- Linked beneficiaries Section -->
        <Caseebeneficiaries v-if="casee" :casee_id="casee.id" />

        <CaseeReferrals v-if="casee" :caseeId="casee.id" />

        
        <!-- Referral Modal -->
		<!-- <ReferralModal 
		:v-if="selectedReferral.id"
		:editMode='editMode' 
		:selectedReferral='selectedReferral' 
		v-on:referralsChanged="getCasee()">
		</ReferralModal> -->

    </div>
</template>

<script>
import Form from 'vform'
import Caseebeneficiaries from './CaseBeneficiaries.vue'
import CaseeReferrals from './CaseReferrals.vue'
import Multiselect from 'vue-multiselect'
import ReferralModal from './ReferralModal'
import axiosMixin from '../../mixins/axiosMixin'	

export default {
	mixins: [axiosMixin],
    name: 'ShowCasee',
    components: {
        Caseebeneficiaries,
        CaseeReferrals,
        Multiselect,
        ReferralModal,
    },

    props: {
        caseeId: {
            type: String, 
            required: true
        }
    },
    data() {
        
        return{
            referralEditMode: false,
			selectedReferral: {},
			// referrals: {},


            casee: '',
            // caseeId: this.$route.params.caseeId,

            caseeEditMode: false,
            
            linkedbeneficiaries: [],

            caseeForm: new Form({
                id: '',
                fileNumber: '',
                linked_beneficiaries: [],
            }),
            format: '',
            regex: '^',
            mask: 'XXX-XXCXXXXX',
        }
    },
    methods: {

        showEditCaseeModal(){
			this.caseeEditMode = true;
			this.caseeForm.reset()
			$('#caseeModal').modal('show')
			this.caseeForm.fill(this.casee)
		},
		updateCasee(){
			this.$Progress.start();
			this.caseeForm.put('/api/casees/'+this.caseeForm.id)
			.then(() => {
				// success
				Fire.$emit('caseeChanged');
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
        this.getCasee(this.caseeId)

        Fire.$on('caseeChanged', () => {
			this.getCasee();
		});
    },
    mounted() {
        let x = 1;
        this.format = this.mask.replace(/X+/g, () => '$' + x++)
        this.mask.match(/X+/g).forEach((char, key) => {
            // console.log(char.length);
            // console.log(key);

            this.regex += '(\\d{' + char.length + '})?'
            console.log(this.regex);
        });

        this.$store.dispatch('getNationalities')
    },

    watch: {
        'caseeForm.fileNumber'(next, prev) {
            if (next.length > prev.length) {
                this.caseeForm.fileNumber = this.caseeForm.fileNumber.replace(/[^0-9]/g, '')
            
            .replace(new RegExp(this.regex, 'g'), this.format)
            .substr(0, this.mask.length);
            }
        },
    },
}
</script>