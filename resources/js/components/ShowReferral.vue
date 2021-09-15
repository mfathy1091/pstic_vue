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
                            <li>{{ this.referral.referral_source.name }}</li>
                            <li>{{ this.referral.referring_person_name }}</li>
                            <li>{{ this.referral.referring_person_email }}</li>
                        </div>
                    </div>
                    <div class="col mb-4">
                        <h6 class="card-subtitle mb-2 text-muted">Reason of Referral</h6>
                        <div class="ml-4">
                            <!-- <?php $reasons = $referral->reasons; ?>
                            @foreach ($reasons as $reason)
                                <li>{{ $reason->name }}</li>
                            @endforeach -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return{
            referral: '',
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
    },

    created (){
        this.getReferral()
    }
}
</script>