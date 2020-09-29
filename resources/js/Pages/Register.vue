<script lang='ts'>
  import Layout from '../Shared/Layout.vue'
  import Vue from 'vue'

  interface RegisterData {
    firstname: string,
    lastname: string,
    username: string,
    email: string,
    organization: string,
    password: string
  }

  export default Vue.extend({
    name: 'Register',
    components: {
      Layout
    },
    data(): RegisterData {
      return {
        firstname: '',
        lastname: '',
        username: '',
        email: '',
        organization: '',
        password: ''
      }
    },
    computed: {
      elements() {
        return [
          {
            type: 'text',
            name: 'firstname',
            required: true,
            placeholder: 'First Name',
            value: this.firstname
          },
          {
            type: 'text',
            name: 'lastname',
            required: true,
            placeholder: 'Last Name',
            value: this.lastname
          },
          {
            type: 'text',
            name: 'username',
            required: true,
            placeholder: 'Username',
            value: this.username
          },
          {
            type: 'text',
            name: 'email',
            required: true,
            placeholder: 'Email',
            value: this.email
          },
          {
            type: 'text',
            name: 'organization',
            placeholder: 'Organization',
            value: this.organization
          },
          {
            type: 'password',
            name: 'password',
            required: true,
            placeholder: 'Password',
            value: this.password
          }
        ]
      }
    },
    methods: {
      getValidatedPayload(): string {
        if (this.hasEmptyRequiredFields()) {
          throw new Error('Please fill out all required fields.');
        }

        if (this.hasFieldsUnderFiveChars()) {
          throw new Error('Please use field values greater than 5 characters.');
        }

        if (this.hasInvalidEmailFormat()) {
          throw new Error('Please fill all required fields with at least 5 characters and valid email.');
        }

        const payload: any = {
          firstname: this.firstname,
          lastname: this.lastname,
          username: this.username,
          email: this.email,
          organization: this.organization,
          password: this.password
        };

        return JSON.stringify(payload);
      },
      registerHandler(): void {
        try {
          const payload: string = this.getValidatedPayload();
          this.$inertia.post('/register', payload, {
            replace: false,
            preserveState: true,
            preserveScroll: false
          });
        } catch (e) {
          alert(e.message);
        }
      },
      hasEmptyRequiredFields(): boolean {
        return this.elements
          .filter((el: any): any => el.hasOwnProperty('required'))
          .map((el: any): string => el.value)
          .some((val: string): boolean => val.trim() === '');
      },
      hasFieldsUnderFiveChars(): boolean {
        return this.elements
          .filter((el: any): any => el.hasOwnProperty('required'))
          .map((el: any): string => el.value)
          .some((val: string): boolean => val.length < 5);
      },
      hasInvalidEmailFormat(): boolean {
        const regex: RegExp = /^\S+@\S+$/g;
        return this.email.search(regex) < 0;
      },
      updateFieldValue(field, e: KeyboardEvent): void {
        e = e || event;
        const element = e.target as HTMLInputElement;
        this[field] = element.value;
      }
    }
  })
</script>

<template>
  <Layout>
    <div id='registration-page'>
      <section id='center-module' class='mw-100'>
        <section id='app-summary' class='pt-4 px-4'>
          <h3>what is lorem ipsum</h3>
          <p class='mb-5'>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
          <h3>why do we use it</h3>
          <p class='mb-5'>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.</p>
          <h3>where can i get some</h3>
          <p>All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.</p>
        </section>
        <form class='p-3 rounded'>
          <input
            :key='key'
            :type='el.type'
            :name='el.name'
            :value='el.value'
            :autofocus='key === 0'
            :placeholder='el.placeholder'
            v-for='(el, key) in elements'
            @keyup='updateFieldValue(el.name, $event)'
            class='d-block p-3 mb-3 w-100 rounded'/>
          <button type='button' class='btn btn-primary d-block p-3 w-100' @click='registerHandler'>Register</button>
        </form>
      </section>
    </div>
  </Layout>
</template>

<style lang='scss' scoped>
  #registration-page {
    width: 100vw;
    min-height: calc(100vh - 60px);
    background: #d5d8dc;
    position: relative;

    #center-module {
      width: 900px;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      grid-column-gap: 40px;
      position: absolute;
      top: 45%;
      left: 50%;
      @include transform(translate(-50%, -50%));

      form {
        @include boxShadow(0 0 10px rgba(0,0,0,0.2));

        input {
          border: 1px solid rgba(0,0,0,0.1);

          &:active, &:focus {
            outline: none;
          }
        }
      }
    }
  }
</style>