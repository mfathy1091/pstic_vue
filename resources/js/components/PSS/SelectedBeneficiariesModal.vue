<template>
    <div class="modal fade" id="selectedBeneficiariesModal" tabindex="-1" aria-labelledby="selectedBeneficiariesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectedBeneficiariesModalLabel">Add Beneficairy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
						<div class="input-group">
							<input v-model="searchForm.beneficiary_name" type="search" class="form-control form-control-sidebar" aria-label="Search">
							<div class="input-group-append">
								<button type="sumbit" class="btn btn-sidebar" @click="search">
									<i class="fas fa-search fa-fw"></i>
								</button>
							</div>
						</div>

						<div>
							<label class="typo__label">Select with search</label>
							<multiselect v-model="selectedBeneficiary" :options="beneficiaries" placeholder="Select one" label="name" track-by="name" @search-change="search"></multiselect>
							<pre class="language-json"><code>{{ selectedBeneficiary  }}</code></pre>
						</div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" @click="sendToParent">Add</button>
                    </div>
            </div>
        </div>
		
    </div>
</template>
<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import axiosMixin from '../../mixins/axiosMixin'

export default {
	mixins: [axiosMixin],
	components: { Multiselect },
    props:{
        psIntakeEditMode: Boolean,
        selectedPsIntake: Object,
    },

	data() {
		return {
			selectedBeneficiary: '',
			beneficiaries: [],
			searchForm : new Form({
				beneficiary_name: '',
            }),
		}
	},


	methods: {
		sendToParent(){
			Fire.$emit('beneficiarySelected', this.selectedBeneficiary);
		},

		async search(){
            this.$Progress.start();
            try{
                const response = await axios.get("/api/beneficiaries/searchBeneficiaries", { params: { name: this.searchForm.beneficiary_name } });
                this.beneficiaries = response.data.data;
                this.$Progress.finish();
            }catch (error){
                this.$Progress.fail();
                console.error(error);
            }
            
        },

	},

}
</script>