<style scoped>

</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
	<div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item active" aria-current="page">Cases</li>
            </ol>
        </nav>

        <div class="row mt-3 mb-3 pl-3"> 
            <h5>Cases</h5>
        </div>

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <router-link
                            :to="{ name: 'workerPssReferrals' }"
                            class="nav-link tab-header" active-class="active">   
                            Cases
                        </router-link>
                    </li>
                </ul>
            </div>
            <!-- <router-view :key="$route.path"></router-view> -->

            <div class="card-body align-top">
                <div class="form-inline ml-2">
                    <button class="btn btn-success btn-sm mr-2">
                        <i class="fas fa-plus-circle"></i><span><b> Case</b></span>
                    </button>
                    <button class="btn btn-secondary btn-sm mr-5" @click="getCasees(casee)">
                        <i class="fas fa-sync-alt"></i>
                    </button>

                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form @submit.prevent="handleSubmit(search)">
                            <ValidationProvider name="File Number" rules="required|length:12" v-slot="{ errors }">
                                <div class="input-group">
                                    <input v-model="searchForm.fileNumber" type="search" :placeholder="mask" class="form-control form-control-sidebar" aria-label="Search">
                                    <div class="input-group-append">
                                        <button type="sumbit" class="btn btn-sidebar">
                                            <i class="fas fa-search fa-fw"></i>
                                        </button>
                                    </div>
                                </div>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </form>
                    </ValidationObserver>

                    <select v-model="filter.status_id" @change="getCasees" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                        <option value="-1" disabled>Filter by...</option>
                        <option value="1">Cases with PSS Referrals</option>
                        <option value="2">Cases with Housing Referrals</option>
                    </select>
                </div>


                <div class="row mt-3" v-if="casees">	
                    <table class=" table table-hover border table-sm">
                        <thead>
                            <tr>
                                <th>File #</th>
                                <th>Case Type</th>
                                <th>Beneficiaries</th>
                                <th># of PSS Intake</th>
                                <th># of Housing Intake</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr v-for="casee in casees" :key="casee.id">
                                    <td class="align-middle">{{ casee.file_number }}</td>
                                    <td class="align-middle">
                                        <span v-show="casee.is_family == '0'">Individual</span>
                                        <span v-show="casee.is_family == '1'">Family</span>
                                    </td>
                                    <td>
                                        <div class="list-unstyled">
                                            <li v-for="beneficiary in casee.beneficiaries" :key="beneficiary.id">
                                                <div>
                                                    <span>{{ beneficiary.name }}</span>
                                                </div>
                                            </li>
                                        </div>
                                    </td>
                                    <td>
                                        <span>{{ casee.referrals_count }}</span>
                                    </td>
                                    <td>
                                        <span>{{ casee.housing_referrals_count }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <router-link
                                            class="fa fa-eye blue align-middle"
                                            :to="{ name: 'caseBeneficiaries', params: { caseeId: casee.id } }"
                                        >
                                        </router-link>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>
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
			editMode: false,
			casees: [],
            searchText: '',
            searchForm: {
                fileNumber: '',
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

        goToCaseePage(casee){
            router.push({ path: '/casees/'+casee.id })
        },

        getCasees(){
			this.$Progress.start();
            axios({
            method: 'get',
            url: '/api/casees',
            data: {

            }
            })
			.then(({data}) => {
				this.casees = data.data
			});
			this.$Progress.finish();
		},

        async search(){
            this.$Progress.start();
            try{
                const response = await axios.get("/api/casees/search", { params: { fileNumber: this.searchForm.fileNumber } });
                this.casees = response.data.data;
                this.$Progress.finish();
            }catch (error){
                this.$Progress.fail();
                console.error(error);
            }
            
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
        this.getCasees();
		
	}
}
</script>