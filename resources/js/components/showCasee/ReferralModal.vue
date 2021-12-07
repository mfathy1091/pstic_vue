<template>
    <div class="modal fade" id="referralModal" tabindex="-1" aria-labelledby="referralModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="!referralEditMode" class="modal-title" id="referralModalLabel">Create New Referral</h5>
                    <h5 v-show="referralEditMode" class="modal-title" id="referralModalLabel">Edit Referral</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="referralEditMode ? updateReferral() : createReferral()">
                    <div class="modal-body">
						<div class="form-group">
							<label for="referral_source_id" class="form-label">Referral Source</label>
							<select name="referral_source_id" v-model="referralForm.referral_source_id" id="referral_source_id" class="form-control" :class="{ 'is-invalid': referralForm.errors.has('referral_source_id') }">
								<option value='0' disabled>Choose...</option>
								<option v-for='referralSource in referralSources' :value='referralSource.id' :key="referralSource.id">{{ referralSource.name }}</option>
							</select>
							<HasError :form="referralForm" field="referral_source_id" />
						</div>

						<div class="form-group">
							<label for="referral_date" class="form-label">Referral Date</label>
							<input id="referral_date" v-model="referralForm.referral_date" type="text" name="referral_date" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD">
							<HasError :form="referralForm" field="referral_date" />
						</div>

						<div class="form-group">
							<label for="referring_person_name" class="form-label">Referring Person Name</label>
							<input id="referring_person_name" v-model="referralForm.referring_person_name" type="text" name="referring_person_name" class="form-control">
							<HasError :form="referralForm" field="referring_person_name" />
						</div>

						<div class="form-group">
							<label for="referring_person_email" class="form-label">Referring Person Email</label>
							<input id="referring_person_email" v-model="referralForm.referring_person_email" type="email" name="referring_person_email" class="form-control">
						<HasError :form="referralForm" field="referring_person_email" />
						</div>

						<div class="form-group">
							<label for="reasons_ids" class="form-label">Referral Reason</label>
							<select multiple name="reasons_ids" v-model="referralForm.reasons_ids" id="reasons_ids" class="form-control" :class="{ 'is-invalid': referralForm.errors.has('reasons_ids') }">
								<option v-for='reason in reasons' :value='reason.id' :key="reason.id">{{ reason.name }}</option>
							</select>
							<HasError :form="referralForm" field="reasons_ids" />
						</div>

						<div class="form-group">
							<label for="referral_narrative_reason" class="form-label">Referral Narrative Reason</label>
							<textarea class="form-control" rows="3" v-model="referralForm.referral_narrative_reason" name="referral_narrative_reason"></textarea>
							<HasError :form="referralForm" field="referral_narrative_reason" />
						</div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-show="!referralEditMode" type="submit" class="btn btn-success">Create</button>
                        <button v-show="referralEditMode" type="submit" class="btn btn-primary">Update</button>
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
        referralEditMode: Boolean,
        selectedUser: Object,
		caseeId: {
            type: Number, 
            required: true
        }
    },

	data() {
		return {
			// editMode: false,
            //  selected: this.selectedUser;,
			casee: '',
            caseebeneficiaries: [],
			directIndividual: '',

			referralSources: [],
            nationalities: [],
			reasons: [],


			users: {},
			roles: [],
			referralForm : new Form({
				id: '',
				// referral_beneficiaries: [],
				// direct_beneficiaries : [],
				referral_source_id: '',
				referral_date: '',
				referring_person_name: '',
				referring_person_email: '',
				referral_narrative_reason: '',
				reasons_ids: [],
				casee_id: 1,
            }),
		}
	},
    watch: {
        selectedUser (next, prev){
            this.referralForm.fill(this.selectedUser)
        }
    },

	methods: {

		createReferral() {
			this.$Progress.start();
			this.referralForm.post('/api/referrals')
			.then((res) => {
				// success
				$('#referralModal').modal('hide')
				Fire.$emit('caseeReferralsChanged');
				
				this.createdReferral = res.data.referral
				
				Toast.fire({
					icon: 'success',
					title: 'Added successfully'
				})
				
				this.$Progress.finish();

				// router.push({ path: '/referrals/'+this.createdReferral.id })
			})
			.catch((e) => {
				this.$Progress.fail();
				console.log(e)
			})
			
		},

		updateReferral(){
			this.$Progress.start();
			this.referralForm.put('/api/user/'+this.referralForm.id)
			.then(() => {
				// success
				Fire.$emit('usersChanged');
				$('#referralModal').modal('hide')
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
		this.getCaseebeneficiaries(this.caseeId)
		this.getReferralSources()
		this.getNationalities()
		this.getReasons()
	}
}
</script>