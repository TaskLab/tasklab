<script lang='ts'>
  import Button from '../Shared/Button.vue'
  import Layout from '../Shared/Layout.vue';
  import Vue from 'vue'

  type HomeData = {
    taskBtns: {
      classes: string,
      path: string,
      styling: string,
      text: string,
      title: string
    }[]
  }

  export default Vue.extend({
    name: 'Home',
    components: {
      Button,
      Layout
    },
    props: {
      user: {
        type: Object
      }
    },
    data(): HomeData {
      return {
        taskBtns: [
          {
            text: 'Create a Task',
            path: '/tasks/create',
            title: 'Create a new task',
            styling: 'background:#00203FFF;height:300px',
            classes: 'd-block py-3 w-100 mx-auto rounded font-weight-bold text-light'
          },
          {
            path: '/tasks',
            text: 'View Tasks',
            title: 'View Tasks',
            styling: 'background:#00203FFF;height:300px',
            classes: 'd-block py-3 w-100 mx-auto rounded font-weight-bold text-light'
          }
        ]
      }
    }
  })
</script>

<template>
  <Layout>
    <div id='home'>
      <h4 class='mb-5'>Dashboard</h4>
      <section
        id='task-btn-grid'>
        <Button
          :key='key'
          :path='btn.path'
          :text='btn.text'
          :title='btn.title'
          :styling='btn.styling'
          :classes='btn.classes'
          v-for='(btn, key) in taskBtns'/>
      </section>
    </div>
  </Layout>
</template>

<style lang='scss' scoped>
  #home {

    #task-btn-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      grid-column-gap: 30px;
    }
  }
</style>