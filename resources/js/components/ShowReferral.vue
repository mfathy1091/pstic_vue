<template>
    <div>  
        <!-- Referral Details Section -->
        <div class="card mt-3" v-if="this.referral">
            <div class="card-header">
                <h5 class="m-0">
                    Referral Details
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

        <!-- Records Section -->
        <Records :referral="referral" />

    </div>
</template>

<script>
import Form from 'vform'
import Records from './Records.vue'

export default {
    name: 'ShowReferral',
    components: {
        Records
    },

    data() {
        
        return{
            referral: '',

            beneficiaryEditMode: false,
            
            recordBeneficiaries: [],
            recordBeneficiariesForm: new Form({
                record_id: '',
            }),
        }
    },
    methods: {
        getReferral(){
			this.$Progress.start();
			axios.get("/api/referrals/"+this.$route.params.id)
            .then(({data}) => {
                this.referral = data.data
            });
			this.$Progress.finish();
        },

		showCreateBeneficiaryModal(){
			this.beneficiaryEditMode = false;
			// this.form.reset()
			$('#beneficiaryModal').modal('show')
		},
    },

    created (){
        this.getReferral()
    }
}
</script>