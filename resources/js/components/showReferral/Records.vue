
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>

    <div>
        <h5 class="mt-5 mb-3">Monthly Records</h5>
        <div class="card card-tabs" v-if="referral">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                    
                    <li class="nav-item" v-for="(record, i) in referral.records" :key="i" @click="changeSelectedRecordTab(record)">
                        <a class="nav-link" v-bind:id="record.id" 
                        v-bind:class="{ 'bg-blue': record.id==currentRecord.id, 'border-left': record.id==currentRecord.id, 'border-top': record.id==currentRecord.id, 'border-right': record.id==currentRecord.id,}"
                        data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="false">
                            {{ record.month.name }}
                            <span v-show="record.status.name == 'Inactive'" class="badge badge-pill badge-secondary">{{ record.status.name }}</span>
                            <span v-show="record.status.name == 'Active'" class="badge badge-pill badge-success">{{ record.status.name }}</span>
                            <span v-show="record.status.name == 'Closed'" class="badge badge-pill badge-dark">{{ record.status.name }}</span>
                            <span v-show="record.is_new == 1" class="badge badge-pill badge-info">New</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body" v-if="referral">
                <div v-for="(record, i) in referral.records" :key="i"
                class="tab-content" id="custom-tabs-two-tabContent">
                    <div v-bind:id="record.id" 
                    class="tab-pane fade" 
                    v-bind:class="{ show: record.id==currentRecord.id, active: record.id==currentRecord.id}"
                    role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                    
                        <div v-if="recordBeneficiaries">

                            <br>
                            
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <b>Beneficiaries</b>
                                    </div>
                                    <div class="col-6">
                                        <ul>
                                            <div class="form-check" v-for="(recordBeneficiary, i) in record.record_beneficiaries" :key="i">
                                                <div class="row">
                                                    <!-- <div class="col-2">
                                                        <input class="form-check-input" name="beneficiariesStatuses[]" v-model="recordForm.beneficiariesStatuses" type="checkbox"  :value="recordBeneficiary" :id="'direct'+i" :disabled="recordBeneficiariesEditMode ? false : true">
                                                        <label class="form-check-label" :for="'direct'+i">Direct</label>
                                                    </div> -->
                                                    
                                                    <div v-show="recordBeneficiariesEditMode" class="form-group">
                                                        <div class="row">
                                                            <span>{{ recordBeneficiary.individual.name }}</span>
                                                            <select name="recordBeneficiaryStatuses[]" :id="'status'+recordBeneficiary.id" class="form-control" @change="addToRecordBeneficiariesArray(recordBeneficiary)">
                                                                <option :value='0'>Exclude</option>
                                                                <option :value="1">Direct</option>
                                                                <option :value='2'>Indirect</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div v-show="!recordBeneficiariesEditMode" class="col">
                                                        
                                                        <span v-show="recordBeneficiary.status == 1" class="badge badge-pill badge-primary">Direct</span>
                                                        <span v-show="recordBeneficiary.status == 2" class="badge badge-pill badge-secondary">Indirect</span>
                                                        <span v-show="recordBeneficiary.status == 0" class="badge badge-pill badge-danger">Not included</span>
                                                        <span>{{ recordBeneficiary.individual.name }} </span>
                                                        <a class="clickable edit-button mb-6" :id="recordBeneficiary.id" v-show="!recordBeneficiariesEditMode" @click="showEditRecordBeneficiaryModal(recordBeneficiary)">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        <button class="btn btn-primary btn-sm" v-show="recordBeneficiariesEditMode" @click="updateRecordBeneficiaries(record)">Save</button>
                                                    </div>


                                                    <!-- <div class="col-10">
                                                        <input class="form-check-input" name="recordBeneficiaries[]" v-model="recordForm.selectedBeneficiariesIds" type="checkbox"  :value="recordBeneficiary.id" :id="i" :disabled="recordBeneficiariesEditMode ? false : true">
                                                        <label class="form-check-label" :for="i">{{ recordBeneficiary.name }}</label>
                                                    </div> -->

                                                </div>


                                            </div>
                                        </ul>
                                    </div>
                                    <div class="col">
                                        <a class="clickable edit-button mb-6" v-show="!recordBeneficiariesEditMode" @click="editRecordBeneficiaries(record.record_beneficiaries)">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <button class="btn btn-primary btn-sm" v-show="recordBeneficiariesEditMode" @click="updateRecordBeneficiaries(record)">Save</button>
                                    </div>
                                </div>

                                <hr>
                            
                                <div class="row">
                                    <div class="col">
                                        1 of 3
                                    </div>
                                    <div class="col-6">
                                        2 of 3 (wider)
                                    </div>
                                    <div class="col">
                                        3 of 3
                                    </div>
                                </div>
                            </div>
                            
                            
                            <hr>



                                                
                            <a class="clickable mb-6" @click="showSelectBeneficiariesModel">
                                Choose individuals from File<i class="fa fa-edit red fa"></i>
                            </a>
                            
                            <!-- Beneficiaries -->
                            <div class="row">
                                <div class="card individual-card m-2 col-md-6 col-12" v-for="beneficiary in recordBeneficiaries" :key="beneficiary.id">


                                    <div class="card-body">
                                        <h5>
                                            <span class="mt-2">{{ beneficiary.individual.name }}</span>
                                            <span v-show="beneficiary.status == 1" class="badge badge-pill badge-primary">Direct</span>
                                            <br><br>

                                            
                                            <div class="dropdown">
                                    
                                                <button class="btn btn-tool float-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                    <button class="dropdown-item" type="button" @click="showEditBeneficiaryAttachmentsModal(beneficiary, record)">Edit</button>
                                                    <button class="dropdown-item" type="button" @click="deleteBeneficiary(beneficiary.id)">
                                                        <span class="text-red">Delete</span>
                                                    </button>
                                                </div>
                                            </div>

                                        </h5>
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

		<!-- Modal -->
		<RecordBeneficiaryModal 
		:v-if="selectedRecordBeneficiary.id"
		:selectedRecordBeneficiary='selectedRecordBeneficiary' 
		v-on:recordBeneficiariesChanged="getReferral()">
		</RecordBeneficiaryModal>

                                    <!-- Activities Section -->
                            <Activities 
                            :v-if="currentRecord.id"
                            :selectedRecord='currentRecord' 
                            v-on:recordsChanged="getReferral()">
                            </Activities>

    </div>
    


