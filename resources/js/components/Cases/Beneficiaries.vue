<style scoped>

</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
	<div>

        <div class="row mt-3 mb-3 pl-3"> 
            <h5>Beneficiaries</h5>
        </div>

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'workerPssReferrals' }"
                            class="nav-link tab-header" active-class="active">   
                            Beneficiaries
                        </router-link>
                    </li>
                </ul>
            </div>
            <!-- <router-view :key="$route.path"></router-view> -->

            <div class="card-body align-top">
                <div class="form-inline ml-2">
                    <button class="btn btn-success btn-sm mr-2" @click="showCreateCaseeModal">
                        <i class="fas fa-plus-circle"></i><span><b> Individual</b></span>
                    </button>
                    <button class="btn btn-success btn-sm mr-2" @click="showCreateCaseeModal">
                        <i class="fas fa-plus-circle"></i><span><b> Family</b></span>
                    </button>
                    <button class="btn btn-secondary btn-sm mr-5" @click="getBeneficiaries(casee)">
                        <i class="fas fa-sync-alt"></i>
                    </button>

                    <div class="input-group">
                        <input v-model="searchForm.fileNumber" type="search" class="form-control form-control-sidebar" aria-label="Search">
                        <div class="input-group-append">
                            <button type="sumbit" class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>

                    <select v-model="filter.status_id" @change="getBeneficiaries" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                        <option value="-1" disabled>Filter by...</option>
                        <option value="1">Cases with PSS Referrals</option>
                        <option value="2">Cases with Housing Referrals</option>
                    </select>
                </div>


                <div class="row mt-3 table-responsive" v-if="beneficiaries">	
                    <table class=" table table-hover border table-sm">
                        <thead>
                            <tr>
                                <th>File Number</th>
                                <th>Individual Number</th>
                                <th>Name</th>
                                <!-- <th># of PSS Intake</th>
                                <th># of Housing Intake</th> -->
                            </tr>
                        </thead>
                        <tbody>
                                <tr v-for="beneficiary in beneficiaries" :key="beneficiary.id" @click="goToCaseDetails(casee)">
                                    <td class="align-middle">{{ beneficiary.file_number }}</td>
                                    <td class="align-middle">{{ beneficiary.file_individual_number }}</td>
                                    <td class="align-middle">{{ beneficiary.name }}</td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>




		<CaseModal 
		:v-if="selectedCasee.id"
		:caseeEditMode='caseeEditMode' 
		:selectedCasee='selectedCasee' 
		v-on:beneficiariesChanged="getBeneficiaries()">
		</CaseModal>

        


	</div>
</template>
<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import router from '../../router'
import CaseModal from './CaseModal'


export default {
	components: { 
		Multiselect,
        CaseModal,
	},

	data() {
		return {
			caseeEditMode: false,
            selectedCasee: '',
			beneficiaries: [],
            searchText: '',
            searchForm: {
                name: '',
            },
            format: '',
            regex: '^',
            mask: 'XXX-XXCXXXXX',
            filter: {
                is_new: '-1',
                status_id: '-1',
                month_id: '-1',
                user_id: 'current_user'
            }
		}
        
	},
	methods: {

        goToCaseDetails(casee){
            router.push({ name: 'caseDetails', params: {caseeId: casee.id} })
        },

        getBeneficiaries(){
			this.$Progress.start();
            axios({
            method: 'get',
            url: '/api/beneficiaries',
            data: {

            }
            })
			.then(({data}) => {
				this.beneficiaries = data.data
			});
			this.$Progress.finish();
		},

        async search(){
            this.$Progress.start();
            try{
                const response = await axios.get("/api/beneficiaries/searchBeneficiaries", { params: { name: this.searchForm.name } });
                this.beneficiaries = response.data.data;
                this.$Progress.finish();
            }catch (error){
                this.$Progress.fail();
                console.error(error);
            }
            
        },
        
        showCreateCaseeModal(){
			this.caseeEditMode = false;
			this.selectedCasee = {};
			$('#caseeModal').modal('show')
            console.log('hi')
		},


		showEditcaseeModal(user){
			this.caseeEditMode = true;
			this.selectedCasee = user;
			$('#caseeModal').modal('show')
		},
	},

    mounted() {
        let x = 1;
        this.format = this.mask.replace(/X+/g, () => '$' + x++)
        this.mask.match(/X+/g).forEach((char, key) => {
            // console.log(char.length);
            // console.log(key);

            this.regex += '(\\d{' + char.length + '})?'
            console.log(this.regex);
        });

    },

    watch: {
        'searchForm.fileNumber'(next, prev) {
            if (next.length > prev.length) {
                this.searchForm.fileNumber = this.searchForm.fileNumber.replace(/[^0-9]/g, '')
            
            .replace(new RegExp(this.regex, 'g'), this.format)
            .substr(0, this.mask.length);
            }
        },
    },

	created() {
        this.getBeneficiaries();
		Fire.$on('beneficiariesChanged', () => {
			this.getBeneficiaries();
		});
	}
}
</script>