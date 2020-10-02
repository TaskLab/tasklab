<script lang='ts'>
  import Layout from '../Shared/Layout.vue'
  import Vue from 'vue'

  interface RegisterData {
    verifypassword: string,
    firstname: string,
    focusIndex: number,
    lastname: string,
    email: string,
    organization: string,
    password: string
  }

  export default Vue.extend({
    name: 'Register',
    components: {
      Layout
    },
    props: {
      error: {
        type: String
      },
      success: {
        type: String
      }
    },
    data(): RegisterData {
      return {
        verifypassword: '',
        firstname: '',
        focusIndex: 0,
        lastname: '',
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
            heading: 'First Name',
            value: this.firstname
          },
          {
            type: 'text',
            name: 'lastname',
            required: true,
            heading: 'Last Name',
            value: this.lastname
          },
          {
            type: 'text',
            name: 'email',
            required: true,
            heading: 'Email',
            value: this.email
          },
          {
            type: 'text',
            name: 'organization',
            heading: 'Organization',
            value: this.organization
          },
          {
            type: 'password',
            name: 'password',
            required: true,
            heading: 'Password',
            value: this.password
          },
          {
            type: 'password',
            name: 'verifypassword',
            required: true,
            heading: 'Verify Password',
            value: this.verifypassword
          }
        ]
      }
    },
    watch: {
      error() {
        if (this.error !== undefined) {
          alert(this.error);
        }
      },
      success() {
        if (this.success !== undefined) {
          alert(this.success);
        }
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
          throw new Error('Please use a valid email.');
        }

        const payload: any = {
          firstname: this.firstname,
          lastname: this.lastname,
          email: this.email,
          organization: this.organization,
          password: this.password,
          verifypassword: this.verifypassword
        };

        return JSON.stringify(payload);
      },
      registerHandler(): void {
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
      hasFieldsUnderFiveChars(): boolean {
        return this.elements
          .filter(
            (el: any): any => el.hasOwnProperty('required') && (el.name !== 'firstname' && el.name !== 'lastname')
          )
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
        <section id='app-summary' class='px-4'>
          <h3>what is lorem ipsum</h3>
          <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
          <h3>why do we use it</h3>
          <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.</p>
          <h3>where can i get some</h3>
          <p>All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.</p>
        </section>
        <form class='p-3 rounded'>
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
            @click='registerHandler'>
            Register
          </button>
        </form>
      </section>
    </div>
  </Layout>
</template>

<style lang='scss' scoped>
  #registration-page {
    width: 100vw;
    min-height: calc(100vh - 60px);
    background: rgb(234, 236, 238);
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
    }
  }

  .red {
    color: #dc3545!important; 
  }
</style>