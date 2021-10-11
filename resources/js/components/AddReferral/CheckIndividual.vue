<template>
    <div>
        <div class="form-group">
            <label>Register Using:</label>
            <select class='form-control col-md-6' v-model='register_type'>
                <option value='0' disabled>Choose...</option>
                <option value='1' >UNHCR File</option>
                <option value='2' >Passport</option>
            </select>
        </div>

        <!-- Register By UNHCR FILE SECTION -->
        <AddRegisteredIndividual v-if="register_type==1" />
        
        
        <!-- Register By Password -->
        <AddIndividualWithPassport v-if="register_type==2" />

    

    </div>
</template>

<script>
import Form from 'vform'
import AddRegisteredIndividual from '../AddRegisteredIndividual.vue'
import AddIndividualWithPassport from '../AddIndividualWithPassport.vue'

export default {
    name: 'CheckIndividual',
    components: {
        AddRegisteredIndividual,
        AddIndividualWithPassport,
    },
    
    data() {
        return{
            register_type: '0',
        }
    },
    methods: {

    }
}
</script>