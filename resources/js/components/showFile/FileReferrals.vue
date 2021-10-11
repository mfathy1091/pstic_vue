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
                    Referrals Linked to {{ this.file.number }}
                </h3>

                <div class="card-tools">
                    <!-- <button class="btn btn-primary" @click="showCreateIndividualModal"> -->
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-link"></i> Add Referral
                    </button>
                </div>
                
            </div>
            <div class="card-body row">
                <p v-if="!this.file.referrals.length" class="ml-5 text-primary">
                    <b>This file has no linked referrals!</b>
                </p>

                    <!-- Referrals -->
                    <div v-for="referral in this.file.referrals" :key="referral.id"
                    class="col-sm-6 col-md-6 col-lg-4 clo-xl-3">
                        <div class="card card-outline bg-light">
                            <div class="overlay bg-white-50" v-show="referral.is_active == 0">
                                <div class="p-4 text-center">
                                    <h3 class="bg-white text-secondary">Inactive</h3>
                                    <a href="" class="text-primary p-2 bg-white">Click here re-activate</a>
                                </div>
                                

                                
                            </div>
                            <div class="card-header border-bottom-0 pt-2 pl-3 pb-0">
                                <h6 class="card-title">{{ referral.name }}</h6>
                                <div class="card-tools">
                                        <span class="clickable text-orange ml-2" @click="unlinkReferral(referral.id)">
                                            Deactivate <i class="fas fa-user-slash orange"></i>
                                        </span>
                                </div>
                            </div>
                            <div class="card-body pt-2" >       
                                <span class="text-muted text-sm"><b>Referral Details</b></span>
                                <hr>
                                <span class="text-muted text-sm"><b>Monthly Records</b></span>
                                    
                                    <span v-for="record in referral.records" :key="record.id">{{ record.month_id }}</span>
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
    name: 'FileIndividuals',
    components: {
        Multiselect,
    },

    props: {
        fileId:Number,
    },
    data(){
        return {
            file: '',
            fileReferrals: [],
        }
    },
    methods: {
        getFile(){
			this.$Progress.start();
			axios.get("/api/files/"+this.fileId)
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
        },
        getFileReferrals(){
            this.$Progress.start();
            axios.get('/api/files/'+this.fileId+'/referrals', { params: { file_id: this.fileId } })
            .then(({data}) => {
                this.fileReferrals = data.data
            });
            this.$Progress.finish();
		},
    },
    created() {
        this.getFile();
        this.getFileReferrals();

        Fire.$on('fileIndividualsChanged', () => {
            this.getFile();
        });
    }
}
</script>