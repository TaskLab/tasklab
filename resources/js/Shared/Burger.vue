<script lang='ts'>
  import { mapState } from 'vuex'
  import Vue from 'vue'

  interface Burger {
    showSideNav: boolean
  }

  export default Vue.extend({
    name: 'Burger',
    computed: {
      ...mapState(['showSideNav'])
    },
    methods: {
      toggleSideNavigation(): void {
        this.$store.commit('toggleSideNavigation', !this.$store.state.showSideNav);
      }
    }
  })
</script>

<template>
  <section
    id='burger'
    @click='toggleSideNavigation'>
    <span class='layer d-block' :class="{ active: showSideNav === true }"></span>
    <span class='layer d-block' :class="{ active: showSideNav === true }"></span>
    <span class='layer d-block' :class="{ active: showSideNav === true }"></span>
  </section>
</template>

<style lang='scss' scoped>
  #burger {
    cursor: pointer;
    position: relative;
    width: 40px;
    height: 40px;
    position: absolute;
    top: 50%;
    left: 10px;
    @include userSelect(none);
    @include transform(translateY(-50%));

    .layer {
      width: 70%;
      height: 4px;
      left: 50%;
      position: absolute;
      background: white;
      @include borderRadius(20px);
      @include transition(all 0.2s ease-out);

      &:first-of-type {
        top: 10px;

        &.active {
          top: 50%;
          @include transform(translate(-50%, -50%) rotate(-45deg));
        }
      }

      &:last-of-type {
        bottom: 10px;

        &.active {
          top: 50%;
          @include transform(translate(-50%, -50%) rotate(45deg));
        }
      }

      &:first-of-type,
      &:last-of-type {
        @include transform(translateX(-50%));
      }

      &:nth-of-type(2) {
        top: 50%;
        @include transform(translate((-50%, -50%)));

        &.active {
          opacity: 0;
        }
      }
    }
  }
</style>