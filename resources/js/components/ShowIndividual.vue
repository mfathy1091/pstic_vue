<template>
    <div>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="m-0">
                    {{ this.individual.name }}
                </h5>
            </div>
<div class="card" v-if="this.individual">
    <div class="card-body">
        <div class="form-group">
            <strong>File Number:</strong>
            {{ this.individual.file.number }}
            <!-- <a href="{{route('files.show',$file.id)}}" class="ml-3 font-italic" role="button" aria-pressed="true">Show File</a> -->
        </div>
        
        <div class="form-group">
            <strong>Individual ID:</strong>
            {{ this.individual.individual_id }}
        </div>
        
        <div class="form-group">
            <strong>Passport Number:</strong>
            {{ this.individual.passport_number }}
        </div>

        <div class="form-group">
            <strong>Age:</strong>
            {{ this.individual.age }}
        </div>

        <div class="form-group">
            <strong>Gender:</strong>
            {{ this.individual.gender.name }}
        </div>

        <div class="form-group">
            <strong>Nationality:</strong>
            {{ this.individual.nationality.name }}
        </div>

        <div class="form-group">
            <strong>Relationship:</strong>
            {{ this.individual.relationship.name }}
        </div>

        <div class="form-group">
            <strong>Current Phone Number:</strong>
            {{ this.individual.current_phone_number}}
        </div>
    </div>
</div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            individual: '',
        }
    },
    methods: {
        getIndividual(){
            console.log("hi")
			this.$Progress.start();
			axios.get("/api/individuals/"+this.$route.params.id)
            .then(({data}) => {
                this.individual = data.data
            });
			this.$Progress.finish();
            
        }
    },
    created (){
        this.getIndividual()
    }
}
</script>
