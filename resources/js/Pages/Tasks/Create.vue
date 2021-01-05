<script lang='ts'>
  import axios from 'axios'
  import Button from '../../Shared/Button.vue'
  import Input from '../../Shared/Input.vue'
  import Layout from '../../Shared/Layout.vue'
  import Select from '../../Shared/Select.vue'
  import Vue from 'vue'

  interface CreateData {
    description: string[]|null,
    link: string[]|null
    owner: string|null,
    parent: string[]|null,
    priority: string|null,
    resetForm: boolean,
    subscribers: string[]|null,
    taskTags: string[]|null,
    date: string|null,
    title: string|null,
    type: string[]|null,
  }

  interface Field {
    key: string,
    type: string,
    name: string,
    options?: any[]|null,
    placeholder: string,
    props?: string[],
    required?: boolean,
    disabled?: boolean,
    value: string|object
  }

  interface Response {
    data: {
      result?: string,
      error?: string
    }
  }

  export default Vue.extend({
    name: 'Create',
    components: {
      Button,
      Input,
      Layout,
      Select
    },
    props: {
      error: {
        type: String
      },
      priorities: {
        type: Array,
        required: true
      },
      result: {
        type: String
      },
      tags: {
        type: Array,
        required: true
      },
      tasks: {
        type: Array,
        required: true
      },
      types: {
        type: Array,
        required: true
      },
      users: {
        type: Array,
        required: true
      }
    },
    data(): CreateData {
      return {
        description: null,
        link: null,
        owner: null,
        parent: null,
        priority: null,
        resetForm: false,
        subscribers: null,
        taskTags: null,
        date: null,
        title: null,
        type: null,
      }
    },
    computed: {
      fieldsOne(): Field[] {
        return [
          {
            key: 'owner',
            name: 'Owner',
            options: this.users,
            placeholder: 'e.g. John Doe',
            props: ['id', 'name'],
            required: true,
            type: 'select',
            value: this.owner
          },
          {
            key: 'priority',
            name: 'Priority',
            options: this.priorities,
            placeholder: 'e.g. High',
            props: ['id', 'name'],
            required: true,
            type: 'select',
            value: this.priority
          },
          {
            key: 'date',
            name: 'Target Date',
            placeholder: 'e.g. December 15, 2020',
            type: 'date',
            value: this.date
          }
        ]
      },
      fieldsTwo(): Field[] {
        return [
          {
            key: 'title',
            name: 'Title',
            placeholder: 'e.g. Fix New Task Form',
            required: true,
            type: 'text',
            value: this.title
          },
          {
            key: 'subscribers',
            name: 'Subscribers',
            placeholder: 'e.g. John Doe, Jane Smith',
            options: this.users,
            props: ['id', 'name'],
            type: 'multi-select',
            value: this.users
          },
          {
            key: 'taskTags',
            name: 'Tags',
            placeholder: 'e.g. Email, Manual',
            options: this.tags,
            props: ['id', 'name'],
            type: 'multi-select',
            value: this.taskTags
          },
          {
            key: 'description',
            name: 'Description',
            placeholder: 'e.g. This task is for the new task creation page.',
            required: true,
            type: 'textarea',
            value: this.description
          }
        ]
      },
      fieldsThree(): Field[] {
        return [
          {
            key: 'parent',
            name: 'Parent Task',
            options: this.tasks,
            placeholder: 'e.g. Fix New Task Form',
            props: ['id', 'title'],
            type: 'select',
            value: this.parent
          },
          {
            key: 'type',
            name: 'Task Type',
            options: this.types,
            placeholder: 'e.g. Feature, Component',
            props: ['id', 'name'],
            type: 'select',
            value: this.type
          },
          {
            key: 'link',
            name: 'Link',
            placeholder: 'e.g. github.com/organization/repo',
            type: 'text',
            value: this.link
          }
        ]
      }
    },
    methods: {
      fieldUpdateHandler(key: string, value: string|number): void {
        this[key] = value;
      },
      getValidatedNewTaskPayload(): string|void {
        if (this.requiredFieldsAreComplete() === false) {
          throw new Error('Please complete all required fields.');
        }

        let payload = {
          owner: this.owner,
          title: this.title,
          description: this.description,
          link: this.link,
          parent: this.parent,
          priority: this.priority,
          subscribers: this.subscribers,
          date: this.date,
          tags: this.taskTags,
          type: this.type
        };

        return JSON.stringify(payload);
      },
      resetFormHandler(): void {
        this.description = null;
        this.link = null;
        this.owner = null;
        this.parent = null;
        this.priority = null;
        this.subscribers = null;
        this.taskTags = null;
        this.date = null;
        this.title = null;
        this.type = null;

        this.resetForm = true;
        Vue.nextTick(() => {
          this.resetForm = false;
        });
      },
      requiredFieldsAreComplete(): boolean {
        return [...this.fieldsOne, ...this.fieldsTwo, ...this.fieldsThree]
          .filter((field: any): boolean => field.hasOwnProperty('required'))
          .every((field: any): boolean => ['', null].includes(field.value) === false);
      },
      submitNewTaskHandler(): void {
        try {
          const payload: string = this.getValidatedNewTaskPayload();
          const vm = this;

          this.$inertia.post('/tasks', payload, {
            replace: false,
            preserveState: true,
            preserveScroll: false,
            _token: this.csrfToken,
            onFinish: () => {
              if (vm.error !== undefined) {
                alert(vm.error);
                return;
              }

              alert(vm.result);
              vm.resetFormHandler();
            }
          });
        } catch (e) {
          alert(e.message);
        }
      }
    }
  })
