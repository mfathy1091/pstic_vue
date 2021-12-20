<style scoped>
.badge{
	font-size: 0.7rem;
	margin-left: 2px;
	
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
	    <div class="container-fluid">
                <nav aria-label="breadcrumb">
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item active" aria-current="page">Cases</li>
            </ol>
        </nav>
		<div class="row">
			<div class="col-md-12">
                <div class="col-4">
                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form @submit.prevent="handleSubmit(search)">
                            <ValidationProvider name="File Number" rules="required|length:12" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="file-number" class="form-label">Search By File Number</label>
                                <input v-model="searchForm.fileNumber" type="text" :placeholder="mask" class="form-control">
                                <span class="text-danger">{{ errors[0] }}</span>
                            </div>
                            </ValidationProvider>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </ValidationObserver>
                </div>

                <br>

				<div class="card">	
                    <table class="table table-hover text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>File #</th>
                                <th>File Owner</th>
                                <th>Other File members</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr v-for="casee in casees" :key="casee.id">
                                    <td class="align-middle">{{ casee.file_number }}</td>
                                    <td class="align-middle"></td>
                                    <td>
                                        <li v-for="individual in casee.beneficiaries" :key="individual.id">
                                        {{ individual.name }}
                                        </li>
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