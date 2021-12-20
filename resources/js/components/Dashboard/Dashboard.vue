<template>
    <div>
        Hello from the dashboard
    </div>
</template>

<script>

export default {
	data() {
		return {
			entries: [],

		}
	},
	methods: {


		getEntries() {
			this.$Progress.start();
			axios.get('/api/test')
			.then((res) => {				
				this.$Progress.finish();
                this.entries = res.data.data;
			})
			.catch(() => {
				// error
				this.$Progress.fail();
			})
		},

	},
	
	created() {
		this.getEntries();
	}
}
</script>