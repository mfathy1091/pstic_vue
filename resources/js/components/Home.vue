<template>
<div>
    <h1>All Referrals</h1>
    <div v-for="referral in referrals" :key="referral.id" class="card-body">
        <router-link :to="{name: 'referralDetails', params: {referralId:referral.id}}">
            {{ referral.referral_date }}
        </router-link>
    </div>
</div>
</template>
<script>
export default {
    data(){
        return {
            referrals: [],
        }
    },
    methods: {
        getReferrals(){
			this.$Progress.start();
			axios.get("/api/referrals")
			.then(({data}) => {
				this.referrals = data.data
			});
			this.$Progress.finish();
		},
    },

    created(){
        this.getReferrals();
    }
}
</script>