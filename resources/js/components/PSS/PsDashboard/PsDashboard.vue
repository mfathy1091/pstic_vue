
<template>
	<div class="p-0">
        <div class="card-body">
                <div class="row mt-3 table-responsive m-0">
                    <div class="card-body bg-white">
                        <h5>History</h5>
                            <column-chart :colors="['#6cb2eb', '#ffed4a']" :messages="{empty: 'No data'}" :download="true" :legend="true" :stacked='true' :data="chartData" />
                    </div>
                </div>
            </div>


    </div>
</template>
<script>
import Multiselect from 'vue-multiselect'
import axiosMixin from '../../../mixins/axiosMixin'

export default {
	components: { 
		Multiselect,
	},
    mixins: [axiosMixin],

	data() {
		return {
            chartData : [
                // {name: 'New', data: {'Jan': 3, 'Feb': 4}},
                // {name: 'Ongoing', data: {'Jan': 5, 'Feb': 3}},
                ],
		}
	},
	methods: {  
        getPsIntakesMonthlyCountsByStatuses()
        {
            this.$Progress.start();
            this.$store.state.main.showLoading = true;
			axios.get('/api/ps-intakes/monthly-counts-by-statuses')
			.then((response) => {
				// success
				//this.PsIntakesCountsByStatuses = response.data;
                this.chartData = response.data;
				this.$Progress.finish();
                this.$store.state.main.showLoading = false;
			})
			.catch((e) => {
				// error
				this.$Progress.fail();
                this.$store.state.main.showLoading = false;
				console.log(e);
			})
        },
    },

	created() {
        this.getPsIntakesMonthlyCountsByStatuses();
	}
}
</script>