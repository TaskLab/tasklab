<script lang='ts'>
  import Header from './Header.vue'
  import Vue from 'vue'

  export default {
    name: 'Layout',
    components: {
      Header
    }
  }
</script>

<template>
  <main>
    <header>

    </header>
    <section>
      <Header/>
      <slot/>
    </section>
  </main>
</template>

<style>
  * {
    font-family: 'Nunito';
    box-sizing: border-box;
  }

  body {
    background: rgb(213, 216, 220);
  }
</style>