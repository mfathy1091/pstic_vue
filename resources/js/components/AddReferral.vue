<template>
    <div>
        <div class="form-group">
            <label>Register Using:</label>
            <select class='form-control col-md-6' v-model='register_type' @change="toggleFileField">
                <option value='0' disabled>Choose...</option>
                <option value='1' >UNHCR File</option>
                <option value='2' >Passport</option>
            </select>
        </div>

        <form @submit.prevent="getFile" v-if="showFileNumberField">
            <hr>
            <div class="form-group">
                <label for="searchInput" class="form-label">Enter the File Number</label>
                <input id="searchInput" v-model="fileForm.searchInput" type="text" name="searchInput" class="form-control">
                <HasError :form="fileForm" field="searchInput" />
            </div>

            <button  type="submit" class="btn btn-primary">Check</button>
        </form>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="m-0">
                    File Number 
                    <span v-if="this.file">(Already Exists)</span>
                </h5>
            </div>
            <div class="card-body">
                <h6 class="card-title">{{ this.file.number }}</h6>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">
                    Individuals under {{ this.file.number }}
                </h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="showCreateIndividualModal">
                        Add New <i class="fas fa-nationality-plus fa-fw"></i>
                    </button>
                </div>
                
            </div>

            <div class="card-body">
                <div class="card-body table-responsive p-0">
					<table class="table table-hover text-nowrap">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Modify</th>
							</tr>
						</thead>
						<tbody>
								<tr v-for="individual in fileIndividuals" :key="individual.id">
									<td>{{ individual.id }}</td>
									<td>{{ individual.name }}</td>
									<td>
										<a href="#" @click="showEditIndividuaModal(individual)">
											<i class="fa fa-edit blue"></i>
										</a>
										
										<a href="#" @click="deleteIndividual(individual.id)">
											<i class="fa fa-trash red"></i>
										</a>
									</td>
								</tr>
						</tbody>
					</table>
				</div>
                <button @click="getFileIndividuals" class="btn btn-primary">Refresh</button>
            </div>
        </div>

    </div>
</template>
<script>
import Form from 'vform'

export default {
    data(){
        return {
            register_type: '1',
            showFileNumberField: true,
            file: '',
            fileIndividuals: [],
            
            fileForm: new Form({
				searchInput: '',
			})
        }
    },
    methods:{
        toggleFileField() {
            if(this.register_type == 1){
                this.showFileNumberField = true
            }else{
                this.showFileNumberField = false
            }
        },
        getFile(){
			this.$Progress.start();
			axios.get('api/files/get/'+this.fileForm.searchInput)
            .then(({data}) => (
                this.file = data.data
            ));
			this.$Progress.finish();
		},
        getFileIndividuals(){
			this.$Progress.start();
			axios.get('api/files/'+this.file.id+'/individuals')
            .then(({data}) => (
                this.fileIndividuals = data.data
            ));
			this.$Progress.finish();
		},



        showCreateIndividualModal(){
			this.editMode = false;
			this.form.reset()
			$('#individualModal').modal('show')
		},
		createIndividual() {
			this.$Progress.start();
			this.form.post('api/nationalities')
			.then(() => {
				// success
				Fire.$emit('nationalitiesChanged');

				$('#individualModal').modal('hide')
				Toast.fire({
					icon: 'success',
					title: 'Added successfully'
				})
				
				this.$Progress.finish();
			})
			.catch(() => {
				// error
				this.$Progress.fail();
			})
		},

		showEditIndividualModal(individual){
			this.editMode = true;
			this.form.reset()
			$('#individualModal').modal('show')
			this.form.fill(individual)
		},
		updateIndividual(){
			this.$Progress.start();
			this.form.put('api/nationalities/'+this.form.id)
			.then(() => {
				// success
				Fire.$emit('nationalitiesChanged');
				$('#individualModal').modal('hide')
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

		deleteIndividual(id){
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			})
			.then((result) => {
				if (result.isConfirmed) {
					this.$Progress.start();
					this.form.delete('api/nationalities/'+id)
					.then(() => {
						// success
						Fire.$emit('nationalitiesChanged');
						Swal.fire(
							'Deleted!',
							'It has been deleted.',
							'success'
						)
						this.$Progress.finish();
					})
					.catch(() => {
						// error
						Swal("Failed!", "There was something wrong.", "warning");
					});
				}
			})
		},
    }
}
</script>