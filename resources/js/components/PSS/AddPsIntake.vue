<style scoped>

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>
        <div class="row mt-3 mb-3 pl-3">
            <h5>Add PS INTAKE</h5>
        </div>

        <div class="card">
            <form @submit.prevent="createPsIntake()">
                    <div class="modal-body">
                        
                        <label for="referral_source_id" class="form-label">Affected Beneficiaries</label>
                        <br>
						<div>
							
							<span v-for='beneficiary in selectedBeneficiaries' :value='beneficiary.id' :key="beneficiary.id">
								{{ beneficiary.name }} 
								<i class="fas fa-times clickable" @click="removeBeneficiary(beneficiary)"></i><br></span>
						</div>
                        
                        <a class="clickable" @click="showSelectedBeneficiariesModal">
                            add beneficiary
                        </a>


                        <br><hr>
						
						<div class="form-group">
							<label for="referral_source_id" class="form-label">Referral Source</label>
							<select name="referral_source_id" v-model="psIntakeForm.referral_source_id" id="referral_source_id" class="form-control" :class="{ 'is-invalid': psIntakeForm.errors.has('referral_source_id') }">
								<option value='0' disabled>Choose...</option>
								<option v-for='referralSource in referralSources' :value='referralSource.id' :key="referralSource.id">{{ referralSource.name }}</option>
							</select>
							<HasError :form="psIntakeForm" field="referral_source_id" />
						</div>

						<div class="form-group">
							<label for="referral_date" class="form-label">Intake / Referral Date</label>
							<input id="referral_date" v-model="psIntakeForm.referral_date" type="text" name="referral_date" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD">
							<HasError :form="psIntakeForm" field="referral_date" />
						</div>

						<div class="form-group">
							<label for="referring_person_name" class="form-label">Referring Person Name</label>
							<input id="referring_person_name" v-model="psIntakeForm.referring_person_name" type="text" name="referring_person_name" class="form-control">
							<HasError :form="psIntakeForm" field="referring_person_name" />
						</div>

						<div class="form-group">
							<label for="referring_person_email" class="form-label">Referring Person Email</label>
							<input id="referring_person_email" v-model="psIntakeForm.referring_person_email" type="email" name="referring_person_email" class="form-control">
						<HasError :form="psIntakeForm" field="referring_person_email" />
						</div>

						<div class="form-group">
							<label class="typo__label">Referral Reasons</label>
							<multiselect 
							v-model="psIntakeForm.referral_reasons" 
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
							<textarea class="form-control" rows="3" v-model="psIntakeForm.referral_narrative_reason" name="referral_narrative_reason"></textarea>
							<HasError :form="psIntakeForm" field="referral_narrative_reason" />
						</div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-show="!psIntakeEditMode" type="submit" class="btn btn-success">Create</button>
                        <button v-show="psIntakeEditMode" type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
        </div>
    
		<SelectedBeneficiariesModal>
		</SelectedBeneficiariesModal>

    </div>
</template>

<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import axiosMixin from '../../mixins/axiosMixin'
import SelectedBeneficiariesModal from './SelectedBeneficiariesModal'

export default {
	mixins: [axiosMixin],
	components: { Multiselect, SelectedBeneficiariesModal },
    props:{
        psIntakeEditMode: Boolean,
        selectedPsIntake: Object,
    },

	data() {
		return {
			// editMode: false,
            //  selected: this.selectedPsIntake;,
			selectedBeneficiaries: [],
            CaseeActiveBeneficiaries: [],
			directIndividual: '',

			referralSources: [],
            nationalities: [],
			referralReasons: [],
			selectedBeneficiary: '',
			beneficiaries: [],
			searchForm : new Form({
				beneficiary_name: '',
            }),

			users: [],
			roles: [],
			psIntakeForm : new Form({
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
        selectedPsIntake (next, prev){
            this.psIntakeForm.fill(this.selectedPsIntake);
			this.psIntakeForm.casee_id = this.$route.params.caseeId;
        }
    },

	methods: {
		addBeneficiary(beneficiary){
			this.selectedBeneficiaries.push(beneficiary)
			console.log(this.selectedBeneficiaries)
		},
		removeBeneficiary(beneficiary){
			this.selectedBeneficiaries = this.selectedBeneficiaries.filter((element) => element.id != beneficiary.id)
		},


        showSelectedBeneficiariesModal(){
			$('#selectedBeneficiariesModal').modal('show')
        },

		createPsIntake() {
			this.$Progress.start();
			this.psIntakeForm.post('/api/referrals')
			.then((res) => {
				// success
				$('#psIntakeModal').modal('hide')
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

		updatePsIntake(){
			this.$Progress.start();
			this.psIntakeForm.put('/api/referrals/'+this.psIntakeForm.id)
			.then(() => {
				Fire.$emit('psIntakesChanged');
				$('#psIntakeModal').modal('hide')
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

	Fire.$on('beneficiarySelected', (value) => {
			this.addBeneficiary(value);
		});
	}
}
</script>