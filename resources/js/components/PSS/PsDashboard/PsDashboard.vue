
<template>
	<div class="p-0">
        <div class="card-body">
            <div class="form-inline mr-2 ml-2 mt-3">
                <select v-model="filter.startMonth" @change="getPsIntakesMonthlyCountsByStatuses" class="custom-select m-1">
                    <option value='2022-01-01'>January 2022</option>
                    <option value='2022-02-01'>February 2022</option>
                    <option value='2022-03-01'>March 2022</option>
                    <option value='2022-04-01'>April 2022</option>
                    <option value='2022-05-01'>May 2022</option>
                    <option value='2022-06-01'>June 2022</option>
                    <option value='2022-07-01'>July 2022</option>
                    <option value='2022-08-01'>August 2022</option>
                    <option value='2022-09-01'>September 2022</option>
                    <option value='2022-10-01'>October 2022</option>
                    <option value='2022-11-01'>November 2022</option>
                    <option value='2022-12-01'>December 2022</option>
                </select>

                <span>to</span>

                <select v-model="filter.endMonth" @change="getPsIntakesMonthlyCountsByStatuses" class="custom-select m-1">
                    <option value='2022-01-01'>January 2022</option>
                    <option value='2022-02-01'>February 2022</option>
                    <option value='2022-03-01'>March 2022</option>
                    <option value='2022-04-01'>April 2022</option>
                    <option value='2022-05-01'>May 2022</option>
                    <option value='2022-06-01'>June 2022</option>
                    <option value='2022-07-01'>July 2022</option>
                    <option value='2022-08-01'>August 2022</option>
                    <option value='2022-09-01'>September 2022</option>
                    <option value='2022-10-01'>October 2022</option>
                    <option value='2022-11-01'>November 2022</option>
                    <option value='2022-12-01'>December 2022</option>
                </select>
                
                <select v-model="filter.budget_id" @change="getPsIntakesMonthlyCountsByStatuses" class="custom-select m-1">
                    <option value=''>All Budgets</option>
                    <option value='1'>UNHCR CAIRO</option>
                    <option value='3'>BPRM</option>
                </select>
            </div>
                <div class="row mt-3 table-responsive m-0">
                    <div class="card-body bg-white">
                        <h5>Active Cases</h5>
                            <column-chart :colors="['#6cb2eb', '#ffed4a']" :messages="{empty: 'No data'}" :download="true" :legend="true" :stacked='true' :data="activeChart" />
                            <br>
                            <h5>Commulative</h5>
                            <line-chart label="Value" :messages="{empty: 'No data'}" max="20" :download="true" :legend="false" :stacked='true' :data="commulativeChart" />
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
            filter: {
                startMonth: '2022-01-01',
                endMonth: '2022-12-01',
                budget_id: '',
            },
		}
	},
	methods: {  

        getPsIntakesMonthlyCountsByStatuses()
        {
            this.$Progress.start();
            this.$store.state.main.showLoading = true;
			axios.get('/api/ps-intakes/monthly-counts-by-statuses', { params: this.filter} )
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