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
  <main id='app'>
    <Header/>
    <SideNav v-if='$store.state.authenticatedUser !== null'/>
    <div
      id='wrapper'
      class='mx-auto'>
      <slot/>
    </div>
  </main>
</template>

<style lang='scss'>
  * {
    font-family: 'Nunito';
    box-sizing: border-box;
  }

  body {
    background: #F4F8F9;
  }

  #wrapper {
    width: 100%;
    max-width: 1600px;
    background: #F4F8F9;
    padding: 100px 35px 35px;
    min-height: calc(100vh - 60px);
    @include transition(all 0.2s ease-out);
  }

  ul {
    list-style-type: none;
  }

  #app {
    overflow: hidden;
  }
</style>