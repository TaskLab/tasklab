<script lang='ts'>
  export default {
    name: 'InputField',
    props: {
      autofocus: {
        type: Boolean
      },
      dataTarget: {
        type: String
      },
      defaultVal: {
        type: [String, Number]
      },
      emitOnBlur: {
        type: Boolean
      },
      emitOnChange: {
        type: Boolean
      },
      heading: {
        type: String,
      },
      inputFor: {
        type: String
      },
      inputStyle: {
        type: String
      },
      inputType: {
        type: String
      },
      isDisabled: {
        type: Boolean
      },
      isRequired: {
        type: Boolean
      },
      labelClasses: {
        type: String
      },
      labelStyle: {
        type: String
      },
      placeholder: {
        type: String
      },
      maxVal: {
        type: Number
      },
      minVal: {
        type: [String, Number]
      },
      resetBtnStyle: {
        type: String
      },
      resetForm: {
        type: Boolean
      },
      wrapId: {
        type: String,
      }
    },
    mounted() {
      if (this.defaultVal && this.inputType === 'date') {
        this.setFormattedDateAsQuery()
      }

      if (this.defaultVal) {
        this.query = (this.inputType === 'text')
          ? this.defaultVal.toString()
          : this.defaultVal;
      }
    },
    data() {
      return {
        query: '',
      }
    },
    computed: {
      showResetBtn() {
        if (this.isDisabled === true) {
          return false;
        }

        if (this.inputType === 'number') {
          return (this.defaultVal === '' || this.defaultVal === undefined)
            ? false
            : true
        }

        if (this.inputType === 'text') {
          return (this.query.trim() === '' && ['', null, undefined].includes(this.defaultVal))
            ? false
            : true
        }
      }
    },
    watch: {
      defaultVal() {
        this.query = (this.inputType === 'text')
          ? this.defaultVal.toString()
          : this.defaultVal;
      },
      resetForm() {
        if (this.resetForm === true) {
          this.resetInputField();
        }
      }
    },
    methods: {
      setFormattedDateAsQuery() {
        const dateArr = this.defaultVal.split('/').map(char => {
          return (char < 10 && char > 0) ? '0' + char : char;
        });

        this.query = `${dateArr[2]}-${dateArr[0]}-${dateArr[1]}`;
      },
      inputChangeHandler(e) {
        e = e || event;
        if (this.emitOnChange || this.inputType === 'date' || this.emitOnBlur) {
          this.$emit(
            'updateFieldValue',
            e.target.value,
            this.dataTarget ? this.dataTarget : this.heading
          );
        }
      },
      resetInputField(e) {
        e = e || event;
        this.query = '';

        if (this.heading === undefined) {
          this.$emit('updateFieldValue', this.query);
          return;
        }

        if (this.inputFor) {
          this.$refs.inputField.setAttribute('placeholder', '');
          this.$refs.inputField.nextElementSibling.classList.remove('active');
          this.$emit('update', this.inputFor, this.query, e);
          return;
        }

          this.$refs.inputField.setAttribute('placeholder', this.placeholder || '');
          this.$emit('resetField', this.heading, e);
      }
    }
  }
</script>

<template>
  <label
    ref='label'
    :id='wrapId || ""'
    class='field-wrap d-block mb-4 mw-100'
    :class="labelClasses || ''"
    :style="labelStyle || ''">
    <input
      v-model='query'
      ref='inputField'
      spellcheck='false'
      :style="inputStyle || ''"
      :disabled='isDisabled ? true : false'
      :data-target="dataTarget || ''"
      :type="(inputType) ? inputType : 'text'"
      :placeholder='placeholder || ""'
      :min='(minVal) ? minVal : ""'
      :max='(maxVal) ? maxVal : ""'
      :autofocus="(autofocus) || false"
      class='form-control bg-transparent p-3'
      @blur="inputChangeHandler"
      @change="(inputType !== 'number' && emitOnBlur === undefined) ? inputChangeHandler : ''"
      @keyup="(emitOnBlur !== true) ? inputChangeHandler : ''">
    <span
      v-if="heading"
      ref='heading'
      class='d-block px-1 font-weight-bold'
      :style="isDisabled ? { color: 'rgba(0,0,0,0.2)' } : { color: '#007bff' }">
      {{ heading.toUpperCase() }}
      <span
        v-if='isRequired && !query'
        :style="isDisabled ? { color: 'rgba(0,0,0,0.2)' } : { color: 'red' }">
        &nbsp;*
      </span>
    </span>
    <button
      type='button'
      v-show='showResetBtn'
      :style='resetBtnStyle || ""'
      @click='resetInputField'
      :data-target="dataTarget || ''"
      class='border-0 text-center'>
      <i
        class='fa fa-times d-block'
        :data-target="dataTarget || ''"></i>
    </button>
  </label>
</template>

<style lang='scss' scoped>

  .field-wrap {
    position: relative;

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    input[type=number] {
      -moz-appearance: textfield;
    }

    input {
      height: 54px;
      padding-right: 55px;
      border: $light-dark-slim;
      @include boxShadow(none);

      &:focus {
        outline: 0;
        border-color: #80bdff !important;
        @include boxShadow(0 0 0 0.2rem rgba(0,123,255,0.25));
      }

      &::-webkit-outer-spin-button,
      &::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }

      &:disabled {
        cursor: not-allowed;
      }
    }

    > span {

      &:first-of-type {
        left: 11px;
        border: 0;
        z-index: 1;
        top: 0 !important;
        background: #fff;
        color: #007bff;
        font-size: 0.7rem;
        letter-spacing: 0.8px;
        @extend %abs-vert-center;
        @include borderRadius(10px);
        @include transition(all 0.2s ease-out);
      }
    }

    button {
      right: 10px;
      background: transparent;
      @extend %abs-vert-center;

      &:focus {
        outline: 0;
      }

      &:active {
        outline: 0;
      }

      i {
        width: 25px;
        height: 25px;
        line-height: 26px;
      }
    }
  }
</style>