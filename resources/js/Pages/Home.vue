<script lang='ts'>
  import Input from '../Shared/Input.vue'
  import Layout from '../Shared/Layout.vue'
  import Vue from 'vue'

  export default Vue.extend({
    name: 'Home',
    components: {
      Input,
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