<template>
    <div class="modal fade" id="selectedBeneficiariesModal" tabindex="-1" aria-labelledby="selectedBeneficiariesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectedBeneficiariesModalLabel">Add Beneficairy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="setSelectedBeneficiaries()">
                    <div class="modal-body">

						<div class="form-group">
							<label for="file_number" class="form-label">File Number</label>
							<input id="file_number" v-model="beneficiaryForm.file_number" type="text" name="file_number" class="form-control">
							<HasError :form="beneficiaryForm" field="file_number" />
						</div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-show="!psIntakeEditMode" type="submit" class="btn btn-success">Create</button>
                        <button v-show="psIntakeEditMode" type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
		
    </div>
</template>
<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import axiosMixin from '../../mixins/axiosMixin'

export default {
	mixins: [axiosMixin],
	components: { Multiselect },
    props:{
        psIntakeEditMode: Boolean,
        selectedPsIntake: Object,
    },

	data() {
		return {
			// editMode: false,
            //  selected: this.selectedPsIntake;,
            CaseeActiveBeneficiaries: [],
			directIndividual: '',

			referralSources: [],
            nationalities: [],
			referralReasons: [],


			users: [],
			roles: [],
			beneficiaryForm : new Form({
				id: '',
				// referral_beneficiaries: [],
				// direct_beneficiaries : [],
				referral_source_id: '',
				referral_date: '',
				name: '',
				referring_person_email: '',
				referral_narrative_reason: '',
				referral_reasons: [],
				casee_id: "",
				direct_referral_beneficiaries: [],
            }),
		}
	},
    watch: {
        selectedPsIntake (next, prev){
            this.beneficiaryForm.fill(this.selectedPsIntake);
			this.beneficiaryForm.casee_id = this.$route.params.caseeId;
        }
    },

	methods: {

		setSelectedBeneficiaries() {
			this.$Progress.start();
			this.beneficiaryForm.post('/api/referrals')
			.then((res) => {
				// success
				$('#selectedBeneficiariesModal').modal('hide')
				Fire.$emit('psIntakesChanged');
				
				this.createdIntake = res.data.referral
				
				Toast.fire({
					icon: 'success',
					title: 'Created successfully'
				})
				
				this.$Progress.finish();

				// router.push({ path: '/referrals/'+this.createdIntake.id })
			})
			.catch((e) => {
				this.$Progress.fail();
				console.log(e)
			})
			
		},

	},

	created() {
		this.getReferralSources()
		this.getNationalities()
		this.getReferralReasons()
		this.getCaseeActiveBeneficiaries(this.$route.params.caseeId);
	}
}
</script>