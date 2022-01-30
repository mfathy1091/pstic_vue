<style scoped>

</style>
<template>
    <div class="card-body">
        <div class="row ml-2">
            <button class="btn btn-success btn-sm mr-2" @click="showCreatePssIntake">
                <i class="fas fa-plus-circle"></i><span><b> PSS Intake</b></span>
            </button>
            <button class="btn btn-secondary btn-sm" @click="getCaseeReferrals(caseeId)">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>

        <h5 class="mt-2">This case is open with 2 Workers</h5>

        <div class="row mt-3 table-responsive">
            <p v-show="!caseeReferrals.length" class="font-italic ml-5">This case has no PSS Intakes!</p>

            <table v-show="caseeReferrals.length" class="border table table-hover table-sm table-striped">
                <thead>
                    <tr>
                        <th>Assigned Worker</th>
                        <th>Open / Close Date</th>
                        <th>Direct Beneficiaries</th>
                        <th>Indirect Beneficiaries</th>
                        <th>Source</th>
                        <th>Current Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody v-if="this.caseeReferrals">
                    <tr v-for="referral in this.caseeReferrals" :key="referral.id">
                        <td><span class="text-nowrap">{{ referral.current_assigned_psw.full_name }}</span></td>
                        <td>
                            <span class="text-nowrap">{{ referral.referral_date | myDateFull }}</span>
                            <br>
                            <span class="font-italic mx-auto">to</span>
                            <br>
                            <span class="text-nowrap" v-if="referral.close_date">{{ referral.close_date | myDateFull }}</span>
                            <span v-if="!referral.close_date">Now</span>
                        </td>
                        <td>
                            <div class="list-unstyled">
                                <li v-for="directBeneficiary in referral.direct_referral_beneficiaries" :key="directBeneficiary.id">
                                    <div>
                                        <span>{{ directBeneficiary.name }}</span>
                                    </div>
                                </li>
                            </div>
                        </td>
                        <td>
                            <div class="list-unstyled">
                                <li v-for="indirectBeneficiary in referral.indirect_referral_beneficiaries" :key="indirectBeneficiary.id">
                                    <div>
                                        <span>{{ indirectBeneficiary.name }}</span>
                                    </div>
                                </li>
                            </div>
                        </td>
                        <td>{{ referral.referral_source.name  }}</td>
                        <td>
                            <span v-show="referral.current_record.status.name == 'Inactive'" class="badge badge-pill badge-secondary">{{ referral.current_record.status.name }}</span>
                            <span v-show="referral.current_record.status.name == 'Active'" class="badge badge-pill badge-success">{{ referral.current_record.status.name }}</span>
                            <span v-show="referral.current_record.status.name == 'Closed'" class="badge badge-pill badge-dark">{{ referral.current_record.status.name }}</span>
                            <!-- <span v-show="referral.current_record.status.name != 'Closed'">
                                <span v-show="referral.current_record.is_new == 1" class="badge badge-pill badge-info">New</span>
                                <span v-show="referral.current_record.is_new == 0" class="badge badge-pill badge-warning">Ongoing</span>
                            </span> -->
                        </td>
                        <td>
                            <router-link
                            :to="{name: 'pssIntakeDetails', params: { caseeId: caseeId, referralId: referral.id }}"
                            class="fa fa-eye blue align-middle mr-2">
                            </router-link>
                            <a class="clickable" @click="showEditPssIntake(referral)">
                                <i class="fas fa-pencil-alt blue mr-2"></i>
                            </a>
                            
                            <a class="clickable">
                                <i class="fa fa-trash red"></i>
                            </a>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

		<PssIntakeModal
		:v-if="selectedPssIntake.id"
		:pssIntakeEditMode='pssIntakeEditMode' 
		:selectedPssIntake='selectedPssIntake' 
		v-on:referralsChanged="getCasee()">
		</PssIntakeModal>

    </div>
</template>

<script>
import Form from 'vform'
import PssIntakeModal from './PssIntakeModal'
import Multiselect from 'vue-multiselect'
import router from '../../../router'
import axiosMixin from '../../../mixins/axiosMixin'

export default {
    components: {
        Multiselect,
        PssIntakeModal,
        
    },
    mixins: [axiosMixin],
    props: {
        caseeId: {
            // type: Number, 
            required: true
        }
    },
    data(){
        return {
            caseeReferrals: [],
            pssIntakeEditMode: false,
			selectedPssIntake: {},
        }
    },
    methods: {

        showCreatePssIntake(){
			this.pssIntakeEditMode = false;
			this.selectedPssIntake = {};
			$('#pssIntakeModal').modal('show')
		},

        showEditPssIntake(referral){
			this.pssIntakeEditMode = true;
			this.selectedPssIntake = referral;
			$('#pssIntakeModal').modal('show')
		},

    },
    created() {
        this.getCaseeReferrals(this.caseeId);

        Fire.$on('caseeBeneficiariesChanged', () => {
            this.getCasee(this.caseeId);
        });

        Fire.$on('caseePssIntakesChanged', () => {
            this.getCaseeReferrals(this.caseeId);
		});
    }
}
</script>