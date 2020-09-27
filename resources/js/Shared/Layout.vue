<script>
  import Header from './Header'

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
    background: rgb(234, 236, 238);
  }
</style>