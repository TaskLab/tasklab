<script lang='ts'>
  import Button from '../Shared/Button.vue'
  import Vue from 'vue'

  interface HeaderData {
    buttons: any[]
  }

  export default Vue.extend({
    name: 'Header',
    components: {
      Button
    },
    data(): HeaderData {
      return {
        buttons: [
          {
            text: 'Log in',
            theme: 'white',
            path: '/login',
            classes: 'px-4 font-weight-bold'
          },
          {
            text: 'Register',
            theme: 'white',
            path: '/register',
            classes: 'px-4 font-weight-bold'
          }
        ]
      }
    },
    computed: {
      onLoginOrRegister(): boolean {
        return ['/login', '/register'].includes(window.location.pathname);
      }
    }
  })
</script>

<template>
  <header id='app-header'>
    <h3 class='text-center font-weight-bold'>
      <span v-if="onLoginOrRegister">tasklab</span>
      <inertia-link href='/' class='text-light' v-else>tasklab</inertia-link>
    </h3>
    <section
      id='unauth-btns'
      v-if='onLoginOrRegister === false'>
      <Button
        :key='`hb-${key}`'
        :text='btn.text'
        :theme='btn.theme'
        :path='btn.path'
        :classes='btn.classes'
        v-for='(btn, key) in buttons'/>
    </section>
  </header>
</template>

<style lang='scss' scoped>
  #app-header {
    width: 100%;
    height: 60px;
    position: relative;
    background: #00203FFF;

    h3 {
      color: #fff;
      margin: 0;
      line-height: 60px;

      a, span {
        text-decoration: none;
        @include userSelect(none);
      }
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
</style>