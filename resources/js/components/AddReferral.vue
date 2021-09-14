<template>
    <div>

		<div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">
                    Add Referral
                </h3>          
            </div>

            <div class="card-body">
                <div class="card-body table-responsive p-0" v-if="directIndividual">
					<form @submit.prevent="referralEditMode ? updateReferral() : createReferral()">
						<div>

							<h5 class="card-title text-primary">Beneficiaries</h5>
							<br>
							<br>

							<div class="form-group">
								<label for="direct_individual_name" class="form-label">Direct Beneficiary</label>
								<input id="direct_individual_name" v-model="directIndividual.name" type="text" name="direct_individual_name" class="form-control" disabled>
								<HasError :form="referralForm" field="direct_individual_name" />
							</div>

								<div class="form-group">
								<label for="referral_source_id" class="form-label">Select Indirect Beneficiaries</label>
								<select multiple name="referral_source_id" v-model="referralForm.indirect_beneficiaries_ids" id="referral_source_id" class="form-control" :class="{ 'is-invalid': referralForm.errors.has('individual') }">
									<option v-for='individual in directIndividual.file.individuals' :value='individual.id' :key="individual.id">{{ individual.name }}</option>
								</select>
								<HasError :form="referralForm" field="referral_source_id" />
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
								<label for="referring_person_email" class="form-label">Referring Person Name</label>
								<input id="referring_person_email" v-model="referralForm.referring_person_email" type="text" name="referring_person_email" class="form-control">
								<HasError :form="referralForm" field="referring_person_email" />
							</div>

						</div>
						<div>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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

export default {
    data(){
        return {

			referralEditMode: false,

            file: '',
            fileIndividuals: [],
			directIndividual: '',

			referralSources: [],
            nationalities: [],

			referralForm : new Form({
				id: '',
				referral_source_id: '',
				referral_date: '',
				referring_person_name: '',
				referring_person_email: '',
                original_direct_individual_id: this.$route.params.id,
				indirect_beneficiaries_ids : [],
            }),
        }
    },
    methods:{
		getIndividual(){
            console.log("hi")
			this.$Progress.start();
			axios.get("/api/individuals/"+this.$route.params.id)
            .then(({data}) => {
                this.directIndividual = data.data
            });
			this.$Progress.finish();
            
        },
		getNationalities(){
			this.$Progress.start();
			axios.get("/api/nationalities").then(({data}) => (this.nationalities = data.data));
			this.$Progress.finish();
		},
		getReferralSources(){			
			this.$Progress.start();
			axios.get("/api/referral-sources").then(({data}) => (this.referralSources = data.data));
			this.$Progress.finish();
		},


        getFileIndividuals(){
			
			if(this.file){
				this.$Progress.start();
				axios.get('/api/files/'+this.file.id+'/individuals')
				.then(({data}) => {
					this.fileIndividuals = data.data
				});
				this.$Progress.finish();
			}else{
				this.fileIndividuals = []
			}

		},

		createReferral() {
			this.$Progress.start();
			this.referralForm.post('/api/referrals')
			.then(() => {
				// success
				// Fire.$emit('fileIndividualsChanged');
				
				goToIndividualPage()
				Toast.fire({
					icon: 'success',
					title: 'Added successfully'
				})
				
				this.$Progress.finish();
			})
			.catch(() => {
				// error
				this.$Progress.fail();
			})
		},


		// File Methods
		getFile(){
			this.$Progress.start();
			axios.get('/api/files/get/'+this.fileForm.file_number)
            .then(({data}) => {
					this.file = data.data
					Fire.$emit('fileChanged');
					Fire.$emit('fileIndividualsChanged');
					
					this.$Progress.finish();
				if(this.file){
					this.showRegisterByFileNumberSection = true	
				}else{
					console.log('hi')
					this.showRegisterByFileNumberSection = false
					this.showCreateFileModal()
				}
            })
		},


		goToIndividualPage(){
			router.push({ path: '/individuals/'+this.directIndividual_id })
		},
		

    },
	created(){
		this.getIndividual();
		this.getReferralSources();
		this.getNationalities();
	}
}
</script>