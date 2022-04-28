<style scoped>


</style>

<template>
    <div>

        <div class="row mt-3 mb-3 pl-3"> 
            <h5>PS Intake | 
                <!-- {{ this.psIntake.casee.file_number }}  -->
                |  {{ this.psIntake.referral_date | myDateShort  }} | 
                <!-- {{ this.psIntake.referral_source.name }} -->
            </h5>
        </div>

        <div class="card">

        <div class="card-body" v-if="this.psIntake">
            <h5>PS Intake Details 
                <span @click="showEditReferralModal"
                    id='clickableAwesomeFont' class="ml-3 clickable">
                        <i class="fas fa-pencil-alt blue"></i>
                </span>
            </h5>

            <div class="row m-3">
                <div class="col mb-6">
                    <h6 class="card-subtitle mb-2 text-muted">Referral Source</h6>
                    <div class="ml-4">
                        <li>{{ this.psIntake.referral_date }}</li>
                        <!-- <li>{{ this.referral.referral_source.name }}</li>
                        <li>{{ this.referral.referring_person_name }}</li> -->
                        <li>{{ this.psIntake.referring_person_email }}</li>
                        <!-- <li>{{ this.referral.casee.file_number }}</li> -->
                    </div>
                </div>
                <div class="col mb-6" >
                    <h6 class="card-subtitle mb-2 text-muted">Reason of Referral</h6>
                    <div class="ml-4" v-for="reason in this.psIntake.reasons" :key="reason.id">
                        <!-- <li>{{ reason.name  }}</li> -->
                    </div>
                    <div>
                        <li>{{ this.psIntake.referral_narrative_reason  }}</li>
                    </div>
                </div>
                
            </div>
            <hr>
            <h5>Affected Beneficiaries
                <span @click="showEditReferralModal"
                    id='clickableAwesomeFont' class="ml-3 clickable">
                        <i class="fas fa-pencil-alt blue"></i>
                </span>

            </h5>
            <div class="row m-3">
                <div class="col mb-4" >
                    <h6 class="card-subtitle mb-2 text-muted">Direct Beneficiaries</h6>
                    <div class="ml-4">
                        <li v-for="directBeneficiary in this.psIntake.direct_referral_beneficiaries" :key="directBeneficiary.id">
                            <div>
                                <!-- <span>{{ directBeneficiary.name }}</span> -->
                            </div>
                        </li>
                    </div>
                    <br>
                    <h6 class="card-subtitle mb-2 text-muted">Indirect Beneficiaries</h6>
                    <div class="ml-4">
                        <li v-for="indirectBeneficiary in this.psIntake.indirect_referral_beneficiaries" :key="indirectBeneficiary.id">
                            <div>
                                <!-- <span>{{ indirectBeneficiary.name }}</span> -->
                            </div>
                        </li>
                    </div>
                </div>
            </div>
            

            <hr>
            <h5>Activity History</h5>

            <!-- <ul v-if="referral">
                <li v-for="record in referral.records" :key="record.id">
                    <span>{{ record.month.name }}</span>
                    <span v-show="record.status.name == 'Inactive'" class="badge badge-pill badge-secondary">{{ record.status.name }}</span>
                    <span v-show="record.status.name == 'Active'" class="badge badge-pill badge-success">{{ record.status.name }}</span>
                    <span v-show="record.status.name == 'Closed'" class="badge badge-pill badge-dark">{{ record.status.name }}</span>
                    <span v-show="record.is_new == 1" class="badge badge-pill badge-info">New</span>
                </li>
            </ul> -->

            <hr>
            <h5>Activity Log</h5>

            <div class="form-inline ml-2">

				<button class="btn btn-success btn-sm mr-2" @click="showCreateActivityModal">
					<i class="fas fa-plus-circle"></i><span><b> Activity</b></span>
				</button>
				<button class="btn btn-secondary btn-sm mr-5" @click="getPsIntake(this.psIntakeId)">
					<i class="fas fa-sync-alt"></i>
				</button>

                <!-- <select @change="getUsers" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value='-1' disabled>Worker...</option>
					<option v-for='record in referral.records' :value='record.id' :key="record.id">
                        <span>{{ record.month.name }}</span>
                    </option>
                </select> -->
                <button class="btn btn-danger" @click="closePssIntake">Close</button>           
            </div>

            <p v-if="!psIntake.activities.length" class="font-italic ml-5 mt-3">There are no records!</p>
    
            <div v-if="psIntake.activities.length" class="row mt-3 table-responsive m-1">
                <table class=" table table-hover border table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>beneficiary</th>
                            <th>Services Provided</th>
                            <th>Is Emergency</th>
                            <th>Emergency Type</th>
                            <th>Assigned Worker</th>
                            <th>Modify</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="activity in psIntake.activities" :key="activity.id">
                            <td><span>{{ activity.activity_date | myDateShort }}</span></td>
                            <td><span>{{ activity.beneficiary.name }}</span></td>
                            <td>
                                <span v-for="service in activity.services" :key="service.id" class="badge badge-pill badge-primary">{{service.service_type.name}}</span>
                            </td>
                            <td>
                                <span>{{ activity.is_emergency }}</span>
                            </td>
                            <td>
                                <span v-for="emergencyType in activity.emergency_types" :key="emergencyType.id" class="badge badge-pill badge-primary">{{emergencyType.name}}</span>
                            </td>

                            <td>{{ activity.user.full_name }}</td>
                            <td>
                                <a class="clickable mr-2" @click="showEditActivityModal(activity)">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="clickable" @click="deleteActivity(activity)">
                                    <i class="fas fa-trash red"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br><hr>

            <h5>Problems</h5>

            <br> <hr>

            <h5>Disabilities</h5>

            <br> <hr>


            <!-- <hr>
            <h5>Emergencies</h5>

            <div class="form-inline ml-2">
                <button class="btn btn-success btn-sm mr-2" @click="showCreateEmergencyModal">
					<i class="fas fa-plus-circle"></i><span><b> Emergency</b></span>
				</button>
				<button class="btn btn-secondary btn-sm mr-5" @click="getPsIntake(this.psIntakeId)">
					<i class="fas fa-sync-alt"></i>
				</button>

            
            </div>

            <div class="row mt-3">
				<table class="border table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Date</th>
                            <th>Beneficiary</th>
                            <th>EmergencyType</th>
                            <th>Assigned Worker</th>
                            <th>Modify</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="emergency in referral.emergencies" :key="emergency.id">
                            <td><span>{{ emergency.record.month.name }}</span></td>
                            <td><span>{{ emergency.emergency_date | myDateShort }}</span></td>
                            <td><span>{{ emergency.beneficiary.name }}</span></td>
                            <td>
                                <span v-for="emergencyType in emergency.emergency_types" :key="emergencyType.id" class="badge badge-pill badge-primary">
                                    {{ emergencyType.name }}
                                </span>
                            </td>
                            <td>{{ emergency.user.full_name }}</td>
                            <td>
                                <a class="clickable mr-2" @click="showEditEmergencyModal(emergency)">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="clickable" @click="deleteEmergency(emergency)">
                                    <i class="fas fa-trash red"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> -->



			<!-- <div class="row mt-3">
				<table class="border table table-hover">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Activities</th>
                            <th>Emergencies</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="record in referral.records" :key="record.id">
                            <td>
                                <span>{{ record.month.name }}</span>
                                <span v-show="record.status.name == 'Inactive'" class="badge badge-pill badge-secondary">{{ record.status.name }}</span>
                                <span v-show="record.status.name == 'Active'" class="badge badge-pill badge-success">{{ record.status.name }}</span>
                                <span v-show="record.status.name == 'Closed'" class="badge badge-pill badge-dark">{{ record.status.name }}</span>
                                <span v-show="record.is_new == 1" class="badge badge-pill badge-info">New</span>
                            </td>
                            <td></td>
                            <td>
                                <div class="list-unstyled">
                                    <li v-for="emergency in record.emergencies" :key="emergency.id">
                                        <span>{{ emergency.emergency_date | myDateShort }}</span>
                                        <span>{{ emergency.user.full_name }}</span>
                                        <a class="clickable" @click="showEditEmergencyModal(emergency)">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a class="clickable" @click="deleteEmergency(emergency)">
                                            <i class="fas fa-trash red"></i>
                                        </a>
                                    </li>
                                </div>
                            </td>
                            <td>
                                
                                <a class="clickable">
                                    <i class="fa fa-trash red"></i>
                                </a>
                                <router-link
                                :to="{name: 'RecordDetails', params: { recordId: record.id }}"
                                class="fa fa-eye blue align-middle"
                                >
                                
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> -->

        

        </div>
