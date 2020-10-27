<script lang='ts'>
  import Vue from 'vue'

  interface InputData {
    query: string|number
  }

  export default Vue.extend({
    name: 'Input',
    props: {
      autofocus: {
        type: Boolean,
        default: false
      },
      btnClasses: {
        type: String
      },
      btnStyle: {
        type: String
      },
      defaultVal: {
        type: [String, Number]
      },
      disabled: {
        type: Boolean
      },
      emitOnKeyUp: {
        type: Boolean,
        default: true
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
      inputStyle: {
        type: String
      },
      inputClasses: {
        type: String
      },
      minVal: {
        type: Number
      },
      maxVal: {
        type: Number
      },
      placeholder: {
        type: String
      },
      reset: {
        type: Boolean
      },
      required: {
        type: Boolean
      },
      type: {
        type: String,
        default: 'text',
        validator(value: string): boolean {
          return ['text', 'password', 'number', 'date'].includes(value);
        }
      },
      value: {
        type: [String, Number]
      },
      wrapClasses: {
        type: String
      },
      wrapStyle: {
        type: String
      }
    },
    mounted(): void {
      if (this.value && this.type === 'date') {
        this.setFormattedDateAsQuery();
      }

      if (this.value) {
        this.query = (this.type === 'text')
          ? this.value.toString()
          : this.value;
      }
    },
    data(): InputData {
      return {
        query: this.defaultVal || '',
      }
    },
    computed: {
      showResetBtn(): boolean {
        if (
          [null, undefined].includes(this.defaultVal) === false
          || (typeof this.query === 'string' && this.query.trim() !== '')
        ) {
          return true;
        }

        return false;
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
      setFormattedDateAsQuery(): void {
        const dateArr: string[] = this.value.split('/')
          .map((char: string|number): any => {
            return (char < 10 && char > 0) ? '0' + char : char;
          });

        this.query = `${dateArr[2]}-${dateArr[0]}-${dateArr[1]}`;
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
    class='input-component'
    :class="wrapClasses || 'm-0'"
    :style='wrapStyle'>
    <input
      :type='type'
      :min='minVal'
      :max='maxVal'
      v-model='query'
      :spellcheck='false'
      :style='inputStyle'
      :disabled='disabled'
      :autofocus='autofocus'
      :placeholder='placeholder'
      :class="inputClasses || 'w-100 rounded pl-3 pr-5'"
      @blur='inputHandler($event.target.value)'
      @change='inputHandler($event.target.value)'
      @keyup='inputHandler($event.target.value)'/>
    <span
      v-if='heading'
      :style='headingStyle'
      class='input-heading font-weight-bold px-2'>
      {{ heading.toUpperCase() }}
      <span
        v-if='required && !query'
        :style="disabled ? { color: 'rgba(0,0,0,0.2)' } : { color: 'red' }">
        &nbsp;*
      </span>
    </span>
    <button
      type='button'
      :style='btnStyle'
      class='input-btn'
      :class='btnClasses'
      v-show='showResetBtn'
      @click='resetInputField'>
      <i class='fa fa-times d-block'></i>
    </button>
  </label>
</template>

<style lang='scss' scoped>
  .input-component {
    position: relative;

    input {
      height: 54px;
      background: transparent;
      border: $light-dark-slim;

      &:focus {
        outline: none;
      }

      &::placeholder {
        color: rgba(0,0,0,0.4);
      }
    }

    .input-heading {
      position: absolute;
      top: -6px;
      left: 9px;
      font-size: 0.7rem;
      color: #00203FFF;
      background: #d5d8dc;
    }

    .input-btn {
      cursor: pointer;
      position: absolute;
      top: 50%;
      right: 14px;
      border: none;
      color: black;
      font-weight: bold;
      background: transparent;
      @include borderRadius(50%);
      @include transform(translateY(-50%));

      &:focus {
        outline: none;
      }
    }
  }
</style>