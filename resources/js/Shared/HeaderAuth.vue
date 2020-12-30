<script lang='ts'>
  import { mapState } from 'vuex'
  import UserMenu from './UserMenu.vue'
  import Vue from 'vue'

  interface HeaderAuthData {
    showUserMenu: boolean
  }

  export default Vue.extend({
    name: 'HeaderAuth',
    components: {
      UserMenu
    },
    mounted(): void {
      document.addEventListener('click', e => this.toggleUserMenu(e));
    },
    beforeDestroy(): void {
      document.removeEventListener('click', e => this.toggleUserMenu(e));
    },
    data(): HeaderAuthData {
      return {
        showUserMenu: false
      }
    },
    computed: {
      ...mapState(['authenticatedUser']),
      userInitials(): string {
        const nameArr: string[] = this.authenticatedUser.name.split(' ');
        return `${nameArr[0][0] + nameArr[nameArr.length - 1][0]}`;
      }
    },
    methods: {
      toggleUserMenu(e: MouseEvent): void {
        if (
          this.showUserMenu === true
          && e.target !== this.$refs.userLogo
          && e.target !== this.$refs.username
        ) {
          this.showUserMenu = false;
        }
      }
    }
  })
</script>

<template>
  <div
    ref='headerAuth'
    id='header-auth'>
    <span
      id='user-logo'
      ref='userLogo'
      class='d-inline-block font-weight-bold text-center'
      @click='showUserMenu = !showUserMenu'>
      {{ userInitials }}
    </span>
    <span
      id='username'
      ref='username'
      class='d-inline-block text-white font-weight-bold'
      @click='showUserMenu = !showUserMenu'>
      {{ this.authenticatedUser.name }}
    </span>
    <i class='fas fa-caret-down text-white d-inline-block'></i>
    <UserMenu v-if='showUserMenu'/>
  </div>
</template>

<style lang='scss' scoped>
  #header-auth {
    position: absolute;
    top: 50%;
    right: 10px;
    height: 42px;
    width: 195px;
    display: flex;
    justify-content: space-between;
    @include transform(translateY(-50%));

    #user-logo {
      width: 42px;
      height: 42px;
      cursor: pointer;
      line-height: 42px;
      position: relative;
      background: rgb(213, 216, 220);
      @include borderRadius(50%);
    }

    #username {
      width: 120px;
      cursor: pointer;
      white-space: nowrap;
      overflow: hidden;
      line-height: 42px;
      text-overflow: ellipsis;
    }

    i {
      line-height: 42px;
    }
  }
</style>