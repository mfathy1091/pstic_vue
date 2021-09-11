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

        <div v-if="showCheckFileNumberField">
			<form @submit.prevent="getFile">
				<hr>
				<div class="form-group">
					<label for="searchInput" class="form-label">Enter the File Number</label>
					<input id="searchInput" v-model="fileForm.searchInput" type="text" name="searchInput" class="form-control">
					<HasError :form="fileForm" field="searchInput" />
				</div>

				<button  type="submit" class="btn btn-primary">Check</button>
			</form>

			<div v-if="showRegisterByFileNumberSection">
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
								Add New
							</button>
						</div>
						
					</div>

					<div class="card-body">
						<div class="card-body table-responsive p-0">
							<table class="table table-hover text-nowrap">
								<thead>
									<tr>
										<th>Name</th>
										<th>Individual ID</th>
										<th>Passport #</th>                     
										<th>Relationship</th>
										<th>Age</th>
										<th>Gender</th>
										<th>Nationality</th>
										<th>Current Phone #</th>
										<th>Modify</th>
									</tr>
								</thead>
								<tbody>
										<tr v-for="individual in fileIndividuals" :key="individual.id">
											<td>{{ individual.name }}</td>
											<td>{{ individual.individual_id }}</td>
											<td>{{ individual.passport_number }}</td>
											<td>{{ individual.relationship.name}}</td>
											<td>{{ individual.age }}</td>
											<td>{{ individual.gender.name}}</td>
											<td>{{ individual.nationality.name}}</td>
											<td>{{ individual.current_phone_number }}</td>
											<td>
												<a href="#" @click="showEditIndividualModal(individual)">
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
					</div>
				</div>

				<div class="form-group">
					<label for="directIndividual_id" class="form-label">Choose Direct</label>
					<select name="directIndividual_id" v-model="directIndividual_id" id="directIndividual_id" class="form-control" aria-placeholder="fdgfdg">
						<option value='0' disabled>Choose...</option>
						<option v-for='individual in fileIndividuals' :value='individual.id' :key="individual.id">{{ individual.name }}</option>
					</select>
				</div>
				<button class="btn btn-primary">Next</button>
				<br>
				<br>

				<!-- Modal -->
				<div class="modal fade" id="individualModal" tabindex="-1" aria-labelledby="individualModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 v-show="!editMode" class="modal-title" id="individualModalLabel">Create New User</h5>
								<h5 v-show="editMode" class="modal-title" id="individualModalLabel">Edit User</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form @submit.prevent="editMode ? updateIndividual() : createIndividual()">
								<div class="modal-body">

									<div class="form-group">
										<label for="relationship_id" class="form-label">Relationship to PA</label>
										<select name="relationship_id" v-model="individualForm.relationship_id" id="relationship_id" class="form-control" :class="{ 'is-invalid': individualForm.errors.has('relationship_id') }">
											<option value='0' disabled>Choose...</option>
											<option v-for='relationship in relationships' :value='relationship.id' :key="relationship.id">{{ relationship.name }}</option>
										</select>
										<HasError :form="individualForm" field="relationship_id" />
									</div>
									
									<div class="form-group">
										<label for="individual_id" class="form-label">File Individual ID</label>
										<input id="individual_id" v-model="individualForm.individual_id" type="text" name="individual_id" class="form-control">
										<HasError :form="individualForm" field="individual_id" />
									</div>

									<hr class="col-8 mt-5 mb-5">
									
									<div class="form-group">
										<label for="name" class="form-label">Name</label>
										<input id="name" v-model="individualForm.name" type="text" name="name" class="form-control">
										<HasError :form="individualForm" field="name" />
									</div>

									<div class="form-group">
										<label for="passport_number" class="form-label">Passport Number</label>
										<input id="passport_number" v-model="individualForm.passport_number" type="text" name="passport_number" class="form-control">
										<HasError :form="individualForm" field="passport_number" />
									</div>

									<div class="form-group">
										<label for="age" class="form-label">Age</label>
										<input id="age" v-model="individualForm.age" type="number" name="age" class="form-control" onwheel="return false;">
										<HasError :form="individualForm" field="age" />
									</div>

									<div class="form-group">
										<label for="gender_id" class="form-label">Gender</label>
										<select name="gender_id" v-model="individualForm.gender_id" id="gender_id" class="form-control" :class="{ 'is-invalid': individualForm.errors.has('gender_id') }">
											<option value='0' disabled>Choose...</option>
											<option value="1">Male</option>
											<option value="2">Female</option>
										</select>
										<has-error :form="individualForm" field="gender_id"></has-error>
									</div>

									<div class="form-group">
										<label for="nationality_id" class="form-label">Nationality</label>
										<select name="nationality_id" v-model="individualForm.nationality_id" id="nationality_id" class="form-control" :class="{ 'is-invalid': individualForm.errors.has('nationality_id') }">
											<option value='0' disabled>Choose...</option>
											<option v-for='nationaity in nationalities' :value='nationaity.id' :key="nationaity.id">{{ nationaity.name }}</option>
										</select>
										<has-error :form="individualForm" field="nationality_id"></has-error>
									</div>

									<div class="form-group">
										<label for="current_phone_number" class="form-label">Current Phone Number</label>
										<input id="current_phone_number" v-model="individualForm.current_phone_number" type="text" name="current_phone_number" class="form-control">
										<HasError :form="individualForm" field="current_phone_number" />
									</div>


								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
									<button v-show="!editMode" type="submit" class="btn btn-success">Create</button>
									<button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
								</div>

							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

    </div>
