<template>
<div>
    <section>

        <h3>Records</h3>
        <div class="card">
            <div class="card-body" v-if="record">
            </div>
                <!-- Activities Section -->
                <Activities 
                :v-if="record"
                :record='record' 
                v-on:recordsChanged="getReferral()">
                </Activities>
        </div>
    </section>


</div>

</template>
<script>
import Form from 'vform'
import Activities from '../ReferralDetails/Activities.vue'
import Multiselect from 'vue-multiselect'
export default {
    props: {
        referralId: {
            type: String,
            required: true
        },
        recordId: {
            type: String,
            required: true
        },
    },
    components: {
        Activities,
        Multiselect,
    },
    data(){
        return {
            record: {},
        }
    },
    methods: {
        getRecord(){
            this.$Progress.start();
			axios.get("/api/records/"+this.recordId)
            .then(({data}) => (this.record = data.data));
			this.$Progress.finish();
        }
    },
    created(){
        this.getRecord();
    }
}
</script>