<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>

		<div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">
                    Add Referral
                </h3>          
            </div>

            <div class="card-body">
                <div class="card-body table-responsive p-0">
					<form @submit.prevent="referralEditMode ? updateReferral() : createReferral()">
						<div>

							<h5 class="card-title text-primary">Beneficiaries</h5>
							<br>
							<br>

							<div class="form-group" v-if="caseeIndividuals">
								<label class="typo__label">Select Beneficiaries</label>
								<multiselect 
								v-model="referralForm.referral_beneficiaries" 
								:options="caseeIndividuals" 
								:multiple="true" 
								:close-on-select="false" 
								:clear-on-select="false" 
								:preserve-search="false" 
								:hideSelected="true"
								:taggable="true"
								placeholder="" 
								label="name" 
								track-by="name" 
								:preselect-first="false">
									<!-- <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template> -->
								</multiselect>
								<!-- <pre class="language-json"><code>{{ value  }}</code></pre> -->
							</div>
							
							<div class="form-group" v-if="caseeIndividuals">
								<label class="typo__label">Select Direct</label>
								<multiselect 
								v-model="referralForm.direct_beneficiaries" 
								:options="referralForm.referral_beneficiaries" 
								:multiple="true" 
								:close-on-select="false" 
								:clear-on-select="false" 
								:preserve-search="false" 
								:hideSelected="true"
								:min="1"
								placeholder="Pick some" 
								label="name" 
								track-by="name"
								:preselect-first="true">
									<!-- <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template> -->
								</multiselect>
								<!-- <pre class="language-json"><code>{{ value  }}</code></pre> -->
							</div>

							<br><hr>
							<h5 class="card-title text-primary">Referral Details</h5><br>
							<br>
							
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
						<div>
							<button v-show="!referralEditMode" type="submit" class="btn btn-success">Create</button>
							<button v-show="referralEditMode" type="submit" class="btn btn-primary">Update</button>
						</div>

					</form>
                </div>
            </div>
        </div>
			
    </div>
</template>
<script>
import Form from 'vform'
import router from '../router'
import Multiselect from 'vue-multiselect'
import axiosMixin from '../../mixins/axiosMixin'	

	
export default {
	mixins: [axiosMixin],
	components: { Multiselect },
	name: 'AddReferral',
    data(){
        return {
			checks: [1],
			referralEditMode: false,
			createdReferral: '',

            casee: '',
            caseeIndividuals: [],
			directIndividual: '',

			referralSources: [],
            nationalities: [],
			reasons: [],

			referralForm : new Form({
				id: '',
				referral_beneficiaries: [],
				direct_beneficiaries : [],
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
    methods:{
		getCasee(){
			console.log('print'+this.$route.params.caseeId);
			this.$Progress.start();
			axios.get("/api/casee/"+this.$route.params.caseeId)
            .then(({data}) => {
                this.casee = data.data
            });
			this.$Progress.finish();
        },

		getNationalities(){
			this.$Progress.start();
			axios.get("/api/nationalities")
			.then(({data}) => {this.nationalities = data.data});
			this.$Progress.finish();
		},
		getReasons(){
			this.$Progress.start();
			axios.get("/api/referral_reasons")
			.then(({data}) => {this.reasons = data.data});
			this.$Progress.finish();
		},
		getReferralSources(){			
			this.$Progress.start();
			axios.get("/api/referral_sources").then(({data}) => (this.referralSources = data.data));
			this.$Progress.finish();
		},

        getCaseeIndividuals(){
			this.$Progress.start();
			axios.get('/api/individuals/' + this.$route.params.id + '/other_casee_individuals/')
			.then(({data}) => {
				this.caseeIndividuals = data.data
			});
			this.$Progress.finish();
		},

		createReferral() {
			this.$Progress.start();
			this.referralForm.post('/api/referrals')
			.then((res) => {
				// success
				// Fire.$emit('caseeIndividualsChanged');
				
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


		goToIndividualPage(){
			router.push({ path: '/individuals/'+this.directIndividual.id })
			console.log(directIndividual.id)
		},
		
		goToReferralPage(){
			router.push({ path: '/referrals/'+this.directIndividual.id })
			console.log(directIndividual.id)
		},

    },
	created(){
		
		this.getCasee()
		this.getReferralSources()
		this.getNationalities()
		this.getReasons()
		this.getCaseeIndividuals()
	}
}
</script>