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
										<a href="#" @click="showEditUserModal(user)" v-if="$can('user_edit')">
											<i class="fa fa-edit blue"></i>
										</a>
										
										<a href="#" @click="deleteUser(user.id)" v-if="$can('user_delete')">
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
		<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 v-show="!editMode" class="modal-title" id="userModalLabel">Create New User</h5>
						<h5 v-show="editMode" class="modal-title" id="userModalLabel">Edit User</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editMode ? updateUser() : createUser()">
						<div class="modal-body">
							<div class="form-group">
								<label for="name" class="form-label">Name</label>
								<input id="name" v-model="userForm.name" type="text" name="name" class="form-control">
								<HasError :form="userForm" field="name" />
							</div>

							<div class="form-group">
								<label for="email" class="form-label">Email</label>
								<input id="email" v-model="userForm.email" type="email" name="email" class="form-control">
								<HasError :form="userForm" field="email" />
							</div>

							<div class="form-group" v-if="roles">
								<label class="typo__label">Roles</label>
								<multiselect 
								v-model="userForm.roles"
								:options="roles"
								:multiple="true"
								:close-on-select="false"
								:clear-on-select="false" 
								:preserve-search="true"
								placeholder="Pick some"
								label="name" 
								track-by="name" 
								:preselect-first="true">
									<!-- <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template> -->
								</multiselect>
								<!-- <pre class="language-json"><code>{{ value  }}</code></pre> -->
							</div>

							<div class="form-group">
								<label for="password" class="form-label">Password</label>
								<input v-model="userForm.password" type="password" name="password" id="password"
								class="form-control" :class="{ 'is-invalid': userForm.errors.has('password') }">
								<has-error :form="userForm" field="password"></has-error>
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
</template>
<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'

export default {
	components: { Multiselect },
	data() {
		return {
			editMode: false,
			users: {},
			roles: [],
			userForm: new Form({
				id: '',
				name: '',
				email: '',
				password: '',
				photo: '',
				roles: [],
			})
		}
	},
	methods: {
		loadRoles(){			
			this.$Progress.start();
			axios.get("/api/roles")
			.then(({data}) => {
				this.roles = data.data
			});
			this.$Progress.finish();
		},
		loadUsers(){			
			this.$Progress.start();
			axios.get("/api/user").then(({data}) => (this.users = data.data));
			this.$Progress.finish();
		},

		showCreateUserModal(){
			this.editMode = false;
			this.userForm.reset()
			$('#userModal').modal('show')
		},
		createUser() {
			this.$Progress.start();
			this.userForm.post('/api/user')
			.then(() => {
				// success
				Fire.$emit('usersChanged');

				$('#userModal').modal('hide')
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

		showEditUserModal(user){
			this.editMode = true;
			this.userForm.reset()
			$('#userModal').modal('show')
			this.userForm.fill(user)
		},
		updateUser(){
			this.$Progress.start();
			this.userForm.put('/api/user/'+this.userForm.id)
			.then(() => {
				// success
				Fire.$emit('usersChanged');
				$('#userModal').modal('hide')
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
					this.userForm.delete('/api/user/'+id)
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
		this.loadRoles()
		
		Fire.$on('usersChanged', () => {
			this.loadUsers();
		});

		
	}
}
</script>