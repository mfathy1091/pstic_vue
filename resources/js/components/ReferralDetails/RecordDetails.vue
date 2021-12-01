<template>
<div>
    <section>

        <h3>Records</h3>
        <div class="card">
            <div class="card-body">
                <span><b>{{ record.month.name }}</b></span>
            </div>
        </div>
    </section>

    <!-- Activities Section -->
    <Activities 
    :v-if="currentRecord.id"
    :selectedRecord='currentRecord' 
    v-on:recordsChanged="getReferral()">
    </Activities>
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