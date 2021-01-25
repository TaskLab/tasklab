<script lang='ts'>
  import Burger from '../Shared/Burger.vue'
  import Button from '../Shared/Button.vue'
  import HeaderAuth from '../Shared/HeaderAuth.vue'
  import { mapState } from 'vuex'
  import Vue from 'vue'

  interface HeaderData {
    buttons: any[],
    timeout: ReturnType<typeof setTimeout>|null,
    onSmallWindow: boolean
  }

  export default Vue.extend({
    name: 'Header',
    components: {
      Burger,
      Button,
      HeaderAuth
    },
    // @jlrusso - (TODO) - move this resize code to main app.vue once data store is set
    mounted(): void {
      this.updateHeaderAuthButtons();
      window.addEventListener('resize', () => this.resizeEventHandler());
    },
    beforeDestroy(): void {
      window.removeEventListener('resize', () => this.resizeEventHandler());
    },
    data(): HeaderData {
      return {
        buttons: [
          {
            icon: '',
            text: 'Log in',
            title: 'Visit log-in page',
            theme: 'white',
            path: '/login',
            classes: 'px-4 font-weight-bold'
          },
          {
            icon: '',
            text: 'Register',
            title: 'Visit registration page',
            theme: 'white',
            path: '/register',
            classes: 'px-4 font-weight-bold'
          }
        ],
        timeout: null,
        onSmallWindow: false
      }
    },
    computed: {
      ...mapState(['authenticatedUser']),
      onLoginOrRegister(): boolean {
        return ['/login', '/register'].includes(window.location.pathname);
      }
    },
    methods: {
      resizeEventHandler(): void {
        if (this.timeout !== null) {
          clearTimeout(this.timeout);
          this.timeout = setTimeout(this.updateHeaderAuthButtons, 50);
          return;
        }

        this.timeout = setTimeout(this.updateHeaderAuthButtons, 50);
      },
      setHeaderAuthButtonContent(type: string): void {
        this.buttons = this.buttons.map((obj: any, idx: number): any => {
          if (idx === 0) {
            return {
              ...obj,
              text: (type === 'text') ? 'Log in' : '',
              icon: (type === 'icon') ? 'fas fa-user' : ''
            };
          }

          return {
            ...obj,
            text: (type === 'text') ? 'Register' : '',
            icon: (type === 'icon') ? 'fas fa-user-plus' : ''
          };
        });
      },
      updateHeaderAuthButtons(): void {
        const width: number = window.innerWidth
          || document.documentElement.clientWidth
          || document.body.clientWidth;

        if (width < 550 && this.onSmallWindow === false) {
          this.onSmallWindow = true;
          this.setHeaderAuthButtonContent('icon');
        }

        if (width > 550 && this.onSmallWindow === true) {
          this.onSmallWindow = false;
          this.setHeaderAuthButtonContent('text');
        }
      }
    }
  })
</script>

<template>
  <header id='app-header'>
    <Burger v-if='$store.state.authenticatedUser !== null'/>
    <span class='logo font-weight-bold text-light' v-if="onLoginOrRegister">tasklab</span>
    <inertia-link href='/' class='logo text-light font-weight-bold' v-else>tasklab</inertia-link>
    <section
      id='unauth-btns'
      v-if='onLoginOrRegister === false && authenticatedUser === null'>
      <Button
        :key='`hb-${key}`'
        :icon='btn.icon'
        :text='btn.text'
        :theme='btn.theme'
        :title='btn.title'
        :path='btn.path'
        :classes='btn.classes'
        v-for='(btn, key) in buttons'/>
    </section>
    <HeaderAuth v-if='authenticatedUser !== null'/>
  </header>
</template>

<style lang='scss' scoped>
  #app-header {
    z-index: 1;
    width: 100%;
    height: 60px;
    position: fixed;
    background: #00203FFF;
    border-bottom: 1px solid rgba(0,0,0,0.3);

    .logo {
      font-size: 25px;
      line-height: 60px;
      text-decoration: none;
      @include userSelect(none);
      @extend %abs-horiz-center;
    }

    #unauth-btns {
      right: 10px;
      width: 215px;
      height: 42px;
      display: flex;
      justify-content: space-between;
      @extend %abs-vert-center;
    }
  }

@media screen and (max-width: 800px) {
  #app-header {

    .logo {
      left: 15px;
      @include transform(none);
    }
  }
}

@media screen and (max-width: 550px) {
  #app-header {

    #unauth-btns {
      width: 142px;
    }
  }
}
</style>