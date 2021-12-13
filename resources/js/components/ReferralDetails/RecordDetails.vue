<template>
    <div>
        <div class="card-body bg-white pt-2 pl-2 p-0" v-if="record">
            <div class="row m-3">
				<button class="btn btn-success btn-sm mr-2" @click="showCreateEmergencyModal">
					<i class="fas fa-plus-circle"></i><span><b> Emergency</b></span>
				</button>
				<button class="btn btn-secondary btn-sm" @click="getEmergencies">
					<i class="fas fa-sync-alt"></i>
				</button>
			</div>
            
            <p v-show="!record.emergencies.length" class="ml-3 text-secondary font-italic">
                There are no emergencies!
            </p>

            <table v-show="record.emergencies.length" class="table table-hover table-striped table-sm border">
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

            <EmergencyModal 
            :v-if="selectedEmergency.id"
            :emergencyEditMode='emergencyEditMode' 
            :selectedEmergency='selectedEmergency'
            :recordId='record.id' 
            v-on:recordsChanged="getEmergencies()">
            </EmergencyModal>
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
            record: "",
            emergencies: [],
            selectedEmergency: "",
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