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
      },
      toggleSideNavigation(): void {
        this.$store.commit('toggleSideNavigation', !this.showSideNav);
      }
    }
  })
</script>

<template>
  <div
    id='nav-wrap'
    @click.self='toggleSideNavigation'
    :class="{ active: (showSideNav === true) }">
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
  </div>
</template>

<style lang='scss' scoped>
  #nav-wrap {
    top: 60px;
    left: 0;
    z-index: 1;
    width: 100%;
    display: none;
    position: fixed;
    cursor: pointer;
    height: calc(100vh - 60px);
    background: rgba(0,0,0,0.3);

    &.active {
      display: block;
    }

    #side-nav {
      z-index: 4;
      left: -300px;
      position: fixed;
      width: 300px;
      cursor: default;
      height: calc(100vh - 60px);
      background: #00203FFF;
      border-right: 1px solid rgba(0,0,0,0.3);
      @include transition(all 0.2s ease-out);

      &.active {
        @include transform(translateX(300px));
      }

      a {
        color: #fff;
        display: grid;
        cursor: pointer;
        grid-column-gap: 20px;
        text-decoration: none;
        background: rgba(0,0,0,0.3);
        grid-template-columns: 25px auto;

        &:hover {
          background: rgba(0,0,0,0.6);
        }

        &:not(:last-of-type) {
          margin-bottom: 15px;
        }
      }
    }
  }
</style>