<script lang='ts'>
  import Header from './Header.vue'
  import Vue from 'vue'

  export default {
    name: 'Layout',
    components: {
      Header
    },
    created(): void {
      if (this.$store.state.authenticatedUser === null) {
        const userJsonData: string|undefined|null = document.querySelector("meta[name='auth-user']")
          ?.getAttribute('content');

        if (typeof userJsonData === 'string' && userJsonData !== '') {
          this.$store.commit(
            'updateAuthenticatedUser',
            JSON.parse(userJsonData)
          );
        }
      }
    }
  }
</script>

<template>
  <main>
    <Header/>
    <slot/>
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