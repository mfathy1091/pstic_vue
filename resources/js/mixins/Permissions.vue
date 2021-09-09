<script>
    export default {
        methods: {
            $can(permissionName) {
                // Permissions = [];
                return Permissions.indexOf(permissionName) !== -1;
            },

            $getPermissions() {
                return Permissions;
            }
        },
    };
</script>