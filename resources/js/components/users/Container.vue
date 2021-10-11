<style scoped>
.badge{
	font-size: 0.7rem;
	margin-left: 2px;
	
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
	<div class="container-fluid">
		<div class="row mt-5">
			<div class="col-md-12">
				<div class="card">
				<div class="card-header">
					<h3 class="card-title">Users</h3>

					<div class="card-tools" v-if="$can('user_create')">
						<button class="btn btn-success" @click="showCreateUserModal">Add New</button>
					</div>
					
				</div>
				<!-- /.card-header -->
				<div class="card-body table-responsive p-0">
					<table class="table table-hover text-nowrap">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Email</th>
								<th>Registered At</th>
								<th>Roles</th>
								<th>Modify</th>
							</tr>
						</thead>
						<tbody>
								<tr v-for="user in users" :key="user.id">
									<td>{{ user.id }}</td>
									<td>{{ user.name }}</td>
									<td>{{ user.email }}</td>
									<td>{{ user.created_at | myDate }}</td>
									<td>
										<span v-for="role in user.roles" :key="role.id" class="badge badge-pill badge-primary">{{role.name}}</span>
									</td>
									<td>
										<a class="clickable" @click="showEditUserModal(user)" v-if="$can('user_edit')">
											<i class="fa fa-edit blue"></i>
										</a>
										
										<a class="clickable" @click="deleteUser(user.id)" v-if="$can('user_delete')">
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
		<UserModal 
		:v-if="selectedUser.id"
		:editMode='editMode' 
		:selectedUser='selectedUser' 
		v-on:usersChanged="loadUsers()">
		</UserModal>

	</div>
</template>
<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import UserModal from './UserModal'

export default {
	components: { 
		Multiselect,
		UserModal,
	},

	data() {
		return {
			editMode: false,
			selectedUser: {},
			users: {},
			roles: [],
		}
	},
	methods: {

		loadUsers(){			
			this.$Progress.start();
			axios.get("/api/user").then(({data}) => (this.users = data.data));
			this.$Progress.finish();
		},

		showCreateUserModal(){
			this.editMode = false;
			this.selectedUser = {};
			$('#userModal').modal('show')
		},


		showEditUserModal(user){
			this.editMode = true;
			this.selectedUser = user;
			$('#userModal').modal('show')
		},


		deleteUser(id){
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
					axios.delete('/api/user/'+id)
					.then(() => {
						// success
						Fire.$emit('usersChanged');
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
		

		this.loadUsers()
		
		Fire.$on('usersChanged', () => {
			this.loadUsers();
		});

		
	}
}
</script>