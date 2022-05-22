
<template>
	<div class="p-0">
        <div class="card-body">
                <div class="row mt-3 table-responsive m-0">
                    <div class="card-body bg-white">
                        <h5>History</h5>
                            <column-chart :colors="['#6cb2eb', '#ffed4a']" :messages="{empty: 'No data'}" :download="true" :legend="true" :stacked='true' :data="activeChart" />
                            <br>
                            <h5>Commulative</h5>
                            <line-chart :messages="{empty: 'No data'}" max="20" :download="true" :legend="false" :stacked='true' :data="commulativeChart" />
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
            activeChart : [
                    {
                        name: 'New',
                        data: {} // {'Jan': 3, 'Feb': 4}
                    },
                    {
                        name: 'Ongoing', 
                        data: {}
                    },
                ],
            commulativeChart : {},
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
                this.activeChart[0].data = response.data.activeNewCounts;
                this.activeChart[1].data = response.data.activOngoingCounts;
                this.commulativeChart = response.data.commulativeCounts;
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