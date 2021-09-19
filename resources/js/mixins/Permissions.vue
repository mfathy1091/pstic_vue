<script>
import Vue from 'vue';
    export default {
        
        data() {
            return{
                abilities: [],
            }
        },
        methods: {
            getAbitities(){
                axios.get('/api/abilities')
                .then(({data}) => {
                    this.abilities = data.data
                });

            },
                        
            
            $can(permissionName) {
                // this.PERMS = this.$Perm
                // Permissions = [];
                console.log('from mixin')
                console.log(this.abilities)
                return this.abilities.indexOf(permissionName) !== -1;
                
                // return this.foo.indexOf(permissionName) !== -1;
                // console.log(window.Perms)
                
            },

            // $getPermissions() {
            //     return this.Permissions;
            // }

        },
        mounted(){
            this.getAbitities()
        },
        computed:{
            
        }
    };
</script>