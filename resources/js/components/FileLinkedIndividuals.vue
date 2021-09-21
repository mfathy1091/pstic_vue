<style scoped>
.clickable {
    cursor: pointer
}
</style>
<template>
    <div>
        <div class="card card-solid">
            <div class="card-header">
                <h3 class="card-title">
                    Individuals Linked to {{ this.file.number }}
                </h3>

                <div class="card-tools">
                    <!-- <button class="btn btn-primary" @click="showCreateIndividualModal"> -->
                    <button class="btn btn-primary">
                        <i class="fas fa-link"></i> Link Individuals
                    </button>
                </div>
                
            </div>
            <div class="card-body pb-0">
                <div class="row" v-if="this.file">
                    <p v-if="!this.file.individuals.length" class="ml-5 text-primary"><b>This file has no linked individuals!</b></p>
                    
                    <div v-for="individual in this.file.individuals" :key="individual.id"
                    class="col-12 col-lg-4 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                        <div class="card bg-light d-flex flex-fill">
                            <div class="card-header text-dark border-bottom-0">
                                <h6 class="lead">{{ individual.name }}</h6>
                            </div>
                            <div class="card-body pt-2">
                                <div class="row">
                                    <div class="col-12">
                                        
                                        <span class="text-muted text-sm"><b>Personal Details</b></span>
                                        <ul class="mb-0 fa-ul text-muted">
                                            <li><span class="fa-li"></span><b>Passport #:</b> {{ individual.passport_number }}</li>
                                            <li><span class="fa-li"></span><b>Age:</b> {{ individual.age }}</li>
                                            <li><span class="fa-li"></span><b>Gender:</b> {{ individual.gender.name }}</li>
                                            <li><span class="fa-li"></span><b>Nationality:</b> {{ individual.nationality.name }}</li>
                                            <li><span class="fa-li"></span><b>Phone #:</b> {{ individual.current_phone_number}}</li>
                                        </ul>
                                        <hr>
                                        <span class="text-muted text-sm"><b>File Related Details</b></span>
                                        <span class="clickable text-red ml-2" @click="unlinkIndividual(individual.id)">
                                            <i class="fas fa-unlink"></i> Unlink
                                        </span>

                                        <ul class="mb-0 fa-ul text-muted">
                                            <li><span class="fa-li"></span><b>Individual ID:</b> {{ individual.individual_id }}</li>
                                            <li><span class="fa-li"></span><b>Relationship:</b> {{ individual.relationship.name}}</li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                    <!-- <button class="btn btn-sm btn-primary" @click="goToIndividualPage(individual)"> -->
                                    <button class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View Page
                                    </button>
                            </div>
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
    name: 'FileLinkedIndividuals',
    components: {
        Multiselect,
    },

    props: {
        file_id:Number,
    },
    data(){
        return {
            file: ''
        }
    },
    methods: {
        getFile(){
			this.$Progress.start();
			axios.get("/api/files/"+this.file_id)
            .then(({data}) => {
                this.file = data.data
            });
			this.$Progress.finish();
        },
        unlinkIndividual(individual_id){
            this.$Progress.start();
			axios.put('/api/individuals/' + individual_id + '/unlink' )
			.then(() => {
				// success
				Fire.$emit('fileIndividualsChanged');
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
        this.getFile();

        Fire.$on('fileIndividualsChanged', () => {
            this.getFile();
        });
    }
}
</script>