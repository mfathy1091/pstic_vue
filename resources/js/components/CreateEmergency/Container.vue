<style scoped>
.clickable {
    cursor: pointer
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>  
        <!-- Referral Details Section -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="m-0">
                    Create New Emergency
                </h5>
            </div>

            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            Date / Time
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="activity_date" class="form-label">Date</label>
                                <input id="activity_date" type="text" name="activity_date" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD">
                                <!-- <HasError :form="referralForm" field="activity_date" /> -->
                            </div>
                            <div class="form-group">
                                <label for="activity_time" class="form-label">Time</label>
                                <input id="activity_time" type="text" name="activity_time" class="form-control" autocomplete="off" placeholder="22:00">
                                <!-- <HasError :form="referralForm" field="activity_time" /> -->
                            </div>
                            <div class="form-group">
                                <label for="location" class="form-label">Location</label>
                                <select name="location" id="location" class="form-control">
                                    <option value='0' disabled>Choose</option>
                                    <option value='0'>client home</option>
                                    <option value='0'>Your office</option>
                                    <option value='0'>Community center</option>
                                </select>
                                <!-- <HasError :form="referralForm" field="location" /> -->
                            </div>
                            <div class="form-group">
                                <label for="location" class="form-label">Emergency Type</label>
                                <select name="location" id="location" class="form-control">
                                    <option value='0' disabled>Choose</option>
                                    <option v-for="emergencyType in emergencyTypes" :key="emergencyType.id">{{ emergencyType.name }}</option>
                                </select>
                                <!-- <HasError :form="referralForm" field="location" /> -->
                            </div>
                        </div>
                        <div class="col">
                            3 of 3
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            Services
                        </div>
                        <div class="col-6">
                            <ul>
                                <li v-for="recordBeneficiary in record.record_beneficiaries" :key="recordBeneficiary.id">
                                    <span>{{ recordBeneficiary.individual.name}}</span>
                                    <div class="form-group" v-if="servicetypes" >
                                        <multiselect :id="recordBeneficiary.id"
                                        v-model="recordBeneficiaryForm.servicetypes"
                                        :options="servicetypes"
                                        :multiple="true"
                                        :close-on-select="false"
                                        :clear-on-select="false" 
                                        :preserve-search="true"
                                        placeholder="Pick some"
                                        label="name" 
                                        track-by="name" 
                                        :preselect-first="true">
                                            <!-- <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template> -->
                                        </multiselect>
                                        <!-- <pre class="language-json"><code>{{ value  }}</code></pre> -->
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col">
                            3 of 3
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import axiosMixin from '../../mixins/axiosMixin'	

export default {
	mixins: [axiosMixin],
    name: 'CreateActivity',
    components: {
        Multiselect,
    },
    props: {
        recordId: {
            type: String,
            required: true
        }
    },
    data() {
        return{
            record: {},
            emergencyTypes: [],
            recordBeneficiaryForm: new Form({
				id: '',
				emergencyTypes: [],
			})

        }
    },
    methods: {
        getRecord() {
			this.$Progress.start();
			axios.get('/api/records/' + this.recordId)
			.then((data) => {
				// success
                this.record = data.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
                console.log(e);
			})
		},

        getEmergencyTypes() {
			this.$Progress.start();
			axios.get('/api/emergency-types/')
			.then((data) => {
				// success
                this.emergencyTypes = data.data.data;
				this.$Progress.finish();
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
                console.log(e);
			})
		},

    },

    created (){
        this.getRecord();
        this.getEmergencyTypes();
    },

}
</script>