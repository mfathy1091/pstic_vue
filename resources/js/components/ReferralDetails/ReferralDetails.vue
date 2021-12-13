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
            
            <section>
                <ul class="nav">
                    <li v-for="record in referral.records" :key="record.id" class="nav-item">
                        <router-link
                        :to="{
                            name: 'RecordDetails',
                            params: {recordId: record.id}
                            }"
                        class="nav-link tab-header">
                            <span>{{ record.month.name }}</span>
                            <span v-show="record.status.name == 'Inactive'" class="badge badge-pill badge-secondary">{{ record.status.name }}</span>
                            <span v-show="record.status.name == 'Active'" class="badge badge-pill badge-success">{{ record.status.name }}</span>
                            <span v-show="record.status.name == 'Closed'" class="badge badge-pill badge-dark">{{ record.status.name }}</span>
                            <span v-show="record.is_new == 1" class="badge badge-pill badge-info">New</span>
                        </router-link>
                    </li>
                </ul>
                <router-view :key="$route.path"></router-view>
            </section>
        </div>
    </div>
</template>
<script>
import router from '../../router'

export default {
    props: {
        referralId: {
            // type: String,
            required: true
        }
    },
    data(){
        return {
            referral: "",
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

        },
        goBack(){
            router.go(-1);
        }
    },
    created(){
        this.getReferral();
    }
}
</script>