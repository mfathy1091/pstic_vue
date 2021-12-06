<template>
<div>
    <div class="card">
        <div class="card-body" v-if="record">
        
            <!-- Activities Section -->
            <Activities 
            :v-if="record"
            :record='record' 
            v-on:recordsChanged="getReferral()">
            </Activities>
            <section>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Emergencies</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="showCreateEmergencyModal">Add New</button>
                        </div>
                        
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Worker</th>
                                    <th>Modify</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="emergency in record.emergencies" :key="emergency.id">
                                    <td>{{ emergency.emergency_date | myDate }}</td>
                                    <td>{{ emergency.emergency_type.name }}</td>
                                    <td>{{ emergency.user.name }}</td>
                                    <td>
                                        <a class="clickable" @click="showEditEmergencyModal(emergency)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                
                                        <a class="clickable" @click="deleteEmergency(emergency.id)">
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
				

                <!-- Modal -->
                <EmergencyModal 
                :v-if="selectedEmergency.id"
                :emergencyEditMode='emergencyEditMode' 
                :selectedEmergency='selectedEmergency'
                :recordId='record.id' 
                v-on:recordsChanged="getEmergencies()">
                </EmergencyModal>
            </section>
        </div>
    </div>


</div>

</template>
<script>
import Form from 'vform'
import Activities from '../ReferralDetails/Activities.vue'
import EmergencyModal from './EmergencyModal'
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
        EmergencyModal,
        Multiselect,
    },
    data(){
        return {
            record: {},
            emergencies: [],
            selectedEmergency: {},
            emergencyEditMode: false,
        }
    },
    methods: {
        getRecord(){
            this.$Progress.start();
			axios.get("/api/records/"+this.recordId)
            .then(({data}) => (this.record = data.data));
			this.$Progress.finish();
        },
        getEmergencies(){
			this.$Progress.start();
			axios.get('/api/emergencies/')
			.then((data) => {
                this.emergencies = data.data.data;
                
				Fire.$emit('recordsChanged');
				this.$Progress.finish();
			})
			.catch(() => {
				this.$Progress.fail();
			})
		},

        showCreateEmergencyModal(){
			this.emergencyEditMode = false;
			this.selectedEmergency = {};
			$('#emergencyModal').modal('show')
		},

        showEditEmergencyModal(emergency){
			this.emergencyEditMode = true;
			this.selectedEmergency = emergency;
			$('#emergencyModal').modal('show')
		},


    },
    created(){
        this.getRecord();
        this.getEmergencies();

        Fire.$on('recordsChanged', () => {
			this.getRecord();
		});
    }
}
</script>