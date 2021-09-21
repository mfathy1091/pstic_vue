<style scoped>
#clickableAwesomeFont {
    cursor: pointer
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>  
        <!-- Referral Details Section -->
        <div class="card mt-3" v-if="this.referral">
            <div class="card-header">
                <h5 class="m-0">
                    Referral Details
                <span @click="showEditReferralModal"
                id='clickableAwesomeFont' class="ml-5">
                    <i class="fa fa-edit blue"></i>
                </span>
                </h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col mb-4">
                        <h6 class="card-subtitle mb-2 text-muted">Referral Source</h6>
                        <div class="ml-4">
                            <li>{{ this.referral.referral_date }}</li>
                            <li>{{ this.referral.referral_source.name }}</li>
                            <li>{{ this.referral.referring_person_name }}</li>
                            <li>{{ this.referral.referring_person_email }}</li>
                        </div>
                    </div>
                    <div class="col mb-4" >
                        <h6 class="card-subtitle mb-2 text-muted">Reason of Referral</h6>
                        <div class="ml-4" v-for="reason in this.referral.reasons" :key="reason.id">
                            <li>{{ reason.name  }}</li>
                        </div>
                    </div>
                    <div class="col mb-4" >
                        <h6 class="card-subtitle mb-2 text-muted">Reason of Referral - Narrative</h6>
                        <div class="ml-4">
                            <li>{{ this.referral.referral_narrative_reason  }}</li>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Referral Modal -->
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
								<input id="referral_date" v-model="referralForm.referral_date" type="text" name="referral_date" class="form-control">
								<HasError :form="referralForm" field="referral_date" />
							</div>
                            
                            <div class="form-group">
								<label for="referring_person_name" class="form-label">Referring Person Name</label>
								<input id="referring_person_name" v-model="referralForm.referring_person_name" type="text" name="referring_person_name" class="form-control">
								<HasError :form="referralForm" field="referring_person_name" />
							</div>
                            
                            <div class="form-group">
								<label for="referring_person_email" class="form-label">Referral Date</label>
								<input id="referring_person_email" v-model="referralForm.referring_person_email" type="email" name="referring_person_email" class="form-control">
								<HasError :form="referralForm" field="referring_person_email" />
							</div>

                            <div class="form-group" v-if="reasons">
								<label class="typo__label">Referral Reasons</label>
								<multiselect 
								v-model="referralForm.reasons" 
								:options="reasons" 
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
        <!-- Records Section -->
        <Records v-if="referral" :referral="referral" />

    </div>
</template>

<script>
import Form from 'vform'
import Records from './Records.vue'
import Multiselect from 'vue-multiselect'

export default {
    name: 'ShowReferral',
    components: {
        Records,
        Multiselect,
    },

    data() {
        
        return{
            referral: '',
            reasons: [],
            referralSources: [],

            referralEditMode: false,
            
            recordBeneficiaries: [],

            referralForm: new Form({
                id: '',
                referral_source_id: '',
                referral_date: '',
                referring_person_name: '',
                referring_person_email: '',
                referral_narrative_reason: '',
                reasons: [],
                original_direct_individual_id: '',
            }),
        }
    },
    methods: {
        getReferralReasons(){
			this.$Progress.start();
			axios.get("/api/referral_reasons")
			.then(({data}) => {
				this.reasons = data.data
			});
			this.$Progress.finish();
		},
        getReferralSources(){			
			this.$Progress.start();
			axios.get("/api/referral_sources")
            .then(({data}) => {
                this.referralSources = data.data
            });
			this.$Progress.finish();
		},

        getReferral(){
			this.$Progress.start();
			axios.get("/api/referrals/"+this.$route.params.referral_id)
            .then(({data}) => {
                this.referral = data.data
            });
			this.$Progress.finish();
        },



        showEditReferralModal(){
			this.referralEditMode = true;
			this.referralForm.reset()
			$('#referralModal').modal('show')
			this.referralForm.fill(this.referral)
		},
		updateReferral(){
			this.$Progress.start();
			this.referralForm.put('/api/referrals/'+this.referralForm.id)
			.then(() => {
				// success
				Fire.$emit('referralChanged');
				$('#referralModal').modal('hide')
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
        this.getReferralSources()
        this.getReferralReasons()
        this.getReferral()

        Fire.$on('referralChanged', () => {
			this.getReferral();
		});
    }
}
</script>