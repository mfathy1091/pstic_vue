<template>
	<div>
		<div class="card-body">
			<div class="form-inline ml-2">
				<button class="btn btn-success btn-sm mr-2" @click="showCreateNationalityModal">
					<i class="fas fa-plus-circle"></i><span><b> Nationality</b></span>
				</button>

				<button class="btn btn-secondary btn-sm mr-5" @click="getNationalities">
					<i class="fas fa-sync-alt"></i>
				</button>

				<select @change="getNationalities" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
					<option value='-1' disabled>Worker...</option>
					<!-- <option v-for='user in users' :value='user.id' :key="user.id">{{ user.name }}</option> -->
				</select>                
			</div>

			<div class="row mt-3">
				<table class="border table table-hover">
					<thead>
						<tr>
							<th>Name</th>
							<th>Modify</th>
						</tr>
					</thead>
					<tbody v-if="nationalities">
							<tr v-for="nationality in nationalities" :key="nationality.id">
								<td>{{ nationality.name }}</td>
								<td>
									<span class="clickable mr-2" @click="showEditNationalityModal(nationality)">
										<i class="fas fa-pencil-alt blue"></i>
									</span>
									
									<span class="clickable mr-2" href="#" @click="deleteNationality(nationality)">
										<i class="fa fa-trash red"></i>
									</span>
								</td>
							</tr>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Modal -->
		<NationalityModal 
		:v-if="selectedNationality.id"
		:editMode='editMode' 
		:selectedNationality='selectedNationality' 
		v-on:usersChanged="getNationalities()">
		</NationalityModal>

	</div>
</template>
<script>
import Form from 'vform'
import axiosMixin from '../../../mixins/axiosMixin'
import NationalityModal from './NationalityModal'

export default {
	mixins: [axiosMixin],
	components: { 
			NationalityModal,
	},

	data() {
		return {
			nationalities: [],
			editMode: false,
			selectedNationality: {},
		}
	},
	methods: {
		async getNationalities(){
			const res = await this.getAll('get', '/api/nationalities')
			if(res.status==200){
				console.log(res)
				this.nationalities = res.data.data
			}
		},

		showCreateNationalityModal(){
			this.editMode = false;
			this.selectedNationality = {};
			$('#nationalityModal').modal('show')
		},


		showEditNationalityModal(nationality){
			this.editMode = true;
			this.selectedNationality = nationality;
			$('#nationalityModal').modal('show')
		},

		deleteNationality(nationality){
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
					axios.delete('/api/nationalities/'+nationality.id)
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

	},

	created() {
		// console.log($getPermissions());

		this.getNationalities();

		Fire.$on('nationalitiesChanged', () => {
			this.getNationalities();
		});

		
	}
}
</script>