</script>

<template>
  <Layout>
    <div id='create-task'>
      <h4 class='font-weight-bold mb-4'>NEW TASK</h4>
      <form id='task-form'>
        <section
          id='grid-one'
          class='p-3 rounded mb-4'>
          <div
            :key='`${key}-1`'
            v-for='(field, key) in fieldsOne'>
            <Input
              :heading='field.name'
              headingStyle='background:#fff;'
              :placeholder='field.placeholder'
              :reset='resetForm'
              :type='field.type'
              wrapClasses='w-100 m-0'
              v-if="['text', 'date'].includes(field.type)"
              @update='fieldUpdateHandler(field.key, $event)'/>
            <Select
              returnKey='id'
              :heading='field.name'
              headingStyle='background:#fff;'
              listStyle='background:rgb(244, 246, 247);'
              :options='field.options'
              :placeholder='field.placeholder'
              :targetProps='field.props'
              :reset='resetForm'
              :type='field.type'
              wrapClasses='w-100 m-0'
              v-if="['select', 'multi-select'].includes(field.type)"
              @update='fieldUpdateHandler(field.key, $event)'/>
          </div>
        </section>
        <section
          id='grid-two'
          class='p-3 rounded mb-4'>
          <div
            :key='`${key}-1`'
            v-for='(field, key) in fieldsTwo'>
            <Input
              :heading='field.name'
              headingStyle='background:#fff;'
              :placeholder='field.placeholder'
              :reset='resetForm'
              :type='field.type'
              wrapClasses='w-100 m-0'
              v-if="['text', 'date'].includes(field.type)"
              @update='fieldUpdateHandler(field.key, $event)'/>
            <Select
              returnKey='id'
              :heading='field.name'
              headingStyle='background:#fff;'
              listStyle='background:rgb(244, 246, 247);'
              :options='field.options'
              :placeholder='field.placeholder'
              :targetProps='field.props'
              :reset='resetForm'
              :type='field.type'
              wrapClasses='w-100 m-0'
              v-if="['select', 'multi-select'].includes(field.type)"
              @update='fieldUpdateHandler(field.key, $event)'/>
            <label
              v-if="field.type === 'textarea'"
              class='rounded mb-0 d-block'>
              <span><b>DESCRIPTION</b></span>
              <textarea
                class='p-3 rounded d-block h-100 w-100'
                placeholder='e.g. This task is for the login page.'
                v-model='description'></textarea>
            </label>
          </div>
        </section>
        <section
          id='grid-three'
          class='p-3 rounded mb-4'>
            <div
            :key='`${key}-3`'
            v-for='(field, key) in fieldsThree'>
            <Input
              :heading='field.name'
              headingStyle='background:#fff;'
              :placeholder='field.placeholder'
              :reset='resetForm'
              :type='field.type'
              wrapClasses='w-100 m-0'
              v-if="['text', 'date'].includes(field.type)"
              @update='fieldUpdateHandler(field.key, $event)'/>
            <Select
              returnKey='id'
              :heading='field.name'
              headingStyle='background:#fff;'
              listStyle='background:rgb(244, 246, 247);'
              :options='field.options'
              :placeholder='field.placeholder'
              :targetProps='field.props'
              :reset='resetForm'
              :type='field.type'
              wrapClasses='w-100 m-0'
              v-if="['select', 'multi-select'].includes(field.type)"
              @update='fieldUpdateHandler(field.key, $event)'/>
          </div>
        </section>
        <Button
          text='Submit'
          :reset='resetForm'
          theme='processing'
          classes='rounded text-white font-weight-bold py-3'
          styling='background:#00203FFF;width:250px;max-width:95%'
          @click='submitNewTaskHandler'/>
      </form>
    </div>
  </Layout>
</template>

<style lang='scss' scoped>
  #create-task {

    #task-form {
      width: 100%;

      #grid-one,
      #grid-three {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 15px;
        background: #fff;
        @include boxShadow(0 0 10px rgba(0,0,0,0.1));
      }

      #grid-two {
        display: grid;
        grid-gap: 15px;
        background: #fff;
        grid-template-columns: 1fr 2fr;
        grid-template-rows: auto;
        grid-template-areas:
          "top ."
          "middle description"
          "bottom .";
        @include boxShadow(0 0 10px rgba(0,0,0,0.1));

        > * {

          &:first-of-type {
            grid-area: top;
          }

          &:nth-of-type(2) {
            grid-area: middle;
          }

          &:nth-of-type(3) {
            grid-area: bottom;
          }

          &:last-of-type {
            height: 100%;
            grid-area: description;
            grid-row: 1 / 4;

            label {
              height: 100%;
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

  @media screen and (max-width: 850px) {
    #create-task {

      #task-form {

        #grid-one,
        #grid-three {
          grid-template-columns: repeat(1, 1fr);
        }

        #grid-two {
          grid-template-columns: unset;
          grid-template-rows: repeat(1, 1fr);
          grid-template-areas:
            "top"
            "middle"
            "bottom"
            "description";

          > * {

            &:last-of-type {
              height: 150px;
              grid-row: auto;
            }
          }
        }
      }
    }
  }
</style>