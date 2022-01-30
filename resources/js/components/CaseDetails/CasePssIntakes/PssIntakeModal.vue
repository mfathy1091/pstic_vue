<template>
    <div class="modal fade" id="pssIntakeModal" tabindex="-1" aria-labelledby="pssIntakeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="!pssIntakeEditMode" class="modal-title" id="pssIntakeModalLabel">Create New PSS Intake</h5>
                    <h5 v-show="pssIntakeEditMode" class="modal-title" id="pssIntakeModalLabel">Edit PSS Intake</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="pssIntakeEditMode ? updatePssIntake() : createPssIntake()">
                    <div class="modal-body">
						<div class="form-group">
							<label class="typo__label">Choose Direct Beneficiaries</label>
							<div>
								<multiselect 
								v-model="pssIntakeForm.direct_referral_beneficiaries" 
								:options="CaseeActiveBeneficiaries" 
								:multiple="true" 
								:close-on-select="false" 
								:clear-on-select="false" 
								:preserve-search="true" 
								placeholder="Pick some" 
								label="name" 
								track-by="name" 
								:preselect-first="true">
									<!-- <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template> -->
								</multiselect>
								<!-- <pre class="language-json"><code>{{ value  }}</code></pre> -->
							</div>
						</div>
						
						<div class="form-group">
							<label for="referral_source_id" class="form-label">Referral Source</label>
							<select name="referral_source_id" v-model="pssIntakeForm.referral_source_id" id="referral_source_id" class="form-control" :class="{ 'is-invalid': pssIntakeForm.errors.has('referral_source_id') }">
								<option value='0' disabled>Choose...</option>
								<option v-for='referralSource in referralSources' :value='referralSource.id' :key="referralSource.id">{{ referralSource.name }}</option>
							</select>
							<HasError :form="pssIntakeForm" field="referral_source_id" />
						</div>

						<div class="form-group">
							<label for="referral_date" class="form-label">Intake / Referral Date</label>
							<input id="referral_date" v-model="pssIntakeForm.referral_date" type="text" name="referral_date" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD">
							<HasError :form="pssIntakeForm" field="referral_date" />
						</div>

						<div class="form-group">
							<label for="referring_person_name" class="form-label">Referring Person Name</label>
							<input id="referring_person_name" v-model="pssIntakeForm.referring_person_name" type="text" name="referring_person_name" class="form-control">
							<HasError :form="pssIntakeForm" field="referring_person_name" />
						</div>

						<div class="form-group">
							<label for="referring_person_email" class="form-label">Referring Person Email</label>
							<input id="referring_person_email" v-model="pssIntakeForm.referring_person_email" type="email" name="referring_person_email" class="form-control">
						<HasError :form="pssIntakeForm" field="referring_person_email" />
						</div>

						<div class="form-group">
							<label class="typo__label">Referral Reasons</label>
							<multiselect 
							v-model="pssIntakeForm.referral_reasons" 
							:options="referralReasons" 
							:multiple="true" 
							:close-on-select="false" 
							:clear-on-select="false" 
							:preserve-search="true" 
							placeholder="Pick some" 
							label="name" 
							track-by="name" 
							:preselect-first="true">
								<!-- <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template> -->
							</multiselect>
							<!-- <pre class="language-json"><code>{{ value  }}</code></pre> -->
						</div>

						<div class="form-group">
							<label for="referral_narrative_reason" class="form-label">Referral Narrative Reason</label>
							<textarea class="form-control" rows="3" v-model="pssIntakeForm.referral_narrative_reason" name="referral_narrative_reason"></textarea>
							<HasError :form="pssIntakeForm" field="referral_narrative_reason" />
						</div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-show="!pssIntakeEditMode" type="submit" class="btn btn-success">Create</button>
                        <button v-show="pssIntakeEditMode" type="submit" class="btn btn-primary">Update</button>
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
        pssIntakeEditMode: Boolean,
        selectedPssIntake: Object,
    },

	data() {
		return {
			// editMode: false,
            //  selected: this.selectedPssIntake;,
            CaseeActiveBeneficiaries: [],
			directIndividual: '',

			referralSources: [],
            nationalities: [],
			referralReasons: [],


			users: [],
			roles: [],
			pssIntakeForm : new Form({
				id: '',
				// referral_beneficiaries: [],
				// direct_beneficiaries : [],
				referral_source_id: '',
				referral_date: '',
				referring_person_name: '',
				referring_person_email: '',
				referral_narrative_reason: '',
				referral_reasons: [],
				casee_id: "",
				direct_referral_beneficiaries: [],
            }),
		}
	},
    watch: {
        selectedPssIntake (next, prev){
            this.pssIntakeForm.fill(this.selectedPssIntake);
			this.pssIntakeForm.casee_id = this.$route.params.caseeId;
        }
    },

	methods: {

		createPssIntake() {
			this.$Progress.start();
			this.pssIntakeForm.post('/api/referrals')
			.then((res) => {
				// success
				$('#pssIntakeModal').modal('hide')
				Fire.$emit('caseePssIntakesChanged');
				
				this.createdPssIntake = res.data.referral
				
				Toast.fire({
					icon: 'success',
					title: 'Created successfully'
				})
				
				this.$Progress.finish();

				// router.push({ path: '/referrals/'+this.createdPssIntake.id })
			})
			.catch((e) => {
				this.$Progress.fail();
				console.log(e)
			})
			
		},

		updatePssIntake(){
			this.$Progress.start();
			this.pssIntakeForm.put('/api/referrals/'+this.pssIntakeForm.id)
			.then(() => {
				Fire.$emit('caseePssIntakesChanged');
				$('#pssIntakeModal').modal('hide')
				Swal.fire(
					'Updated!',
					'It has been updated.',
					'success'
				)
				this.$Progress.finish();
			})
			.catch(() => {
				this.$Progress.fail();
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