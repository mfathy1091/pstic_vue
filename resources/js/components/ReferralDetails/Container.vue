<template>
<div>
    <section>
        <h1>{{ referral.referral_date }}</h1>
    </section>
    <section>
        <h2>Records in {{ referral.referral_date }}</h2>
        <div>
            <div v-for="record in referral.records" :key="record.id" class="card-body">
                <router-link
                :to="{
                    name: 'RecordDetails',
                    params: {recordId: record.id}
                    }">
                <span>{{ record.month.name }}</span>
                </router-link>
            </div>
        </div>
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
		}
    },
    created(){
        this.getReferral();
    }
}
</script>