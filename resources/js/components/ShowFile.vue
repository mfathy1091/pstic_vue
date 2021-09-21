<style scoped>
.clickable {
    cursor: pointer
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>  
        <!-- Referral Details Section -->
        <div class="card mt-3" v-if="this.file">
            <div class="card-header">
                <h5 class="m-0">
                    File Details: {{ this.file.number }}
                <span @click="showEditFileModal"
                id='clickableAwesomeFont' class="ml-5">
                    <i class="fa fa-edit blue"></i>
                </span>
                </h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col mb-4">
                        <h6 class="card-subtitle mb-2 text-muted">File details</h6>
                        <div class="ml-4">
                            <li>File Number: {{ this.file.number }}</li>
                            <li>File Owner: </li>
                        </div>
                    </div>
                    <div class="col mb-4" >
                        <h6 class="card-subtitle mb-2 text-muted">Linked Individuals</h6>
                        <!-- <div class="ml-4" v-for="reason in this.referral.reasons" :key="reason.id">
                            <li>{{ reason.name  }}</li>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- File Modal -->
		<div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 v-show="!fileEditMode" class="modal-title" id="fileModalLabel">Create New File</h5>
						<h5 v-show="fileEditMode" class="modal-title" id="fileModalLabel">Edit File</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
                    
                    <!-- File Number Field -->
                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form @submit.prevent="fileEditMode ? handleSubmit(updateFile) : handleSubmit(createFile)">
                            <div class="modal-body">
                                <ValidationProvider name="File Number" rules="required|length:12" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="number" class="form-label">Enter File Number</label>
                                    <input v-model="fileForm.number" type="text" :placeholder="mask" class="form-control">
                                    <span class="text-danger">{{ errors[0] }}</span>
                                </div>
                                </ValidationProvider>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button v-show="!fileEditMode" type="submit" class="btn btn-success">Create</button>
                                <button v-show="fileEditMode" type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </ValidationObserver>

                </div>  
			</div>
		</div>
        <!-- Linked Individuals Section -->
        <FileLinkedIndividuals v-if="file" :file_id="file.id" />

    </div>
</template>

<script>
import Form from 'vform'
import FileLinkedIndividuals from './FileLinkedIndividuals.vue'
import Multiselect from 'vue-multiselect'

export default {
    name: 'ShowFile',
    components: {
        FileLinkedIndividuals,
        Multiselect,
    },

    data() {
        
        return{
            file: '',

            fileEditMode: false,
            
            linkedIndividuals: [],

            fileForm: new Form({
                id: '',
                number: '',
                linked_individuals: [],
            }),
            format: '',
            regex: '^',
            mask: 'XXX-XXCXXXXX',
        }
    },
    methods: {
        getFile(){
			this.$Progress.start();
			axios.get("/api/files/"+this.$route.params.file_id)
            .then(({data}) => {
                this.file = data.data
            });
			this.$Progress.finish();
        },

        showEditFileModal(){
			this.fileEditMode = true;
			this.fileForm.reset()
			$('#fileModal').modal('show')
			this.fileForm.fill(this.file)
		},
		updateFile(){
			this.$Progress.start();
			this.fileForm.put('/api/files/'+this.fileForm.id)
			.then(() => {
				// success
				Fire.$emit('fileChanged');
				$('#fileModal').modal('hide')
				Swal.fire(
					'Updated!',
					'It has been updated.',
					'success'
				)
				this.$Progress.finish();
			})
			.catch(() => {
				// error
				this.$Progress.fail();
			})
		},
    },

    created (){
        this.getFile()

        Fire.$on('fileChanged', () => {
			this.getFile();
		});
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

        this.$store.dispatch('getNationalities')
    },

    watch: {
        'fileForm.number'(next, prev) {
            if (next.length > prev.length) {
                this.fileForm.number = this.fileForm.number.replace(/[^0-9]/g, '')
            
            .replace(new RegExp(this.regex, 'g'), this.format)
            .substr(0, this.mask.length);
            }
        },
    },
}
</script>