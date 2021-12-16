<style scoped>
    .clickable {
        cursor: pointer
    }

    .router-link-active{
        background-color: #ffffff !important;
        color: #459adf !important;
        
    }

    .router-link-active.tab-header{
        border-top: 1px solid;
        border-left: 1px solid;
        border-right: 1px solid;
    }

    .back-btn{
        background-color: #f4f6f9 !important;
    }

    .my-breadcrumb{
        background-color: #e9ecef !important;
    }
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item">
                    <router-link
                    :to="{ name: 'cases' }"
                    class="my-breadcrumb"
                    > 
                        Cases 
                    </router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ casee.file_number }}</li>
            </ol>
        </nav>

        <div class="row mt-3 mb-3">
            <router-link
            :to="{ name: 'cases' }"
            class="back-btn pl-3 pr-3"> 
                <i class="fas fa-arrow-left"></i>
            </router-link>
            <h5>        
                {{ casee.file_number }}
            </h5>
            <span @click="showEditCaseeModal"
                id='clickableAwesomeFont' class="ml-5">
                    <i class="fas fa-pencil-alt blue"></i>
            </span>
        </div>

        <!-- Case Modal -->
		<div class="modal fade" id="caseeModal" tabindex="-1" aria-labelledby="caseeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 v-show="!caseeEditMode" class="modal-title" id="caseeModalLabel">Create New Case</h5>
						<h5 v-show="caseeEditMode" class="modal-title" id="caseeModalLabel">Edit Casee</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
                    
                    <!-- Casee fileNumber Field -->
                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form @submit.prevent="caseeEditMode ? handleSubmit(updateCasee) : handleSubmit(createCasee)">
                            <div class="modal-body">
                                <ValidationProvider name="Casee fileNumber" rules="required|length:12" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="fileNumber" class="form-label">Enter Casee fileNumber</label>
                                    <input v-model="caseeForm.fileNumber" type="text" :placeholder="mask" class="form-control">
                                    <span class="text-danger">{{ errors[0] }}</span>
                                </div>
                                </ValidationProvider>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button v-show="!caseeEditMode" type="submit" class="btn btn-success">Create</button>
                                <button v-show="caseeEditMode" type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </ValidationObserver>

                </div>  
			</div>
		</div>

        <ul class="nav">
            <li class="nav-item">
                <router-link
                    :to="{ name: 'caseBeneficiaries' }"
                    class="nav-link tab-header">   
                    Beneficiaries
                </router-link>
            </li>
            <li class="nav-item">
                <router-link
                    :to="{ name: 'caseReferrals' }"
                    class="nav-link tab-header">
                    PSS Referrals
                </router-link>
            </li>
            <li class="nav-item">
                <router-link
                    :to="{ name: 'caseReferrals' }"
                    class="nav-link tab-header">
                    Housing Referrals
                </router-link>
            </li>

        </ul>

        <router-view :key="$route.path"></router-view>

    </div>
</template>

<script>
import Form from 'vform'
import Caseebeneficiaries from './CaseBeneficiaries.vue'
import CaseeReferrals from './CaseReferrals.vue'
import Multiselect from 'vue-multiselect'
import ReferralModal from './ReferralModal'
import axiosMixin from '../../mixins/axiosMixin'	
import router from '../../router'

export default {
	mixins: [axiosMixin],
    name: 'ShowCasee',
    components: {
        Caseebeneficiaries,
        CaseeReferrals,
        Multiselect,
        ReferralModal,
    },

    props: {
        caseeId: {
            // type: Number, 
            required: true
        }
    },
    data() {
        
        return{
            referralEditMode: false,
			selectedReferral: {},
			// referrals: {},


            casee: '',
            // caseeId: this.$route.params.caseeId,

            caseeEditMode: false,
            
            linkedbeneficiaries: [],

            caseeForm: new Form({
                id: '',
                fileNumber: '',
                linked_beneficiaries: [],
            }),
            format: '',
            regex: '^',
            mask: 'XXX-XXCXXXXX',
        }
    },
    methods: {

        showEditCaseeModal(){
			this.caseeEditMode = true;
			this.caseeForm.reset()
			$('#caseeModal').modal('show')
			this.caseeForm.fill(this.casee)
		},
		updateCasee(){
			this.$Progress.start();
			this.caseeForm.put('/api/casees/'+this.caseeForm.id)
			.then(() => {
				// success
				Fire.$emit('caseeChanged');
				$('#caseeModal').modal('hide')
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
        goToCases(){
            router.push({ name: 'cases'});
        }

    },

    created (){
        this.getCasee(this.caseeId)

        Fire.$on('caseeChanged', () => {
			this.getCasee();
		});
    },
    mounted() {
        let x = 1;
        this.format = this.mask.replace(/X+/g, () => '$' + x++)
        this.mask.match(/X+/g).forEach((char, key) => {
            // console.log(char.length);
            // console.log(key);

            this.regex += '(\\d{' + char.length + '})?'
            // console.log(this.regex);
        });

        // this.$store.dispatch('getNationalities')
    },

    watch: {
        'caseeForm.fileNumber'(next, prev) {
            if (next.length > prev.length) {
                this.caseeForm.fileNumber = this.caseeForm.fileNumber.replace(/[^0-9]/g, '')
            
            .replace(new RegExp(this.regex, 'g'), this.format)
            .substr(0, this.mask.length);
            }
        },
    },
}
</script>