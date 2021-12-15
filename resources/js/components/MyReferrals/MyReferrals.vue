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

                    <h3 class="card-title">Search</h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                        <input v-model="searchText" type="text" name="table_search" class="form-control float-right" placeholder="Search" autocomplete="off">

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                            </button>
                        </div>
                        </div>
                    </div>
            

				</div>
				<!-- /.card-header -->
				<div class="card-body table-responsive p-0">
					<table class="table table-hover text-nowrap align-middle">
						<thead>
							<tr>
								<th>File #</th>
                                <th>File Owner</th>
                                <th>Other File members</th>
                                <th>Actions</th>
							</tr>
						</thead>
						<tbody>
                            <tr v-for="referral in this.pswReferrals" :key="referral.id">
                                <td>{{ referral.referral_source.name  }}</td>
                                <td>{{ referral.referral_date | myDate }}</td>
                                <td>
                                    <div class="list-unstyled">
                                        <li v-for="(record, i) in referral.records" :key="record.id">
                                            <div v-show="i==0">
                                                <span>{{ record.month.name }}</span>
                                                <span v-show="record.status.name == 'Inactive'" class="badge badge-pill badge-secondary">{{ record.status.name }}</span>
                                                <span v-show="record.status.name == 'Active'" class="badge badge-pill badge-success">{{ record.status.name }}</span>
                                                <span v-show="record.status.name == 'Closed'" class="badge badge-pill badge-dark">{{ record.status.name }}</span>
                                                <span v-show="record.is_new == 1" class="badge badge-pill badge-info">New</span>
                                            </div>

                                        </li>
                                    </div>

                                </td>
                                <td>{{ referral.current_assigned_psw.name }}</td>

                                <td>
                                    <a class="clickable">
                                        <i class="fa fa-edit blue"></i>
                                    </a>
                                    
                                    <a class="clickable">
                                        <i class="fa fa-trash red"></i>
                                    </a>
                                    <router-link
                                    :to="{name: 'referralDetails', params: { caseeId: caseeId, referralId: referral.id }}"
                                    class="fa fa-eye blue align-middle"
                                    >
                                    
                                    </router-link>
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

	</div>
</template>
<script>
import Form from 'vform'
import Multiselect from 'vue-multiselect'
import router from '../../router'

export default {
	components: { 
		Multiselect,
	},

	data() {
		return {
			editMode: false,
			pswReferrals: [],
            searchText: '',
            searchForm: {
                fileNumber: '',
            },
            format: '',
            regex: '^',
            mask: 'XXX-XXCXXXXX',
		}
	},
	methods: {

        getPswReferrals(){
			this.$Progress.start();
            axios({
            method: 'get',
            url: '/api/current-psw/referrals',
            data: {

            }
            })
			.then(({data}) => {
				this.pswReferrals = data.data
			});
			this.$Progress.finish();
		},

	},

	created() {
        this.getPswReferrals();
		
	}
}
</script>