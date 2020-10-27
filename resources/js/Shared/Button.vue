<script lang='ts'>
  import Vue from 'vue'

  interface ButtonData {
    processing: boolean
  }

  export default Vue.extend({
    name: 'Button',
    props: {
      classes: {
        type: String
      },
      disabled: {
        type: Boolean
      },
      icon: {
        type: [Object, String],
        validator(value: any): boolean {
          if (typeof value === 'object') {
            return (value.hasOwnProperty('position') && value.hasOwnProperty('class'))
          }

          return value.includes('fa-') || value === '';
        }
      },
      path: {
        type: String
      },
      reset: {
        type: Boolean
      },
      styles: {
        type: String
      },
      text: {
        type: String
      },
      title: {
        type: String
      },
      type: {
        type: String,
        default: 'button'
      },
      themes: {
        type: [Array, String],
        validator(value: string|string[]): boolean {
          return (typeof value === 'string')
            ? ['processing'].includes(value)
            : value.every((theme: string): boolean => {
              return ['processing'].includes(theme);
            });
        }
      }
    },
    data(): ButtonData {
      return {
        processing: false
      }
    },
    computed: {
      themeIsOrIncludesProcessing(): boolean {
        return (typeof this.theme === 'string' && this.theme === 'processing')
          || (typeof this.theme === 'object' && this.theme.includes('processing'))
      }
    },
    watch: {
      reset(): void {
        if (this.processing === true && this.reset === true) {
          this.processing = false;
          Vue.nextTick()
            .then(() => {
              this.reset = false;
            })
        }
      }
    },
    methods: {
      clickHandler(): void {
        if (this.themeIsOrIncludesProcessing === true) {
          this.processing = true;
        }

        if (this.path !== undefined) {
          this.$inertia.visit(this.path, { method: 'get' });
          return;
        }

        this.$emit('click');
      }
    }
  })
</script>

<template>
  <button
    :type='type'
    :style='styles'
    :class='classes'
    :title='title'
    :disable='disabled'
    class='btn-component rounded'
    @click='clickHandler'>
    <div
      class='text-center'
      v-if='themeIsOrIncludesProcessing && processing === true'>
      <i class='fas fa-spinner'></i>
    </div>
    <div v-else>
      <i
        :class='icon.class'
        v-if="['', undefined].includes(icon) == false && icon.position === 'before'"></i>
      {{ text }} <i v-if="typeof icon === 'string' && icon !== ''" :class='icon'></i>
      <i
        :class='icon.class'
        v-if="['', undefined].includes(icon) == false && icon.position === 'after'"></i>
    </div>
  </button>
</template>

<style lang='scss' scoped>
  .btn-component {
    border: none;
  }
</style>