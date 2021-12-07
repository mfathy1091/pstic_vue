<style scoped>
.badge{
	font-size: 0.7rem;
	margin-left: 2px;
	
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
	<div class="container-fluid">
		<div class="row mt-5">
			<div class="col-md-12">

				<div class="card">
				<div class="card-header">

                    <h3 class="card-title">Emergencies</h3>
            

				</div>
				<!-- /.card-header -->
				<div class="card-body table-responsive p-0">
					<table class="table table-hover text-nowrap align-middle">
						<thead>
							<tr>
								<th>Case / File #</th>
                                <th>Main Beneficiary</th>
                                <th>Emergency Date</th>
								<th>Emergency Type</th>
								<th>Worker</th>
							</tr>
						</thead>
						<tbody>
								<tr v-for="emergency in emergencies" :key="emergency.id">
									<td class="align-middle"></td>
                                    <td class="align-middle"></td>
                                    <td>
                                        {{ emergency.emergency_date }}
                                        <!-- <li v-for="individual in casee.beneficiaries" :key="individual.id">
										{{ individual.name }}
                                        </li> -->
                                    </td>
									<td>{{ emergency.emergency_type.name }}</td>
									<td>{{ emergency.user.name }}</td>
								</tr>
						</tbody>
					</table>
				</div>
				<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
		</div>

	</div>
</template>
<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import router from '../../router'

export default {
	components: { 
		Multiselect,
	},

	data() {
		return {
			emergencies: [],
		}
	},
	methods: {

        async getEmergencies(){
            this.$Progress.start();
            try{
                const response = await axios.get("/api/emergencies");
                this.emergencies = response.data.data;
                this.$Progress.finish();
            }catch (error){
                this.$Progress.fail();
                console.error(error);
            }
        },
	},



	created() {
        this.getEmergencies();
		
	}
}
</script>