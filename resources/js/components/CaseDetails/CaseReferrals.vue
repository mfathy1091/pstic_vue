<style scoped>

</style>
<template>
    <div class="card-body">
        <div class="row ml-2">
            <button class="btn btn-success btn-sm mr-2" @click="showCreateReferralModal">
            <!-- <button class="btn btn-success btn-sm mr-2" @click="showCreateRoleModal" v-if="$can('role_create')"> -->
                <i class="fas fa-plus-circle"></i><span><b> Referral</b></span>
            </button>
            <button class="btn btn-secondary btn-sm" @click="getCaseeReferrals(caseeId)">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>

        <div class="row mt-3">
            <p v-show="!caseeReferrals.length" class="font-italic ml-5">This case has no referrals!</p>

            <table v-show="caseeReferrals.length" class="border table table-hover table-sm">
                <thead>
                    <tr>
                        <th>Source</th>
                        <th>Referral Date</th>
                        <th>Current Month Status</th>
                        <th>Assigned Worker</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody v-if="this.caseeReferrals">
                    <tr v-for="referral in this.caseeReferrals" :key="referral.id">
                        <td>{{ referral.referral_source.name  }}</td>
                        <td>{{ referral.referral_date | myDate }}</td>
                        <td>
                            <div class="list-unstyled">
                                <li v-for="(record, i) in referral.records" :key="record.id">
                                    <div v-show="i==0">
                                        <span>{{ record.month.name }}</span>
                                        <span v-show="record.status.name == 'Inactive'" class="badge badge-pill badge-secondary">{{ record.status.name }}</span>
                                        <span v-show="record.status.name == 'Active'" class="badge badge-pill badge-success">{{ record.status.name }}</span>
                                        <span v-show="record.status.name == 'Closed'" class="badge badge-pill badge-dark">{{ record.status.name }}</span>
                                        <span v-show="record.is_new == 1" class="badge badge-pill badge-info">New</span>
                                    </div>

                                </li>
                            </div>

                        </td>
                        <td>{{ referral.current_assigned_psw.name }}</td>

                        <td>
                            <router-link
                            :to="{name: 'referralDetails', params: { caseeId: caseeId, referralId: referral.id }}"
                            class="fa fa-eye blue align-middle mr-2">
                            </router-link>
                            <a class="clickable" @click="showEditReferralModal(referral)">
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

		<ReferralModal
		:v-if="selectedReferral.id"
		:referralEditMode='referralEditMode' 
		:selectedReferral='selectedReferral' 
		v-on:referralsChanged="getCasee()">
		</ReferralModal>

    </div>
</template>

<script>
import Form from 'vform'
import ReferralModal from './ReferralModal'
import Multiselect from 'vue-multiselect'
import router from '../../router'
import axiosMixin from '../../mixins/axiosMixin'

export default {
    name: 'caseeReferrals',
    components: {
        Multiselect,
        ReferralModal,
        
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
            referralEditMode: false,
			selectedReferral: {},
        }
    },
    methods: {

        showCreateReferralModal(){
			this.referralEditMode = false;
			this.selectedReferral = {};
			$('#referralModal').modal('show')
		},

        showEditReferralModal(referral){
			this.referralEditMode = true;
			this.selectedReferral = referral;
			$('#referralModal').modal('show')
		},

    },
    created() {
        this.getCaseeReferrals(this.caseeId);

        Fire.$on('caseebeneficiariesChanged', () => {
            this.getCasee(this.caseeId);
        });

        Fire.$on('caseeReferralsChanged', () => {
            this.getCaseeReferrals(this.caseeId);
		});
    }
}
</script>