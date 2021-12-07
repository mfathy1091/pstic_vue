<style scoped>
.clickable {
    cursor: pointer
}
</style>
<template>
    <div>
        <div class="card card-row" v-if="this.casee">
            <div class="card-header">
                <h3 class="card-title">
                    beneficiaries Linked to {{ this.casee.number }}
                </h3>

                <div class="card-tools">
                    <!-- <button class="btn btn-primary" @click="showCreateIndividualModal"> -->
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-link"></i> Link beneficiaries
                    </button>
                </div>
                
            </div>
            <div class="card-body row">
                <p v-if="!this.casee.beneficiaries.length" class="ml-5 text-primary">
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
        casee_id:Number,
    },
    data(){
        return {
            casee: ''
        }
    },
    methods: {
        getCasee(){
			this.$Progress.start();
			axios.get("/api/casees/"+this.casee_id)
            .then(({data}) => {
                this.casee = data.data
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

        Fire.$on('caseebeneficiariesChanged', () => {
            this.getCasee();
        });
    }
}
</script>