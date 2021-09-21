<style scoped>
.clickable {
    cursor: pointer
}
</style>
<template>
    <div>
        <div class="card card-row" v-if="this.file">
            <div class="card-header">
                <h3 class="card-title">
                    Individuals Linked to {{ this.file.number }}
                </h3>

                <div class="card-tools">
                    <!-- <button class="btn btn-primary" @click="showCreateIndividualModal"> -->
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-link"></i> Link Individuals
                    </button>
                </div>
                
            </div>
            <div class="card-body row">
                <p v-if="!this.file.individuals.length" class="ml-5 text-primary">
                    <b>This file has no linked individuals!</b>
                </p>

                    <!-- individuals -->
                    <div v-for="individual in this.file.individuals" :key="individual.id"
                    class="col-sm-6 col-md-6 col-lg-4 clo-xl-3">
                        <div class="card card-outline bg-light">
                            <div class="overlay bg-white-50" v-show="individual.is_active == 0">
                                <div class="p-4 text-center">
                                    <h3 class="bg-white text-secondary">Inactive</h3>
                                    <a href="" class="text-primary p-2 bg-white">Click here re-activate</a>
                                </div>
                                

                                
                            </div>
                            <div class="card-header border-bottom-0 pt-2 pl-3 pb-0">
                                <h6 class="card-title">{{ individual.name }}</h6>
                                <div class="card-tools">
                                        <span class="clickable text-orange ml-2" @click="unlinkIndividual(individual.id)">
                                            Deactivate <i class="fas fa-user-slash orange"></i>
                                        </span>
                                </div>
                            </div>
                            <div class="card-body pt-2" >

                                        
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

                                        <ul class="mb-0 fa-ul text-muted">
                                            <li><span class="fa-li"></span><b>Individual ID:</b> {{ individual.individual_id }}</li>
                                            <li><span class="fa-li"></span><b>Relationship:</b> {{ individual.relationship.name}}</li>
                                        </ul>

                            </div>
                            <div class="card-footer p-0">
                                    <!-- <button class="btn btn-sm btn-primary" @click="goToIndividualPage(individual)"> -->
                                    <span class="btn btn-tool clickable">
                                        Show <i class="fas fa-eye"></i>
                                    </span>
                                    <span class="btn btn-tool clickable text-blue">
                                        Edit <i class="fas fa-pen blue"></i>
                                    </span>
                                    <span class="btn btn-tool clickable text-red">
                                        Delete <i class="fas fa-trash red"></i>
                                    </span>
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