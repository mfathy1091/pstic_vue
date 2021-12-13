<style scoped>
.clickable {
    cursor: pointer
}
</style>
<template>
    <div>
        <div class="card-body bg-white pt-2 pl-2 p-0">
            <div class="row m-3">
				<button class="btn btn-success btn-sm mr-2">
				<!-- <button class="btn btn-success btn-sm mr-2" @click="showCreateRoleModal" v-if="$can('role_create')"> -->
					<i class="fas fa-plus-circle"></i><span><b> Beneficiary</b></span>
				</button>
				<button class="btn btn-secondary btn-sm" @click="getCaseeBeneficiaries">
					<i class="fas fa-sync-alt"></i>
				</button>
			</div>

            


            <div class="row m-0" v-if="casee">
                <p v-if="!casee.beneficiaries.length" class="ml-5 text-primary">
                    <b>This case has no linked beneficiaries!</b>
                </p>

                <!-- beneficiaries -->
                <div v-for="individual in this.casee.beneficiaries" :key="individual.id">

                    <div class="card individual-card" style="width: 18rem;">
                        <div class="card-body">
                            <h4 class="card-title">{{ individual.name }}</h4>
                            <div class="dropdown">
                            
                            <button class="btn btn-tool float-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <button class="dropdown-item" type="button">Edit</button>
                                <button class="dropdown-item" type="button">
                                    <span class="text-red">Delete</span>
                                </button>
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item" type="button">
                                    <span class="text-orange">Deativate</span>
                                </button>
                            </div>
                            </div>

                            <br>
                            <br>

                            <div class="mb-0 fa-ul">
                                <li><span class="text-sm"></span>{{ individual.age }} years; {{ individual.gender.name }}; {{ individual.nationality.name }}</li>
                                <li><span class="text-sm"></span><i class="fas fa-phone-square-alt"></i> {{ individual.current_phone_number}}</li>
                                <li><span class="text-sm"></span><b>Passport #:</b> {{ individual.passport_number }}</li>

                            </div>
                            <hr>
                            <span class="text-sm"><b>File Details</b></span>

                            <ul class="mb-0 fa-ul">
                                <li><span class="fa-li"></span><b>Individual ID:</b> {{ individual.beneficiary_id }}</li>
                                <li><span class="fa-li"></span><b>Relationship:</b> {{ individual.relationship.name}}</li>
                            </ul>
                        </div>
                    </div>
                    
                </div>

            </div>
        </div>


    </div>
</template>

<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'

export default {
    name: 'Caseebeneficiaries',
    components: {
        Multiselect,
    },

    props: {
        caseeId: {
            // type: Number, 
            required: true
        }
    },

    data(){
        return {
            casee: '',
            caseeBeneficiaries: [],
            beneficiaryEditMode: false,
			selectedBeneficiary: {},
        }
    },
    methods: {
        getCasee(){
			this.$Progress.start();
			axios.get("/api/casees/"+this.caseeId)
            .then(({data}) => {
                this.casee = data.data
            });
			this.$Progress.finish();
        },

        getCaseeBeneficiaries(){
            this.$Progress.start();
            axios.get('/api/casees/'+this.caseeId+'/beneficiaries', { params: { casee_id: this.caseeId } })
            .then(({data}) => {
                this.caseeBeneficiaries = data.caseeBeneficiaries
            });
            this.$Progress.finish();
		},

        unlinkIndividual(beneficiary_id){
            this.$Progress.start();
			axios.put('/api/beneficiaries/' + beneficiary_id + '/unlink' )
			.then(() => {
				// success
				Fire.$emit('caseebeneficiariesChanged');
				// $('#individualModal').modal('hide')
				Swal.fire(
					'Unlinked!',
					'Unlinked successfully.',
					'success'
				)
				this.$Progress.finish();
			})
			.catch(() => {
				// error
				this.$Progress.fail();
			})
        }
    },
    created() {
        this.getCasee();
        this.getCaseeBeneficiaries();

        Fire.$on('caseebeneficiariesChanged', () => {
            this.getCaseeBeneficiaries();
        });
    }
}
</script>