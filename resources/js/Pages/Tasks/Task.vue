<script lang='ts'>
  import Breadcrumbs from '../../Shared/Breadcrumbs.vue';
  import Layout from '../../Shared/Layout.vue';
  import Select from '../../Shared/Select.vue';
  import Vue from 'vue'

  export default Vue.extend({
    name: 'Task',
    components: {
      Breadcrumbs,
      Layout,
      Select
    },
    props: {
      states: {
        type: Array
      },
      statuses: {
        type: Array
      },
      task: {
        type: Object
      },
      users: {
        type: Array
      }
    },
    computed: {
      crumbs(): any[] {
        return [
          {
            name: 'Tasks',
            link: '/tasks'
          },
          {
            name: this.task.title,
            link: `/tasks/${this.task.id}`
          }
        ]
      }
    },
    mounted(): void {
      const descriptionArray: string[] = this.task.description.split('\n \n');
      const descriptionContent: string = descriptionArray
        .map((part, idx) => (idx === descriptionArray.length - 1)
          ? `<p style='margin:0;'>${part}</p>`
          : `<p>${part}</p>`
        )
        .join('');

      this.$refs.description.innerHTML = descriptionContent;
    },
  })
</script>

<template>
  <Layout>
    <div id='task'>
      <Breadcrumbs
        wrapClasses='p-0'
        :crumbs='crumbs'/>
      <h4 class='mb-5'>Task #{{ task.id }}</h4>
      <section class='grid-col-3 mb-3'>
        <p><b>Title:</b> {{ task.title }}</p>
        <p><b>Author:</b> {{ task.author.name }}</p>
        <p><b>Created:</b> {{ new Date(task.created_at).toLocaleDateString() }}</p>
      </section>
      <section class='grid-col-3 mb-4'>
        <Select
          :options='users'
          wrapClasses='w-100'
          placeholder='e.g. John Doe'
          :defaultOption='task.owner_id'
          headingStyle='background:#f4f8f9;'
          :targetProps="['id','name']"
          heading='Owner'/>
        <Select
          :options='statuses'
          wrapClasses='w-100'
          placeholder='e.g. Testing'
          :defaultOption='task.status_id'
          headingStyle='background:#f4f8f9;'
          :targetProps="['id','name']"
          heading='Status'/>
        <Select
          :options='states'
          wrapClasses='w-100'
          placeholder='e.g. Closed'
          :defaultOption='task.state_id'
          headingStyle='background:#f4f8f9;'
          :targetProps="['id','name']"
          heading='State'/>
      </section>
      <p><b>Description:</b></p>
      <div class='description p-3 rounded' ref='description'></div>
    </div>
  </Layout>
</template>

<style lang='scss' scoped>
  #task {

    .grid-col-3 {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-gap: 30px;

      p {
        padding: 14px 0;
        text-align: center;
        border: $light-dark-slim;
        @include borderRadius(0.25rem);
      }
    }

    .description {
      background: rgba(0,0,0,0.1);
    }
  }
</style>