<script>
  export default {
    methods: {
      $hasRoles(roleNames) {
        return roleNames.some(item => window.UserRoles.includes(item));
      }
    },
  };
</script>
