<template>
    <section>
        <h1>Records</h1>
            <span>{{ record.month.name }}</span>
            <span v-show="record.status.name == 'Inactive'" class="badge badge-pill badge-secondary">{{ record.status.name }}</span>
            <span v-show="record.status.name == 'Active'" class="badge badge-pill badge-success">{{ record.status.name }}</span>
            <span v-show="record.status.name == 'Closed'" class="badge badge-pill badge-dark">{{ record.status.name }}</span>
            <span v-show="record.is_new == 1" class="badge badge-pill badge-info">New</span>
    </section>
</template>
<script>
import store from "../../store/index.js"
export default {
    props:{
        referralId: {
            type: String,
            required: true,
        },
        recordId: {
            type: String,
            required: true,
        }
    },
    mehods:{
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
    }
}
</script>