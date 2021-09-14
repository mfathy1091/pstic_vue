<script>
import Vue from 'vue';
    export default {
        
        data() {
            return{
                PERMS: [],
            }
        },
        methods: {
            $can(permissionName) {
                // this.PERMS = this.$Perm
                // Permissions = [];
                return this.PERMS.indexOf(permissionName) !== -1;
                // return this.foo.indexOf(permissionName) !== -1;
                // console.log(window.Perms)
                
            },

            // $getPermissions() {
            //     return this.Permissions;
            // }
        },
        computed:{
            foo: function () { this.PERMS = window.Perms }
        }
    };
</script>