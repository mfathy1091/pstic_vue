<template>
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
</template>
<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
export default {
	components: { Multiselect },
    props:{
        editMode: Boolean,
        selectedUser: Object,
    },
	data() {
		return {
			// editMode: false,
            //  selected: this.selectedUser;,
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
    watch: {
        selectedUser (next, prev){
            this.userForm.fill(this.selectedUser)
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

	},

	created() {
		this.loadRoles();
	}
}
</script>