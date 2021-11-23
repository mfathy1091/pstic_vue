<template>
	<div class="container-fluid">

		<div class="row mt-5">
			<div class="col-md-12">
				<div class="card">
				<div class="card-header">
					<h3 class="card-title">Nationalities</h3>

					<div class="card-tools">
						<button class="btn btn-success" @click="showCreateNationalityModal">
							Add New
						</button>
					</div>
					
				</div>
				<!-- /.card-header -->
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
								<tr v-for="nationality in nationalities" :key="nationality.id">
									<td>{{ nationality.id }}</td>
									<td>{{ nationality.name }}</td>
									<td>
										<a href="#" @click="showEditNationalityModal(nationality)">
											<i class="fa fa-edit blue"></i>
										</a>
										
										<a href="#" @click="deleteNationality(nationality)">
											<i class="fa fa-trash red"></i>
										</a>
									</td>
								</tr>
						</tbody>
					</table>
				</div>
				<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
		</div>

		<!-- Modal -->
		<NationalityModal 
		:v-if="selectedNationality.id"
		:editMode='editMode' 
		:selectedNationality='selectedNationality' 
		v-on:usersChanged="getLocalNationalities()">
		</NationalityModal>

	</div>
</template>
<script>
import Form from 'vform'
import axiosMixin from '../../mixins/axiosMixin'
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
		async getLocalNationalities(){
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
		

		this.getLocalNationalities();

		// get nationalities

		
		
		Fire.$on('nationalitiesChanged', () => {
			this.getLocalNationalities();
		});

		
	}
}
</script>