<script lang='ts'>
  import Header from './Header.vue'
  import SideNav from './SideNav.vue'
  import Vue from 'vue'

  export default {
    name: 'Layout',
    components: {
      Header,
      SideNav
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
    <SideNav v-if='$store.state.authenticatedUser !== null'/>
    <slot/>
  </main>
</template>

<style>
  * {
    font-family: 'Nunito';
    box-sizing: border-box;
  }

  body {
    background: #F4F8F9;
  }
</style>