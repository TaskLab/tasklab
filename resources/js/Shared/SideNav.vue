<script lang='ts'>
  import axios from 'axios'
  import { mapState } from 'vuex'
  import Vue from 'vue'

  interface SideNav {
    items: any[]|null
  }

  export default Vue.extend({
    name: 'SideNav',
    created(): void {
      this.getListOfNavigationItems();
    },
    data(): SideNav {
      return {
        items: null
      }
    },
    computed: {
      ...mapState(['showSideNav'])
    },
    methods: {
      async getListOfNavigationItems(): Promise<void> {
        const { data: { items }} = await axios.get('/navigation-items');
        this.items = items;
      }
    }
  })
</script>

<template>
  <nav
    id='side-nav'
    class='p-3'
    v-if='items !== null'
    :class="{ active: (showSideNav === true) }">
    <a
      :key='key'
      :href='item.path'
      v-for='(item, key) in items'
      class='rounded font-weight-bold py-3 px-4'>
      <span><i :class='item.icon'></i></span>
      <span>{{ item.name }}</span>
    </a>
  </nav>
</template>

<style lang='scss' scoped>
  #side-nav {
    z-index: 4;
    left: -300px;
    position: fixed;
    width: 300px;
    height: calc(100vh - 60px);
    background: #00203FFF;
    border-top: 1px solid rgba(0,0,0,0.3);
    @include transition(all 0.2s ease-out);

    &.active {
      @include transform(translateX(300px));
    }

    a {
      color: #fff;
      display: grid;
      grid-column-gap: 20px;
      text-decoration: none;
      grid-template-columns: 25px auto;
      @include transition(all 0.1s ease-out);

      &:hover {
        background: rgba(0,0,0,0.5);
      }

      &:not(:last-of-type) {
        margin-bottom: 15px;
      }
    }
  }
</style>