<script lang='ts'>
  import Breadcrumbs from '../../Shared/Breadcrumbs.vue';
  import Input from '../../Shared/Input.vue';
  import Layout from '../../Shared/Layout.vue';
  import Select from '../../Shared/Select.vue';
  import Vue from 'vue'

  type TaskData = {
    cc: string,
    message: string,
    resetForm: boolean,
    showReplyForm: boolean,
    subject: string,
    to: string
  }

  export default Vue.extend({
    name: 'Task',
    components: {
      Breadcrumbs,
      Input,
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
    data(): TaskData {
      return {
        to: '',
        cc: '',
        resetForm: false,
        subject: '',
        showReplyForm: false,
        message: ''
      }
    },
    computed: {
      authuser(): object {
        return this.$store.state.authenticatedUser
      },
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
    methods: {
      updateReplyFormField(field: string, value: string): void {
        this[field] = value;
      }
    }
  })
</script>

<template>
  <Layout>
    <div id='task' class='p-2'>
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
        <p v-if='task.owner_id'><b>Owner:</b> {{ task.owner.name }}</p>
        <Select
          v-else
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
      <div class='description p-3 rounded mb-4' ref='description'></div>
      <section
        id='reply-forward-wrap'
        v-if='authuser && authuser.id === task.owner_id && showReplyForm === false'>
        <button
          type='button'
          id='reply-btn'
          @click='showReplyForm = !showReplyForm'
          class='d-inline-block px-5 mr-3 rounded text-light'>
          <b>Reply</b>
        </button>
        <button
          type='button'
          id='reply-btn'
          @click='showReplyForm = !showReplyForm'
          class='d-inline-block px-5 rounded text-light'>
          <b>Forward</b>
        </button>
      </section>
      <hr v-else/>
      <section
        id='reply-wrap'
        v-if='showReplyForm === true'>
        <div class='fields-wrap'>
          <div class='rounded-top p-3'>
            <Input
              type='text'
              heading='To'
              :required='true'
              :reset='resetForm'
              wrapClasses='w-100 m-0'
              headingStyle='background:#fff;'
              placeholder='e.g. jdoe@email.com'
              @update="updateReplyFormField('to', $event)"/>
            <Input
              type='text'
              heading='CC'
              :reset='resetForm'
              wrapClasses='w-100 m-0'
              headingStyle='background:#fff;'
              placeholder='e.g. jdoe@email.com'
              @update="updateReplyFormField('cc', $event)"/>
            <Input
              type='text'
              heading='Subject'
              :reset='resetForm'
              wrapClasses='w-100 m-0'
              headingStyle='background:#fff;'
              placeholder='e.g. Solution for this task'
              @update="updateReplyFormField('subject', $event)"/>
          </div>
          <div class='rounded-bottom px-3 pb-3 mb-3'>
            <label
              class='rounded mb-0 d-block'>
              <span><b>DESCRIPTION</b></span>
              <textarea
                class='p-3 rounded d-block h-100 w-100'
                placeholder='e.g. This task is for the login page.'
                v-model='message'></textarea>
            </label>
          </div>
        </div>
        <button
          type='button'
          class='d-inline-block px-5 mr-3 rounded text-light form-footer-btn'
          v-if='authuser && authuser.id === task.owner_id'>
          <b>Send</b>
        </button>
        <button
          type='button'
          @click='showReplyForm = false'
          class='d-inline-block bg-danger px-5 rounded text-light form-footer-btn'
          v-if='authuser && authuser.id === task.owner_id'>
          <b>Cancel</b>
        </button>
      </section>
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

    .form-footer-btn {
      height: 54px;
      border: none;

      &:first-of-type {
        background: rgb(0, 32, 63);
      }

      &:active, &:focus {
        outline: none;
      }
    }

    #reply-forward-wrap {

      button {
        height: 54px;
        border: none;
        background: rgb(0, 32, 63);
      }
    }

    #reply-wrap {

      .fields-wrap {
        @include boxShadow(0 0 10px rgba(0,0,0,0.1));

        > div {
          background: #fff;

          &:first-of-type {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 15px;
          }

          &:last-of-type {

            label {
              height: 150px;
              position: relative;

              span {
                position: absolute;
                top: -6px;
                left: 9px;
                padding: 0 7.5px;
                font-weight: bold;
                font-size: 0.7rem;
                color: #00203FFF;
                background: #fff;
              }

              textarea {
                background: transparent;
                border: $light-dark-slim;

                &:focus {
                  outline: none;
                }

                &::placeholder {
                  color: rgba(0,0,0,0.4);
                }
              }
            }
          }
        }
      }
    }
  }
</style>