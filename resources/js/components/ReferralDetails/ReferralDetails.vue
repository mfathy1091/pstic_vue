<template>
<div>

    
    <section class="card mt-3" v-if="this.referral">
        <div class="card-header">
            <h5 class="m-0">
                Referral Details
            <span @click="showEditReferralModal"
            id='clickableAwesomeFont' class="ml-5">
                <i class="fa fa-edit blue"></i>
            </span>
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
        </div>
    </section>
    
    <section>
        <ul class="nav nav-pills nav-fill">
            <li v-for="record in referral.records" :key="record.id" class="nav-item">
                <router-link
                :to="{
                    name: 'RecordDetails',
                    params: {recordId: record.id}
                    }"
                class="nav-link active">
                <span>{{ record.month.name }}</span>
                </router-link>
            </li>
        </ul>
        <router-view :key="$route.path"></router-view>
    </section>
</div>
</template>
<script>
export default {
    props: {
        referralId: {
            type: String,
            required: true
        }
    },
    data(){
        return {
            referral: {},
        }
    },
    methods: {
        getReferral(){			
			this.$Progress.start();
			axios.get("/api/referrals/"+this.referralId)
            .then(({data}) => (this.referral = data.data));
			this.$Progress.finish();
		},

        showEditReferralModal(){

        }
    },
    created(){
        this.getReferral();
    }
}
</script>