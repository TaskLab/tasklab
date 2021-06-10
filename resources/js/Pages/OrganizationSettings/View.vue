<script lang='ts'>
  import Layout from '../../Shared/Layout.vue'
  import Vue from 'vue';

  export default Vue.extend({
    // @todo - make a real page out of this.
    // need to discuss with James on if we want to use one of those pre build components

    name: 'View',
    components: {
      Layout
    },
    props: {
      org: {
        type: [Array, Object],
        required: true
      },
      org_settings: {
        type: [Array, Object],
        required: true
      }
    }
  });
</script>

<template>
  <Layout>
    <div id='org-settings'>
      <h4 class='mb-5 font-weight-bold'>{{ org.name }}</h4>
      <p>{{org_settings}}</p>
    </div>
  </Layout>
</template>