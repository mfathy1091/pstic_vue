<template>
    <div>

    </div>
</template>

<script>
import Form from 'vform'
import axiosMixin from '../../mixins/axiosMixin'
import EmergencyModal from './EmergencyModal'

export default {
    mixins: [axiosMixin],
    props:{
        recordId: String,
		required: true,
    },
    components: { 
			EmergencyModal,
	},
    data() {
		return {
			record: {},
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


    },
    created(){
        this.getRecord();
		
    }
}
</script>