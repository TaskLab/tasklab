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