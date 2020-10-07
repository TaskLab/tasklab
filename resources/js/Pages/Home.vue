<script lang='ts'>
  import Layout from '../Shared/Layout.vue';
  import Vue from 'vue'

  export default Vue.extend({
    name: 'Home',
    components: {
      Layout
    },
    props: {
      user: {
        type: Object
      }
    }
  })
</script>

<template>
  <Layout>
    
  </Layout>
</template>