</template>

<script>
import Form from 'vform'
import Activities from '../ReferralDetails/Activities.vue'
import Multiselect from 'vue-multiselect'
import RecordBeneficiaryModal from './RecordBeneficiaryModal'

export default {
    name: 'Records',
    components: {
        Activities,
        Multiselect,
        RecordBeneficiaryModal,
    },

    props: {
        referral_id: Number,
        casee: Object,
    },
    data() {
        return{
            selectedRecordBeneficiary: {},

            recordBeneficiariesEditMode: false,
            serviceEditMode: false,
            beneficiaryAttachmentsEditMode: false,
            services: [],
            disabilities: [],
            
            referral: '',

            currentRecord: '',
            currentBeneficiary: '',
            recordBeneficiaries: [],
            
            caseeIndividuals: [],
            remianingBeneficiaries: [],


            serviceForm: new Form({
                record_id: '',
                recordBeneficiary_id: '',
                services_ids: [],
            }),
            beneficiaryForm: new Form({
                id: '',
                individual_id: '',
                record_id: '',
                status: '',
                disabilities: [],
                services: [],
                // followUps: [],
                // disabilities: [],
            }),
            recordBeneficiaryForm: new Form({
                individual_id: '',
                status: '',
            }),
            recordForm: new Form({
                id: '',
                month_id: '',
                referral_id: '',
                status_id: '',
                is_new: '',
                recordBeneficiaries: [],
            }),


        }
    },
    methods: {
        showMenu(id){
            let menu = document.getElementById(id).style.display = "none";
        },

    	showEditRecordBeneficiaryModal(recordBeneficiary){
			this.selectedRecordBeneficiary = recordBeneficiary;
			$('#recordBeneficiaryModal').modal('show')
		},

        editRecordBeneficiaries(recordBeneficiaries){
            this.recordBeneficiariesEditMode = true;
            
            recordBeneficiaries.forEach(recordBeneficiary => {
                this.recordBeneficiaries.push(recordBeneficiary);
            });

            console.log(this.recordBeneficiaries);

        },
        updateRecordBeneficiaries(record){
            this.recordBeneficiariesEditMode = false;
            
            // this.recordForm.id = record.id
            // this.recordForm.month_id = record.month_id
            // this.recordForm.referral_id = record.referral_id
            // this.recordForm.status_id = record.status_id
            // this.recordForm.is_new = record.is_new

            // this.updateRecord();
        },

        updateRecord(){
            
			this.$Progress.start();
			this.recordForm.put('/api/records/'+this.recordForm.id)
			.then(() => {
				// success
				Fire.$emit('recordChanged');
				Swal.fire(
					'Updated!',
					'It has been updated.',
					'success'
				)
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
                Toast.fire({
					icon: 'error',
					title: e
				})
				this.$Progress.fail();
			})
		},
        addToRecordBeneficiariesArray(individual)
        {
            recordBeneficiaries




            let status = document.getElementById('status'+individual.id).value;

            // check if the beneficiary already in the array
            let found = this.recordForm.recordBeneficiaries.find(o => o.individual_id === individual.id);
            
            // if aleady in the array; just update the status
            if(found){
                // if status is execlude; remove the beneficiary
                if($status == -1){
                    this.recordForm.recordBeneficiaries.remove(found);
                }
                found.status = status;
            }
            // if not found; added it to the array
            else{
                //if($status == 1 || $status == 0){
                    let recordBeneficiary = {
                        "individual_id": individual.id,
                        "status": status,
                    };
                    this.recordForm.recordBeneficiaries.push(recordBeneficiary);
                //}

            } 
            console.log(found);
            

        },





        getReferral(){
            console.log("fired")
			this.$Progress.start();
			axios.get("/api/referrals/"+this.referral_id)
            .then(({data}) => {
                this.referral = data.data
            });
			this.$Progress.finish();
        },
        // getLatestRecord(){
		// 	this.$Progress.start();
		// 	axios.get("/api/referrals/" + this.referral_id +"/latest-record/")
        //     .then(({data}) => {
        //         this.latestRecord = data.data
        //         this.record = this.latestRecord;
        //     });
		// 	this.$Progress.finish();
        // },

        getLatestRecord(){
			this.$Progress.start();
			axios.get("/api/referrals/" + this.referral_id +"/latest-record/")
            .then(({data}) => {
                this.latestRecord = data.data
                // this.record = this.latestRecord;
            });
			this.$Progress.finish();
        },

        init() { 
            // (1) get Referral
            this.$Progress.start();
			axios.get("/api/referrals/"+this.referral_id)
            .then(({data}) => {
                this.referral = data.data
                var latestRecord = this.referral.records[0];
                
                // (2) set selected tab to latest record
                this.record = latestRecord
                this.serviceForm.record_id = latestRecord.id
                this.getRecordBeneficiaries()
            });
			this.$Progress.finish();

        },
        changeSelectedRecordTab(record){
            this.currentRecord=record
            this.serviceForm.record_id = record.id
            this.getRecordBeneficiaries()
        },
        
        getCaseeIndividuals(){
            this.$Progress.start();
            axios.get('/api/casees/'+this.casee.id+'/individuals')
            .then(({data}) => {
                this.caseeIndividuals = data.data
            });
            this.$Progress.finish();
		},

        getRecordBeneficiaries(){			
			this.$Progress.start();
			axios.get('/api/records/' + this.record.id + '/record-beneficiaries', { params: { record_id: this.record.id } })
            .then(({data}) => {
                this.recordBeneficiaries = data.data
                this.remianingBeneficiaries = this.caseeIndividuals.filter(function(obj) { return this.recordBeneficiaries.indexOf(obj) == -1; });
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

        showSelectBeneficiariesModel(){
            // this.beneficiaryAttachmentsEditMode = false;
			this.beneficiaryForm.reset()
			$('#beneficiaryAttachmentsModal').modal('show')
        },
        showEditBeneficiaryAttachmentsModal(beneficiary, record){
			this.beneficiaryAttachmentsEditMode = true;
			this.beneficiaryForm.reset()
            this.beneficiaryForm.record_id = record.id
            this.beneficiaryForm.beneficiary_id = beneficiary.id
			$('#beneficiaryAttachmentsModal').modal('show')
			this.beneficiaryForm.fill(beneficiary)
		},
        createBeneficiaryAttachments() {
			this.$Progress.start();
			this.beneficiaryForm.post('/api/beneficiaries')
			.then(() => {
				// success
				Fire.$emit('beneficiariesAttachmentsChanged');

				$('#beneficiaryAttachmentsModal').modal('hide')
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

        updateBeneficiaryAttachments(){
			this.$Progress.start();
			this.beneficiaryForm.put('/api/beneficiaries/'+this.beneficiaryForm.id)
			.then(() => {
				// success
				Fire.$emit('beneficiariesAttachmentsChanged');
				$('#beneficiaryAttachmentsModal').modal('hide')
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
						Fire.$emit('beneficiariesAttachmentsChanged');
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
        this.init()
        // this.getLatestRecord();
		// console.log($getPermissions());
		

        this.getCaseeIndividuals()
		this.getServices()
        this.getDisabilities()
        
        
		
		Fire.$on('servicesChanged', () => {
			this.getServices();
		});
		
        Fire.$on('beneficiariesAttachmentsChanged', () => {
			this.getReferral();
            this.getRecordBeneficiaries();
		});

        Fire.$on('recordBeneficiariesChanged', () => {
			this.getReferral();
		});
		
	},
    // watch: {
    //     'caseeIndividuals'(next, prev) {
    //         this.fileNumber
    //     },
    // },
}
</script>