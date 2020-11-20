<script lang='ts'>
  import Layout from '../../Shared/Layout.vue'
  import Results from '../../Shared/Results.vue'
  import Vue from 'vue'

  type ListData = {
    gridConfig: {
      columns: string,
      gap: string,
      minWidth: string
    }
  }

  export default Vue.extend({
    name: 'List',
    components: {
      Layout,
      Results
    },
    props: {
      fields: {
        type: [Array, Object],
        required: true
      },
      tasks: {
        type: [Array, Object],
        required: true
      }
    },
    data(): ListData {
      return {
        gridConfig: {
          minWidth: '1700px',
          columns:'100px auto 230px 220px 150px 150px 150px 220px',
          gap:'35px'
        }
      }
    }
  })
</script>

<template>
  <Layout>
    <Results
      :items='tasks'
      :fields='fields'
      :gridConfig='gridConfig'/>
  </Layout>
</template>