<script lang='ts'>
  import Vue from 'vue'

  export default Vue.extend({
    name: 'Header',
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
  </header>
</template>

<style lang='scss' scoped>
  #app-header {
    width: 100%;
    height: 60px;
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
  }
</style>