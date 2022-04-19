<template>
    <div>
        <h5 class="mt-5 mb-3">Activities</h5>
        <div class="card-tools">
            <button class="btn btn-success" @click="showCreateActivityModal">
                Add New
            </button>
        </div>
        <div>
            <ul v-for="(activity, i) in activities" :key="i">{{ activity.activity_date }}</ul>
        </div>


        <!-- Modal -->
		<ActivityModal 
		:v-if="selectedActivity.id"
		:activityEditMode='activityEditMode' 
		:selectedActivity='selectedActivity' 
		v-on:recordsChanged="getActivities()">
		</ActivityModal>
    </div>
</template>

<script>
import Form from 'vform'
import axiosMixin from '../../../../mixins/axiosMixin'
import ActivityModal from './ActivityModal'

export default {
    mixins: [axiosMixin],
    props:{
        selectedRecord: Object,
    },
    components: { 
			ActivityModal,
	},
    data() {
		return {
			activities: [],
            selectedActivity: {},
            activityEditMode: false,
		}
	},
    methods: {
        getActivities(){
			this.$Progress.start();
			axios.get('/api/activities/')
			.then((data) => {
                this.activities = data.data.data;
                
				Fire.$emit('recordsChanged');
				this.$Progress.finish();
			})
			.catch(() => {
				this.$Progress.fail();
			})
		},

        showCreateActivityModal(){
			this.activityEditMode = false;
			this.selectedActivity = {};
			$('#activityModal').modal('show')
		},


		showEditActivityModal(nationality){
			this.activityEditMode = true;
			this.selectedActivity = nationality;
			$('#activityModal').modal('show')
		},
    },
    created(){
        this.getActivities();
    }
}
</script>