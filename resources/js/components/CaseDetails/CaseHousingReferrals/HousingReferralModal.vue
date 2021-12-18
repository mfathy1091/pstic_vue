<template>
    <div class="modal fade" id="housingReferralModal" tabindex="-1" aria-labelledby="housingReferralModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="!housingReferralEditMode" class="modal-title" id="housingReferralModalLabel">Create New Housing Referral</h5>
                    <h5 v-show="housingReferralEditMode" class="modal-title" id="housingReferralModalLabel">Edit Housing Referral</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="housingReferralEditMode ? updateHousingReferral() : createHousingReferral()">
                    <div class="modal-body">
						<div class="form-group">
							<label for="referral_source_id" class="form-label">Referral Source</label>
							<select name="referral_source_id" v-model="housingReferralForm.referral_source_id" id="referral_source_id" class="form-control" :class="{ 'is-invalid': housingReferralForm.errors.has('referral_source_id') }">
								<option value='0' disabled>Choose...</option>
								<option v-for='referralSource in referralSources' :value='referralSource.id' :key="referralSource.id">{{ referralSource.name }}</option>
							</select>
							<HasError :form="housingReferralForm" field="referral_source_id" />
						</div>

						<div class="form-group">
							<label for="referral_date" class="form-label">Referral Date</label>
							<input id="referral_date" v-model="housingReferralForm.referral_date" type="text" name="referral_date" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD">
							<HasError :form="housingReferralForm" field="referral_date" />
						</div>

						<div class="form-group">
							<label for="referring_person_name" class="form-label">Referring Person Name</label>
							<input id="referring_person_name" v-model="housingReferralForm.referring_person_name" type="text" name="referring_person_name" class="form-control">
							<HasError :form="housingReferralForm" field="referring_person_name" />
						</div>

						<div class="form-group">
							<label for="referring_person_email" class="form-label">Referring Person Email</label>
							<input id="referring_person_email" v-model="housingReferralForm.referring_person_email" type="email" name="referring_person_email" class="form-control">
						<HasError :form="housingReferralForm" field="referring_person_email" />
						</div>


						<div class="form-group">
							<label for="referral_narrative_reason" class="form-label">Referral Narrative Reason</label>
							<textarea class="form-control" rows="3" v-model="housingReferralForm.referral_narrative_reason" name="referral_narrative_reason"></textarea>
							<HasError :form="housingReferralForm" field="referral_narrative_reason" />
						</div>

						<div class="form-group">
							<label for="referral_source_id" class="form-label">Grant Status</label>
							<select name="referral_source_id" v-model="housingReferralForm.grant_status_id" id="grant_status_id" class="form-control" :class="{ 'is-invalid': housingReferralForm.errors.has('grant_status_id') }">
								<option value='0' disabled>Choose...</option>
								<option v-for='grantStatus in housingGrantStatuses' :value='grantStatus.id' :key="grantStatus.id">{{ grantStatus.name }}</option>
							</select>
							<HasError :form="housingReferralForm" field="referral_source_id" />
						</div>

						<div class="form-group">
							<label for="grant_amount" class="form-label">Grant Amount</label>
							<input id="grant_amount" v-model="housingReferralForm.grant_amount" type="number" name="grant_amount" class="form-control">
							<HasError :form="housingReferralForm" field="grant_amount" />
						</div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-show="!housingReferralEditMode" type="submit" class="btn btn-success">Create</button>
                        <button v-show="housingReferralEditMode" type="submit" class="btn btn-primary">Update</button>
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
        housingReferralEditMode: Boolean,
        selectedHousingReferral: Object,
		caseeId: {
            // type: Number, 
            required: true
        }
    },

	data() {
		return {
			casee: '',
			referralSources: [],
			housingGrantStatuses: [],
			housingReferralForm : new Form({
				id: '',
				casee_id: "",
				referral_source_id: '',
				referral_date: '',
				referring_person_name: '',
				referring_person_email: '',
				referral_narrative_reason: '',
				grant_status_id: '',
				grant_amount: '',
            }),
		}
	},
    watch: {
        selectedHousingReferral (next, prev){
            this.housingReferralForm.fill(this.selectedHousingReferral);
			this.housingReferralForm.casee_id = this.$route.params.caseeId;
        }
    },

	methods: {

		createHousingReferral() {
			this.$Progress.start();
			this.housingReferralForm.post('/api/housing-referrals')
			.then((res) => {
				// success
				$('#housingReferralModal').modal('hide')
				Fire.$emit('caseeHousingReferralsChanged');
								
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

		updateHousingReferral(){
			this.$Progress.start();
			this.housingReferralForm.put('/api/housing-referrals/'+this.housingReferralForm.id)
			.then(() => {
				Fire.$emit('caseeHousingReferralsChanged');
				$('#housingReferralModal').modal('hide')
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
		this.getCasee(this.caseeId)
		this.getReferralSources()
		this.getNationalities()
		this.getReferralReasons()
		this.getHousingGrantStatuses()
	}
}
</script>