<style scoped>
#clickableAwesomeFont {
    cursor: pointer
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>
        <div class="card card-tabs" v-if="referral">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                    <li class="pt-2 px-3"><h3 class="card-title">Monthly Records</h3></li>
                    <li class="nav-item" v-for="(record, i) in referral.records" :key="i" @click="changeSelectedRecordTab(record)">
                        <a class="nav-link" v-bind:id="record.id" 
                        v-bind:class="{ 'bg-white': currentRecordId==record.id, 'border-left': currentRecordId==record.id, 'border-top': currentRecordId==record.id, 'border-right': currentRecordId==record.id,}"
                        data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="false">
                            {{ record.month.name }}<br>
                            <span v-show="record.status.name == 'Inactive'" class="badge badge-pill badge-secondary">{{ record.status.name }}</span>
                            <span v-show="record.status.name == 'Active'" class="badge badge-pill badge-success">{{ record.status.name }}</span>
                            <span v-show="record.status.name == 'Closed'" class="badge badge-pill badge-dark">{{ record.status.name }}</span>
                            <span v-show="record.is_new == 1" class="badge badge-pill badge-info">New</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body" v-if="referral">
                <div class="tab-content" id="custom-tabs-two-tabContent" v-for="(record, i) in referral.records" :key="i">
                    <div v-bind:id="record.id" 
                    class="tab-pane fade" 
                    v-bind:class="{ show: currentRecordId==record.id, active: currentRecordId==record.id}"
                    role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                        {{ record.month.name }}
                        

                        <div>
                            <button class="btn btn-success">
                                Modify Beneficiaries
                            </button>


                            
                            <!-- Beneficiaries -->
                            <div class="row">
                                <div class="card m-2 col-md-6 col-12" v-for="beneficiary in recordBeneficiaries" :key="beneficiary.id">
                                    <div class="card-header">
                                        <span class="mt-2">{{ beneficiary.individual.name }}</span>
                                        <span v-show="beneficiary.is_direct == 1" class="badge badge-pill badge-primary">Direct</span>
                                        <span @click="showEditBeneficiaryModal(beneficiary, record)"
                                        id='clickableAwesomeFont' class="ml-5">
                                            <i class="fa fa-edit blue fa-lg"></i>
                                        </span>
                                        <span @click="deleteBeneficiary(beneficiary.id)"
                                        id='clickableAwesomeFont'>
                                            <i class="fa fa-trash red"></i>
                                        </span>

                                        
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col mb-2">
                                                <h6 class="text-muted mr-2">
                                                    Provided Services
                                                    <span @click="showEditServiceModal(beneficiary, record)"
                                                    id='clickableAwesomeFont'>
                                                    </span>
                                                </h6>


                                                <div class="ml-2">
                                                    <li v-for="service in beneficiary.services" :key="service.id">
                                                        {{ service.name }}
                                                    </li>
                                                </div>
                                            </div>
                                            <div class="col mb-2">
                                                <h6 class="text-muted mr-2">
                                                    Disabilities
                                                </h6>
                                                
                                                <div class="ml-2">
                                                    <li v-for="disability in beneficiary.disabilities" :key="disability.id">
                                                        {{ disability.name }}
                                                    </li>
                                                </div>
                                            </div>
                                            <div class="col mb-2" >
                                                <h6 class="card-subtitle mb-2 text-muted">
                                                    Emergencies
                                                    <!-- <i class="fa fa-plus-circle green fa-lg"></i> -->
                                                </h6>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                            
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Service Modal -->
        <!-- <div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!serviceEditMode" class="modal-title" id="serviceModalLabel">Add Service</h5>
                        <h5 v-show="serviceEditMode" class="modal-title" id="serviceModalLabel">Modify Services Provided</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="serviceEditMode ? updateService() : createService()">
                        <div class="modal-body">
                            
                            <div class="form-group">
                                <label for="beneficiary_id" class="form-label">Beneficiary</label>
                                <select name="beneficiary_id" v-model="serviceForm.beneficiary_id" id="beneficiary_id" class="form-control" :class="{ 'is-invalid': serviceForm.errors.has('beneficiary_id') }">
                                    <option v-for='beneficiary in this.currentRecord.beneficiaries' :value='beneficiary.id' :key="beneficiary.id">{{ beneficiary.individual.name }}</option>
                                </select>
                                <HasError :form="serviceForm" field="beneficiary_id" />
                            </div>
                            
                            <div class="form-group">
                                <label for="name" class="form-label">Record ID</label>
                                <input id="name" v-model="serviceForm.record_id" type="text" name="name" class="form-control" disabled>
                            </div>

                            <div class="form-group">
                                <label for="name" class="form-label">Beneficiary ID</label>
                                <input id="name" v-model="serviceForm.beneficiary_id" type="text" name="name" class="form-control" disabled>
                            </div>

                            <div class="form-group" v-if="services">
								<label class="typo__label">Services Provided</label>
								<multiselect 
								v-model="beneficiaryForm.services" 
								:options="services" 
								:multiple="true" 
								:close-on-select="false" 
								:clear-on-select="false" 
								:preserve-search="true" 
								placeholder="Pick some" 
								label="name" 
								track-by="name" 
								:preselect-first="true">
								</multiselect>
							</div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="!serviceEditMode" type="submit" class="btn btn-success">Create</button>
                            <button v-show="serviceEditMode" type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> -->

        <!-- Beneficiary Modal -->
        <div class="modal fade" id="beneficiaryModal" tabindex="-1" aria-labelledby="beneficiaryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!beneficiaryEditMode" class="modal-title" id="beneficiaryModalLabel">
                            Add Beneficiary | 
                            {{ this.currentRecord.month.name }}
                            </h5>
                        <h5 v-show="beneficiaryEditMode" class="modal-title" id="beneficiaryModalLabel">Edit Beneficiary {{ this.currentRecord.month.name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="beneficiaryEditMode ? updateBeneficiary() : createBeneficiary()">
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="beneficiary_id" class="form-label">Beneficiary</label>
                                <select name="beneficiary_id" v-model="beneficiaryForm.beneficiary_id" id="beneficiary_id" class="form-control" :class="{ 'is-invalid': beneficiaryForm.errors.has('beneficiary_id') }">
                                    <option v-for='beneficiary in this.currentRecord.beneficiaries' :value='beneficiary.id' :key="beneficiary.id">{{ beneficiary.individual.name }}</option>
                                </select>
                                <HasError :form="beneficiaryForm" field="beneficiary_id" />
                            </div>
                            
                            <div class="form-group">
                                <label for="name" class="form-label">Record ID</label>
                                <input id="name" v-model="beneficiaryForm.record_id" type="text" name="name" class="form-control" disabled>
                            </div>

                            <div class="form-group">
                                <label for="name" class="form-label">Beneficiary ID</label>
                                <input id="name" v-model="beneficiaryForm.id" type="text" name="name" class="form-control" disabled>
                            </div>

                            <div class="form-group" v-if="services">
								<label class="typo__label">Services Provided</label>
								<multiselect 
								v-model="beneficiaryForm.services" 
								:options="services" 
								:multiple="true" 
								:close-on-select="false" 
								:clear-on-select="false" 
								:preserve-search="true" 
								placeholder="Pick some" 
								label="name" 
								track-by="name" 
								:preselect-first="true">
								</multiselect>
							</div>

                            <div class="form-group" v-if="disabilities">
								<label class="typo__label">Disabilities</label>
								<multiselect 
								v-model="beneficiaryForm.disabilities" 
								:options="disabilities" 
								:multiple="true" 
								:close-on-select="false" 
								:clear-on-select="false" 
								:preserve-search="true" 
								placeholder="Pick some" 
								label="name" 
								track-by="name" 
								:preselect-first="true">
								</multiselect>
							</div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="!beneficiaryEditMode" type="submit" class="btn btn-success">Create</button>
                            <button v-show="beneficiaryEditMode" type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    



</template>

<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'

export default {
    name: 'Records',
    components: { Multiselect },
    props: {
        referral:Object,
    },
    data() {
        return{
            serviceEditMode: false,
            beneficiaryEditMode: false,
            services: [],
            disabilities: [],

            currentRecordId: '',
            currentRecord: {},
            currentBeneficiary: '',
            recordBeneficiaries: [],

            serviceForm: new Form({
                record_id: '',
                beneficiary_id: '',
                services_ids: [],
            }),
            beneficiaryForm: new Form({
                id: '',
                individual_id: '',
                record_id: '',
                is_direct: '',
                disabilities: [],
                services: [],
                // followUps: [],
                // disabilities: [],
            }),
        }
    },
    methods: {
        init() { 
            // set latest record id
            // var lastPosition = this.lineData.length -1;
            this.currentRecordId = this.referral.records[0].id;
            this.currentRecord = this.referral.records[0];
            this.getRecordBeneficiaries()
        },

        changeSelectedRecordTab(record){
            this.currentRecordId=record.id
            this.currentRecord=record
            this.serviceForm.record_id = record.id
            this.getRecordBeneficiaries()
        },
        

        getRecordBeneficiaries(){			
			this.$Progress.start();
			axios.get('/api/records/' + this.currentRecordId + '/beneficiaries', { params: { record_id: this.currentRecordId } })
            .then(({data}) => {
                this.recordBeneficiaries = data.data
            });
			this.$Progress.finish();
		},
        getServices(){
			this.$Progress.start();
			axios.get("/api/services")
			.then(({data}) => {
				this.services = data.data
			});
			this.$Progress.finish();
		},
        getDisabilities(){
			this.$Progress.start();
			axios.get("/api/disabilities")
			.then(({data}) => {
				this.disabilities = data.data
			});
			this.$Progress.finish();
		},

        showAddBeneficiaryModal(){
            this.beneficiaryEditMode = false;
			this.beneficiaryForm.reset()
			$('#beneficiaryModal').modal('show')
        },
        showEditBeneficiaryModal(beneficiary, record){
			this.beneficiaryEditMode = true;
			this.beneficiaryForm.reset()
            this.beneficiaryForm.record_id = record.id
            this.beneficiaryForm.beneficiary_id = beneficiary.id
			$('#beneficiaryModal').modal('show')
			this.beneficiaryForm.fill(beneficiary)
		},
        createBeneficiary() {
			this.$Progress.start();
			this.beneficiaryForm.post('/api/beneficiaries')
			.then(() => {
				// success
				Fire.$emit('beneficiariesChanged');

				$('#beneficiaryModal').modal('hide')
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

        updateBeneficiary(){
			this.$Progress.start();
			this.beneficiaryForm.put('/api/beneficiaries/'+this.beneficiaryForm.id)
			.then(() => {
				// success
				Fire.$emit('beneficiariesChanged');
				$('#beneficiaryModal').modal('hide')
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







		deleteBeneficiary(id){
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			})
			.then((result) => {
				if (result.isConfirmed) {
					this.$Progress.start();
					this.beneficiaryForm.delete('/api/beneficiaries/'+id)
					.then(() => {
						// success
						Fire.$emit('beneficiariesChanged');
						Swal.fire(
							'Deleted!',
							'It has been deleted.',
							'success'
						)
						this.$Progress.finish();
					})
					.catch(() => {
						// error
						Swal("Failed!", "There was something wrong.", "warning");
					});
				}
			})
		},
	},

	created() {
		// console.log($getPermissions());
		this.init()

		this.getServices()
        this.getDisabilities()
		
		Fire.$on('servicesChanged', () => {
			this.getServices();
		});
		
        Fire.$on('beneficiariesChanged', () => {
			this.getRecordBeneficiaries();
		});
		
	}
}
</script>