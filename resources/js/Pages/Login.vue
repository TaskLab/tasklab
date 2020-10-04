<script lang='ts'>
  import Layout from '../Shared/Layout.vue'
  import Vue from 'vue'

  interface LoginData {
    email: string,
    focusIndex: number,
    password: string
  }

  export default Vue.extend({
    name: 'Login',
    components: {
      Layout
    },
    data(): LoginData {
      return {
        email: '',
        focusIndex: 0,
        password: ''
      }
    },
    computed: {
      elements(): any[] {
        return [
          {
            type: 'text',
            name: 'email',
            required: true,
            heading: 'Email',
            value: this.email
          },
          {
            type: 'password',
            name: 'email',
            required: true,
            heading: 'Password',
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

        if (this.hasInvalidEmailFormat()) {
          throw new Error('Please use a valid email.');
        }

        const payload: any = {
          email: this.email,
          password: this.password
        };

        return JSON.stringify(payload);
      },
      loginHandler(): void {
        try {
          const payload: string = this.getValidatedPayload();
          this.$inertia.post('/register', payload, {
            replace: false,
            preserveState: true,
            preserveScroll: false,
            _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
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
    <div
      id='login'
      class='py-5'>
      <form class='p-3 rounded mb-4'>
        <h4 class='text-center mb-5 font-weight-bold'>Login</h4>
        <label
          :key='key'
          class='mb-3 w-100'
          v-for='(el, key) in elements'>
          <span :class="{ active: (key === focusIndex || el.value.trim() !== '')}">
            {{ el.heading }}
            <span v-if="el.value.trim() !== '' && el.name !== 'verifypassword'">&nbsp;<i class='fas fa-check'></i></span>
            <span v-if="el.value.trim() !== '' && el.name === 'verifypassword'">&nbsp;
              <i v-if='password === verifypassword' class='fas fa-check'></i>
              <i v-if='password !== verifypassword' class='fas fa-times red'></i>
            </span>
            <span
              class='font-weight-bold'
              v-if="key !== focusIndex && el.value.trim() === '' && el.required === true">*</span>
          </span>
          <input
            :type='el.type'
            :name='el.name'
            :value='el.value'
            :autofocus='key === 0'
            @blur='focusIndex = null'
            @focus='focusIndex = key'
            :spellcheck='false'
            @keyup='updateFieldValue(el.name, $event)'
            class='d-block pt-3 pb-2 pr-3 w-100'/>
        </label>
        <button
          type='button'
          class='btn text-light d-block p-3 w-100'
          @click='loginHandler'>
          Enter
        </button>
      </form>
      <section
        id='create-cta'
        class='p-3 rounded text-center'>
        <span>New to tasklab?</span>&nbsp; <inertia-link href='/register' method='get'>Create an account</inertia-link>
      </section>
    </div>
  </Layout>
</template>

<style lang='scss' scoped>
  #login {
    width: 100vw;
    min-height: calc(100vh - 60px);
    background: rgb(234, 236, 238);
    position: relative;

    form {
      width: 400px;
      margin: auto;
      max-width: 90%;
      box-shadow: -10px -10px 25px 0 rgba(255,255,255,0.65), 10px 10px 25px 0 rgba(80, 80, 80, 0.2);

      label {
        position: relative;

        input {
          border-top: none;
          border-left: none;
          border-right: none;
          background: transparent !important;
          border-bottom: 1px solid rgba(0,0,0,0.1);

          &:-webkit-autofill,
          &:-webkit-autofill:hover,
          &:-webkit-autofill:focus,
          &:-webkit-autofill:active {
              transition: background-color 5000s ease-in-out 0s;
          }

          &:active, &:focus {
            outline: none;
            border-bottom: 1px solid #00203FFF;
          }
        }

        > span {
          position: absolute;
          top: 14px;
          left: 2px;
          @include transition(all 0.1s ease-out);

          &.active {
            font-size: 0.7rem;
            color: rgba(0,0,0,0.5);
            @include transform(translateY(-14px));
          }

          span:first-of-type i {
            color: #28a745;
          }

          span {
            color: #dc3545;
          }
        }
      }

      button {
        background: #00203FFF;
      }
    }

    #create-cta {
      width: 400px;
      margin: auto;
      max-width: 90%;
      box-shadow: -10px -10px 25px 0 rgba(255,255,255,0.65), 10px 10px 25px 0 rgba(80, 80, 80, 0.2);
    }
  }

  @media screen and (max-width: 900px) and (max-height: 450px) and (orientation: landscape) {

  }
</style>