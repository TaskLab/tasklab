<script lang='ts'>
  import Vue from 'vue';

  type CheckboxData = {
    isChecked: boolean
  }

  export default Vue.extend({
    name: 'Checkbox',
    props: {
      reset: {
        type: Boolean
      },
      setChecked: {
        type: Boolean
      },
      value: {
        type: String
      },
      wrapClasses: {
        type: String
      },
      wrapStyle: {
        type: String
      }
    },
    data(): CheckboxData {
      return {
        isChecked: this.setChecked || false
      }
    },
    watch: {
      reset(): void {
        if (this.reset) {
          this.isChecked = false;
        }
      }
    },
    methods: {
      toggleCheckedStatus(): void {
        this.isChecked = !this.isChecked;
        this.$emit('update', this.isChecked);
      }
    }
  })
</script>

<template>
  <label
    class='m-0'
    :style='wrapStyle'
    :class="[{ center: value === undefined }, wrapClasses]">
    <span class='d-inline-block rounded' @click.stop='toggleCheckedStatus'>
      <i v-show='isChecked' class='fa fa-check font-weight-bold'></i>
    </span>
    <span v-if='value !== undefined' class='d-inline-block ml-2'>{{ value }}</span>
    <input type='checkbox' hidden/>
  </label>
</template>

<style lang='scss' scoped>

  label {
    cursor: pointer;

    &.center {
      @extend %abs-center;
    }

    span {

      &:first-of-type {
        width: 20px;
        height: 20px;
        position: relative;
        vertical-align: middle;
        border: $light-dark-slim;

        i {
          font-size: 0.9rem;
          @extend %abs-center;
        }
      }

      &:last-of-type {
        vertical-align: middle;
      }
    }
  }
</style>