<br><br>


        </div>
            <EmergencyModal 
            :v-if="selectedEmergency.id"
            :emergencyEditMode='emergencyEditMode' 
            :selectedEmergency='selectedEmergency'
            v-on:psIntakeChanged="getPsIntake()">
            </EmergencyModal>

            <ActivityModal 
            :v-if="selectedActivity.id"
            :activityEditMode='activityEditMode' 
            :selectedActivity='selectedActivity'
            v-on:psIntakeChanged="getPsIntake()">
            </ActivityModal>
    </div>
</template>
<script>
import router from '../../../../router'
import ActivityModal from './ActivityModal'
import EmergencyModal from './EmergencyModal'
import Multiselect from 'vue-multiselect'
import axiosMixin from '../../../../mixins/axiosMixin'

export default {
    mixins: [axiosMixin],
    props: {
        psIntakeId: {
            // type: String,
            required: true
        }
    },
    components: {
        EmergencyModal,
        ActivityModal,
        Multiselect,
    },
    data(){
        return {
            psIntake: "",
            referralBeneficiaries: [],
            caseeBeneficiaries: [],
            selectedEmergency: {},
            selectedActivity: {},
            emergencyEditMode: false,
            activityEditMode: false,
        }
    },
    methods: {

        closePssIntake(){
            let currentRecord = this.psIntake['current_record'];
            if(currentRecord['is_new'] == 1){
                Swal.fire({
                    position: 'center',
                    icon: 'warning',
                    title: 'Cannot Close!',
                    text: 'A new case cannot be closed, you can close it next month',
                    showConfirmButton: true,
                    // timer: 1500
                })

            }else{
            
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are going to close this case!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, close it!'
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        this.$Progress.start();
                        axios.put('/api/referrals/'+this.psIntake.id + '/close')
                        .then(() => {
                            // success
                            Fire.$emit('referralChanged');
                            Swal.fire(
                                'Case Closed!',
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
            }
        },

        showEditReferralModal(){

        },
        showCreateEmergencyModal(){
			this.emergencyEditMode = false;
			this.selectedEmergency = {};
			$('#emergencyModal').modal('show')
		},

        showEditEmergencyModal(emergency){
			this.emergencyEditMode = true;
			this.selectedEmergency = emergency;
			$('#emergencyModal').modal('show')
		},

        deleteEmergency(emergency){
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
					axios.delete('/api/emergencies/'+emergency.id)
					.then(() => {
						// success
						Fire.$emit('referralChanged');
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

        showCreateActivityModal(){
			this.activityEditMode = false;
			this.selectedActivity = {};
			$('#activityModal').modal('show')
		},

        showEditActivityModal(activity){
			this.activityEditMode = true;
			this.selectedActivity = activity;
			$('#activityModal').modal('show')
		},

        deleteActivity(activity){
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
					axios.delete('/api/activities/'+activity.id)
					.then(() => {
						// success
						Fire.$emit('referralChanged');
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
    created(){
        const tooltips = document.querySelectorAll('.tt')
        tooltips.forEach(t => {
            new bootstrap.Tooltip(t)
        })
        this.getPsIntake(this.psIntakeId);
        this.getCaseeBeneficiaries(this.$route.params.caseeId);
        Fire.$on('psIntakeChanged', () => {
			this.getPsIntake(this.psIntakeId);
		});
        this.getActiveReferralBeneficiaries(this.$route.params.psIntakeId);
    }
}
</script>