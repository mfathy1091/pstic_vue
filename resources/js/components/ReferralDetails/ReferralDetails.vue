<style scoped>
.router-link-active{
    background-color: #ffffff !important;
    color: #459adf !important;
}

.tab-header{
    color: #343a40 !important;
}

.back-btn{
    background-color: #ffffff !important;
    color: #459adf !important;
}

</style>

<template>
    <div>
        <div class="card-body bg-white pt-2" v-if="this.referral">

            <div class="row mt-3">
                <router-link
                :to="{ name: 'caseReferrals' }"
                class="back-btn pl-3 pr-3">
                    <i class="fas fa-arrow-left"></i>
                </router-link>

                <h5>        
                    {{ this.referral.referral_source.name }} / {{ this.referral.referral_date | myDate  }}
                </h5>
                <span @click="showEditReferralModal"
                    id='clickableAwesomeFont' class="ml-5">
                        <i class="fa fa-edit blue"></i>
                </span>
            </div>

            <div class="row m-3">
                <div class="col mb-4">
                    <h6 class="card-subtitle mb-2 text-muted">Referral Source</h6>
                    <div class="ml-4">
                        <li>{{ this.referral.referral_date }}</li>
                        <li>{{ this.referral.referral_source.name }}</li>
                        <li>{{ this.referral.referring_person_name }}</li>
                        <li>{{ this.referral.referring_person_email }}</li>
                        <li>{{ this.referral.casee.number }}</li>
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

            <hr>
            <h5>Beneficiary Statuses</h5>
            <ul v-if="caseeBeneficiaries">
                <li v-for="caseBeneficiary in caseeBeneficiaries" :key="caseBeneficiary.id">
                    {{ caseBeneficiary.name }}
                </li>
            </ul>

            <hr>
            <h5>Records</h5>
            <div class="row m-3">
				<button class="btn btn-success btn-sm mr-2">
					<i class="fas fa-plus-circle"></i><span><b> Activity</b></span>
				</button>
                <button class="btn btn-success btn-sm mr-2" @click="showCreateEmergencyModal">
					<i class="fas fa-plus-circle"></i><span><b> Emergency</b></span>
				</button>
				<button class="btn btn-secondary btn-sm">
					<i class="fas fa-sync-alt"></i>
				</button>
			</div>
            
            <table class="table table-hover table-striped border">
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
                                    <span>{{ emergency.emergency_date | myDate }}</span>
                                    <span>{{ emergency.user.name }}</span>
                                    <a class="clickable" @click="showEditEmergencyModal(emergency)">
                                        <i class="fas fa-pencil-alt"></i>
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
<br><br>


        </div>
            <EmergencyModal 
            :v-if="selectedEmergency.id"
            :emergencyEditMode='emergencyEditMode' 
            :selectedEmergency='selectedEmergency'
            v-on:recordsChanged="getReferral()">
            </EmergencyModal>
    </div>
</template>
<script>
import router from '../../router'
import EmergencyModal from './EmergencyModal'
import Multiselect from 'vue-multiselect'
import axiosMixin from '../../mixins/axiosMixin'

export default {
    mixins: [axiosMixin],
    props: {
        referralId: {
            // type: String,
            required: true
        }
    },
    components: {
        EmergencyModal,
        Multiselect,
    },
    data(){
        return {
            referral: "",
            caseeBeneficiaries: [],
            selectedEmergency: "",
            emergencyEditMode: false,
        }
    },
    methods: {


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
    },
    created(){
        this.getReferral(this.referralId);
        this.getCaseebeneficiaries(this.$route.params.caseeId);
        Fire.$on('referralChanged', () => {
			this.getReferral(this.referralId);
		});
    }
}
</script>