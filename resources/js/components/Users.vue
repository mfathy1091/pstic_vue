<template>
	<div class="container-fluid">
		<div class="row mt-5">
			<div class="col-md-12">
				<div class="card">
				<div class="card-header">
					<h3 class="card-title">Users</h3>

					<div class="card-tools">
						<button class="btn btn-success" data-toggle="modal" data-target="#addNew">
							Add New <i class="fas fa-user-plus fa-fw"></i>
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
								<th>Email</th>
								<th>Type</th>
								<th>Registered At</th>
								<th>Modify</th>
							</tr>
						</thead>
						<tbody>
								<tr v-for="user in users" :key="user.id">
									<td>{{ user.id }}</td>
									<td>{{ user.name }}</td>
									<td>{{ user.email }}</td>
									<td>{{ user.type | upText}}</td>
									<td>{{ user.created_at | myDate }}</td>
									<td>
										<a href="#">
											<i class="fa fa-edit blue"></i>
										</a>
										/
										<a href="#" @click="deleteUser(user.id)">
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
		<div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="addNewLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addNewLabel">Add New User</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="createUser">
						<div class="modal-body">
							<div class="form-group">
								<label for="name" class="form-label">Name</label>
								<input id="name" v-model="form.name" type="text" name="name" class="form-control">
								<HasError :form="form" field="name" />
							</div>

							<div class="form-group">
								<label for="email" class="form-label">Email</label>
								<input id="email" v-model="form.email" type="email" name="email" class="form-control">
								<HasError :form="form" field="email" />
							</div>

							<div class="form-group">
								<label for="bio" class="form-label">Bio</label>
								<input id="bio" v-model="form.bio" type="bio" name="bio" class="form-control">
								<HasError :form="form" field="bio" />
							</div>

							<div class="form-group">
								<select name="type" v-model="form.type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
									<option value="">Select User Role</option>
									<option value="admin">Admin</option>
									<option value="user">Standard User</option>
									<option value="author">Author</option>
								</select>
								<has-error :form="form" field="type"></has-error>
							</div>

							<div class="form-group">
								<input v-model="form.password" type="password" name="password" id="password"
								class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
								<has-error :form="form" field="password"></has-error>
							</div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-success">Create</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import Form from 'vform'

export default {
	data() {
		return {
			users: {},
			form: new Form({
				name: '',
				email: '',
				password: '',
				type: '',
				bio: '',
				photo: '',
			})
		}
	},
	methods: {
		loadUsers(){
			this.$Progress.start();
			axios.get("api/user").then(({data}) => (this.users = data.data));
			this.$Progress.finish();
		},

		createUser() {
			this.$Progress.start();
			this.form.post('api/user')
			.then(() => {
				// we can listen to this event from anywhere
				Fire.$emit('usersChanged');

				$('#addNew').modal('hide')
				Toast.fire({
					icon: 'success',
					title: 'Added successfully'
				})
				
				this.$Progress.finish();
			})
			.catch(() => {

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
					this.form.delete('api/user/'+id)
					.then(() => {
						Fire.$emit('usersChanged');
						Swal.fire(
							'Deleted!',
							'Your file has been deleted.',
							'success'
						)
						this.$Progress.finish();
					})
					.catch(() => {
						Swal("Failed!", "There was something wrong.", "warning");
					});
				}
			})
		},
	},

	created() {
		this.loadUsers();
		
		Fire.$on('usersChanged', () => {
			this.loadUsers();
		});
	}
}
</script>