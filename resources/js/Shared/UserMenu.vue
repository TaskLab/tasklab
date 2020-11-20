<script lang='ts'>
  import axios from 'axios'
  import Vue from 'vue'

  interface UserMenuData {
    items: {
      icon: string,
      name: string,
      path?: string
    }[]
  }

  export default Vue.extend({
    name: 'UserMenu',
    mounted(): void {
      setTimeout(() => {
        this.$refs.usermenu.classList.add('active');
      });
    },
    beforeDestroy(): void {
      this.$refs.usermenu.classList.remove('active');
    },
    data(): UserMenuData {
      return {
        items: [
          {
            icon: 'fas fa-sign-out-alt',
            name: 'Log out',
            path: '/logout'
          }
        ]
      }
    },
    methods: {
      async clickHandler(path: string): Promise<void> {
        const res = await axios.post('/logout');
        this.$store.commit('updateAuthenticatedUser', null);
        window.location.href = '/';
      }
    }
  })
</script>

<template>
  <div
    id='user-menu'
    ref='usermenu'
    class='rounded p-2'>
    <ul class='p-0 mb-0'>
      <li
        :key='key'
        class='p-3 rounded'
        v-for='(item, key) in items'
        @click='clickHandler(item.path)'>
        <i :class='item.icon'></i>
        <span class='font-weight-bold'>{{ item.name }}</span>
      </li>
    </ul>
  </div>
</template>

<style lang='scss' scoped>
  #user-menu {
    opacity: 0;
    position: absolute;
    top: 60px;
    left: 120%;
    width: 100%;
    @include transform(translateX(0));
    @include transition(all 0.2s ease-out);
    @include boxShadow(0 0 10px rgba(0,0,0,0.2));

    li {
      display: grid;
      cursor: pointer;
      grid-column-gap: 15px;
      list-style-type: none;
      grid-template-columns: 16px auto;

      span {
        line-height: 16px;
      }

      &:hover {
        color: #fff;
        background: #00203FFF;
      }
    }

    &.active {
      opacity: 1;
      @include transform(translateX(-233px));
    }
  }
</style>