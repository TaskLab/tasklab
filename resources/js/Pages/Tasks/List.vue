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
      links: {
        type: Array,
        validator(links): boolean {
          return links.every((link: any): boolean => {
            return link.hasOwnProperty('key')
              && link.hasOwnProperty('path')
              && typeof link.key === 'string'
              && typeof link.path === 'string'
          })
        }
      },
      resultsRequestURL: {
        required: true,
        type: String
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
          columns:'60px 100px auto 230px 220px 150px 150px 150px 220px',
          gap:'10px'
        }
      }
    }
  })
</script>

<template>
  <Layout>
    <Results
      :items='tasks'
      :links='links'
      :fields='fields'
      :checkable='true'
      :gridConfig='gridConfig'
      :resultsRequestURL='resultsRequestURL'/>
  </Layout>
</template>