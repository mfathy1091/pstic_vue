<style scoped>
.badge{
	font-size: 0.7rem;
	margin-left: 2px;
	
}

.root-div{
    background-color: #f4f6f9 !important;
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
	<div class="root-div">
        <div class="card-body bg-white">
            <h5>December 2021</h5>
                <line-chart :data="{'November 2021': 50, '207-04': 20, '2017-05': 70}"></line-chart>
            <hr>
        </div>
        <br class="bg-secondary">
        <div class="card bg-white">
            <div class="card-body">
                <h5>December 2021</h5>
                <line-chart :data="{'November 2021': 50, '207-04': 20, '2017-05': 70}"></line-chart>
            <hr>
            </div>
        </div>
        <div class="card">
            fgfjg
            jfghfgjhf
        </div>
    </div>
</template>
<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import router from '../../router'
import axiosMixin from '../../mixins/axiosMixin'
// import { Line } from 'vue-chartjs'

export default {
    // extends: Line,
    mounted () {
        // this.renderChart(data, options)
    },
	components: { 
		Multiselect,
	},
    mixins: [axiosMixin],

	data() {
		return {
            data: [
                    {name: 'Workout', data: {'2017-01-01': 3, '2017-01-02': 4}},
                    {name: 'Call parents', data: {'2017-01-01': 5, '2017-01-02': 3}}
                ],
		}
	},
	methods: {


	},

    computed:{
        currentUser: {
            get(){
                return this.$store.state.currentUser.user;
            }
        }
    },
    watch: {
        currentUser (n, o) {
            // this.filter.user_id = _.cloneDeep(n).id;
        }
    },

	created() {

	}
}
</script>