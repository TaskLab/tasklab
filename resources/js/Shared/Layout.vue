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

  ul {
    list-style-type: none;
  }

  #app {
    overflow: hidden;
  }

  #wrapper {
    width: 100%;
    padding: 35px;
    max-width: 1600px;
    background: #F4F8F9;
    min-height: calc(100vh - 60px);
    @include transition(all 0.2s ease-out);
  }
</style>