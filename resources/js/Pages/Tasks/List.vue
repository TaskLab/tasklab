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
      filters: {
        type: [Array, Object]
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
      newItemPath: {
        type: String
      },
      getRequestURL: {
        required: true,
        type: String
      },
      tasks: {
        type: Object,
        required: true
      }
    },
    data(): ListData {
      return {
        gridConfig: {
          minWidth: '1800px',
          columns:'100px auto 230px 220px 200px 150px 150px 150px 220px',
          gap:'10px'
        }
      }
    }
  })
</script>

<template>
  <Layout>
    <div id='tasks'>
      <h4 class='mb-5 font-weight-bold'>Tasks</h4>
      <Results
        :links='links'
        :fields='fields'
        :filters='filters'
        :propItems='tasks'
        :gridConfig='gridConfig'
        :newItemPath='newItemPath'
        :getRequestURL='getRequestURL'/>
    </div>
  </Layout>
</template>