</template>
<script>
import Form from 'vform'

export default {
    data(){
        return {
			editMode: false,
			nationalities: {},
            register_type: '1',
            showCheckFileNumberField: true,
			showRegisterByFileNumberSection: false,
            file: '',
            fileIndividuals: [],
			directIndividual_id: '0',
			relationships: [],
            
            fileForm: new Form({
				searchInput: '',
			}),
			individualForm : new Form({
				id: '',
                file_number: '',
                passport_number: '',
                name: '',
                age: '',
                is_registered: '',
                file_id: '1',
                individual_id: '',
                gender_id: '0',
                nationality_id: '0',
                relationship_id: '0',
                current_phone_number: '',
            }),
        }
    },
    methods:{
        toggleFileField() {
            if(this.register_type == 1){
                this.showCheckFileNumberField = true
            }else{
                this.showCheckFileNumberField = false
            }
        },
		getNationalities(){
			this.$Progress.start();
			axios.get("api/nationalities").then(({data}) => (this.nationalities = data.data));
			this.$Progress.finish();
		},
        getFile(){
			this.$Progress.start();
			axios.get('api/files/get/'+this.fileForm.searchInput)
            .then(({data}) => {
					this.file = data.data
					Fire.$emit('fileChanged');
					Fire.$emit('fileIndividualsChanged');
					
					this.$Progress.finish();
				if(this.file){
					this.showRegisterByFileNumberSection = true	
				}else{
					this.showRegisterByFileNumberSection = false
				}
            })
		},
        getFileIndividuals(){
			
			if(this.file){
				this.$Progress.start();
				axios.get('api/files/'+this.file.id+'/individuals')
				.then(({data}) => {
					this.fileIndividuals = data.data
				});
				this.$Progress.finish();
			}else{
				this.fileIndividuals = []
			}

		},

		getRelationships(){
			this.$Progress.start();
			axios.get('api/relationships/')
            .then(({data}) => {
                this.relationships = data.data
			});
			this.$Progress.finish();
		},

        showCreateIndividualModal(){
			this.editMode = false;
			this.individualForm.reset()
			$('#individualModal').modal('show')
		},
		createIndividual() {
			this.$Progress.start();
			this.individualForm.post('api/individuals')
			.then(() => {
				// success
				Fire.$emit('fileIndividualsChanged');

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
			this.individualForm.reset()
			$('#individualModal').modal('show')
			this.individualForm.fill(individual)
		},
		updateIndividual(){
			this.$Progress.start();
			this.individualForm.put('api/individuals/'+this.individualForm.id)
			.then(() => {
				// success
				Fire.$emit('fileIndividualsChanged');
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
					this.individualForm.delete('api/individuals/'+id)
					.then(() => {
						// success
						Fire.$emit('fileIndividualsChanged');
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
    },
	created(){
		Fire.$on('fileChanged', () => {
			this.getFileIndividuals();
		});

		Fire.$on('fileIndividualsChanged', () => {
			this.getFileIndividuals();
		});

		this.getRelationships();
		this.getNationalities();
	}
}
</script>