<style scoped>
.clickable {
    cursor: pointer
}
</style>
<template>

    <div>
        <div class="card">
            <div class="row m-3">
				<button class="btn btn-success btn-sm mr-2" @click="showCreateHousingReferralModal">
				<!-- <button class="btn btn-success btn-sm mr-2" @click="showCreateRoleModal" v-if="$can('role_create')"> -->
					<i class="fas fa-plus-circle"></i><span><b> Referral</b></span>
				</button>
				<button class="btn btn-secondary btn-sm" @click="getCaseeHousingReferrals(caseeId)">
					<i class="fas fa-sync-alt"></i>
				</button>
			</div>

            <p v-show="!caseeHousingReferrals.length" class="font-italic ml-3">This case has no referrals!</p>

            <table v-show="caseeHousingReferrals.length" class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>Source</th>
                        <th>Referral Date</th>
                        <th>Grant Status</th>
                        <th>Grant Amount</th>
                        <th>Assigned Housing Advocate</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody v-if="this.caseeHousingReferrals">
                    <tr v-for="referral in this.caseeHousingReferrals" :key="referral.id">
                        <td>{{ referral.referral_source.name  }}</td>
                        <td>{{ referral.referral_date | myDate }}</td>
                        <td>
                            <span v-show="referral.grant_status.name == 'Accepted'" class="badge badge-pill badge-success">{{ referral.grant_status.name }}</span>
                            <span v-show="referral.grant_status.name == 'Rejected'" class="badge badge-pill badge-danger">{{ referral.grant_status.name }}</span>
                            <span v-show="referral.grant_status.name == 'Pending'" class="badge badge-pill badge-warning">{{ referral.grant_status.name }}</span>
                        </td>
                        <td>{{ referral.grant_amount }}</td>
                        <td>{{ referral.assigned_housing_advocate.name }}</td>

                        <td>
                            <!-- <router-link
                            :to="{name: 'referralDetails', params: { caseeId: caseeId, referralId: referral.id }}"
                            class="fa fa-eye blue align-middle mr-2">
                            </router-link> -->
                            <a class="clickable" @click="showEditHousingReferralModal(referral)">
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

		<HousingReferralModal v-if="casee" :caseeId="casee.id"
		:v-if="selectedHousingReferral.id"
		:housingReferralEditMode='housingReferralEditMode' 
		:selectedHousingReferral='selectedHousingReferral' 
		v-on:caseeHousingReferralsChanged="getCasee(caseeId)">
		</HousingReferralModal>

    </div>
</template>



<script>
import Form from 'vform'
import HousingReferralModal from './HousingReferralModal'
import Multiselect from 'vue-multiselect'
import router from '../../../router'
import axiosMixin from '../../../mixins/axiosMixin'

export default {
    name: 'caseHousingReferrals',
    components: {
        Multiselect,
        HousingReferralModal,
        
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
            casee: '',
            caseeHousingReferrals: [],
            housingReferralEditMode: false,
			selectedHousingReferral: {},
        }
    },
    methods: {
        
        showCreateHousingReferralModal(){
			this.housingReferralEditMode = false;
			this.selectedHousingReferral = {};
			$('#housingReferralModal').modal('show')
		},

        showEditHousingReferralModal(referral){
			this.housingReferralEditMode = true;
			this.selectedHousingReferral = referral;
			$('#housingReferralModal').modal('show')
		},

    },
    created() {
        this.getCasee(this.caseeId);
        this.getCaseeHousingReferrals(this.caseeId);

        Fire.$on('caseeHousingReferralsChanged', () => {
            this.getCaseeHousingReferrals(this.caseeId);
		});
    }
}
</script>