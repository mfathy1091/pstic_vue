<template>
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="!userEditMode" class="modal-title" id="userModalLabel">Create New User</h5>
                    <h5 v-show="userEditMode" class="modal-title" id="userModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="userEditMode ? updateUser() : createUser()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-label">First Name</label>
                            <input id="name" v-model="userForm.first_name" type="text" name="name" class="form-control">
                            <HasError :form="userForm" field="first_name" />
                        </div>

                        <div class="form-group">
                            <label for="name" class="form-label">Last Name</label>
                            <input id="name" v-model="userForm.last_name" type="text" name="name" class="form-control">
                            <HasError :form="userForm" field="last_name" />
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
                        <div class="form-group" v-if="areas">
                            <label class="typo__label">Areas</label>
                            <multiselect 
                            v-model="userForm.areas"
                            :options="areas"
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
							<label for="budget_id" class="form-label">Budget</label>
							<select name="budget_id" v-model="userForm.budget_id" id="budget_id" class="form-control" :class="{ 'is-invalid': userForm.errors.has('budget_id') }">
								<option value=''>Choose...</option>
								<option v-for='budget in budgets' :value='budget.id' :key="budget.id">{{ budget.name }}</option>
							</select>
							<HasError :form="userForm" field="budget_id" />
						</div>
                        
                        <div class="form-group">
							<label for="department_id" class="form-label">Department</label>
							<select name="department_id" v-model="userForm.department_id" id="department_id" class="form-control" :class="{ 'is-invalid': userForm.errors.has('department_id') }">
								<option value=''>Choose...</option>
								<option v-for='department in departments' :value='department.id' :key="department.id">{{ department.name }}</option>
							</select>
							<HasError :form="userForm" field="department_id" />
						</div>

                        <div v-show="!userEditMode" class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input v-model="userForm.password" type="password" name="password" id="password"
                            class="form-control" :class="{ 'is-invalid': userForm.errors.has('password') }">
                            <has-error :form="userForm" field="password"></has-error>
                        </div>

                        <div class="form-group form-check">
                            <input v-model="userForm.is_active" type="checkbox" class="form-check-input" id="is_active">
                            <label class="form-check-label" for="is_active">Active?</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-show="!userEditMode" type="submit" class="btn btn-success">Create</button>
                        <button v-show="userEditMode" type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import axiosMixin from '../../../mixins/axiosMixin'	

export default {
	mixins: [axiosMixin],
	components: { Multiselect },
    props:{
        userEditMode: Boolean,
        selectedUser: Object,
    },
	data() {
		return {
			// userEditMode: false,
            //  selected: this.selectedUser;,
			users: {},
			roles: [],
            budgets: [],
            areas: [],
            departments: [],
			userForm: new Form({
				id: '',
				first_name: '',
                last_name: '',
				email: '',
				password: '',
				photo: '',
				roles: [],
                areas: [],
                budget_id: '',
                department_id: '',
                is_active: '',
			})
		}
	},
    watch: {
        selectedUser (next, prev){
            if(this.userEditMode){
                this.userForm.fill(this.selectedUser)
            }else{
                this.resetUserForm();
            }
            
        }
    },

	methods: {

        resetUserForm() {
            this.userForm.id = '';
            this.userForm.first_name = '';
            this.userForm.last_name = '';
            this.userForm.email = '';
            this.userForm.password = '';
            this.userForm.photo = '';
            this.userForm.roles = [];
            this.userForm.budget_id = '';
            this.userForm.is_active = false;
        },
		createUser() {
			this.$Progress.start();
			this.userForm.post('/api/users')
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
			this.userForm.put('/api/users/'+this.userForm.id)
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
		this.getRoles();
        this.getAreas();
        this.getBudgets();
        this.getDepartments();
	}
}
</script>