<script lang='ts'>
  import Vue from 'vue'

  interface Datalist {
    query: string
  }

  export default Vue.extend({
    name: 'Datalist',
    props: {
      emitOnKeyUp: {
        type: Boolean
      },
      heading: {
        type: String
      },
      headingClasses: {
        type: String
      },
      headingStyle: {
        type: String
      },
      inputClasses: {
        type: String
      },
      inputStyle: {
        type: String
      },
      options: {
        type: Array,
        required: true
      },
      placeholder: {
        type: String
      },
      reset: {
        type: Boolean
      },
      targetProp: {
        type: [String, Array],
        default: 'name'
      },
      wrapClasses: {
        type: String
      },
      wrapStyle: {
        type: String
      }
    },
    data(): Datalist {
      return {
        query: ''
      }
    },
    watch: {
      reset(): void {
        if (this.reset === true) {
          this.resetInputField();
        }
      }
    },
    methods: {
      getRenderedOptionValue(option: any): string {
        return (typeof this.targetProp === 'object')
          ? this.targetProp.map((p: string): string|number => option[p])
            .reduce((sum: string, part: string|number): string => sum + ' - ' + part)
          : option[this.targetProp];
      },
      inputHandler(value: string|number): void {
        const e: Event|FocusEvent|KeyboardEvent|undefined = event;

        if (e?.type === 'keyup' && this.emitOnKeyUp === false) {
          return;
        }

        this.$emit('update', value);
      },
      resetInputField(): void {
        this.query = '';
        this.$emit('update', '');
      }
    }
  })
</script>

<template>
  <label
    :class='wrapClasses'
    :style='wrapStyle'
    class='datalist-component'>
    <span
      :class='headingClasses'
      :style='headingStyle'
      class='heading font-weight-bold px-2'>
      {{ heading.toUpperCase() }}
    </span>
    <input
      v-model='query'
      :class='inputClasses'
      :style='inputStyle'
      :placeholder='placeholder'
      :list='`datalist-${_uid}`'
      @change='inputHandler($event.target.value)'
      @keyup='inputHandler($event.target.value)'
      @blur='inputHandler($event.target.value)'/>
    <datalist
      :id='`datalist-${_uid}`'
      @change='inputHandler($event.target.value)'>
      <option
        :key='key'
        :value='getRenderedOptionValue(option)'
        v-for='(option, key) in options'/>
    </datalist>
  </label>
</template>

<style lang='scss' scoped>
  .datalist-component {
    position: relative;

    .heading {
      position: absolute;
      top: -6px;
      left: 9px;
      font-size: 0.7rem;
      color: #00203FFF;
      background: #d5d8dc;
    }

    input {
      height: 54px;
      border: $light-dark-slim;
      background: #d5d8dc;
      padding: 1rem;
      @include borderRadius(0.25rem);

      &:focus {
        outline: none;
      }
    }
  }
</style>