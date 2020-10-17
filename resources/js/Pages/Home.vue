<script lang='ts'>
  import Button from '../Shared/Button.vue'
  import Layout from '../Shared/Layout.vue';
  import Vue from 'vue'

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
    }
  })
</script>

<template>
  <Layout>
    <div id='home' class='mb-4'>
      <h1 class='text-center my-4'>Welcome</h1>
      <Button
        text='Create a Task'
        theme='processing'
        path='/tasks/create'
        title='Create a new task'
        styles='background:#00203FFF;width:200px;'
        classes='d-block py-3 mx-auto rounded font-weight-bold text-light'/>
    </div>
  </Layout>
</template>