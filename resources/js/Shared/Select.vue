<script lang='ts'>
  import Vue from 'vue'

  type Option = {
    id: string|number,
    name: string|number
  }

  type SelectData = {
    selectedOption: {id: string|number, name: string}|null,
    showOptionList: boolean
  }

  export default Vue.extend({
    name: 'Select',
    mounted(): void {
      document.addEventListener('click', e => this.toggleOptionsOnBlur(e));
    },
    destroyed(): void {
      document.removeEventListener('click', e => this.toggleOptionsOnBlur(e));
    },
    props: {
      defaultOption: {
        type: [Object, String, Number]
      },
      disableReset: {
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
      listClasses: {
        type: String
      },
      listStyle: {
        type: String
      },
      options: {
        type: Array,
        required: true
      },
      optionClasses: {
        type: String
      },
      listOptionStyle: {
        type: String
      },
      placeholder: {
        type: String
      },
      reset: {
        type: Boolean
      },
      returnKey: {
        type: String
      },
      selectedOptionClasses: {
        type: String
      },
      selectedOptionStyle: {
        type: String
      },
      targetProps: {
        type: Array,
        validator(value: string[]): boolean {
          return value.every(v => typeof v === 'string');
        }
      },
      wrapClasses: {
        type: String
      },
      wrapStyle: {
        type: String
      }
    },
    data(): SelectData {
      return {
        selectedOption: this.defaultOption || null,
        showOptionList: false
      }
    },
    watch: {
      reset(): void {
        if (this.reset === true) {
          this.resetHandler();
        }
      }
    },
    methods: {
      resetHandler(): void {
        this.selectedOption = null;
        this.showOptionList = false;
      },
      toggleOptionsOnBlur(e: MouseEvent|Event|null|undefined): void {
        if (this.showOptionList === false) {
          return;
        }

        e = e || event;
        const target: EventTarget = e?.target as EventTarget;
        if (this.$refs[`select-${this._uid}`].contains(target) === false) {
          this.showOptionList = false;
        }
      },
      updateSelectedOption(option: Option): void {
        this.selectedOption = option;
        this.showOptionList = false;

        this.$emit(
          'update',
          (this.returnKey !== undefined) ? option[this.returnKey] : option
        );
      }
    }
  })
</script>

<template>
  <div
    :style='wrapStyle'
    :class='wrapClasses'
    class='select-wrap pr-5'
    :ref='`select-${_uid}`'
    @click.self.stop='showOptionList = !showOptionList'>
    <span
      class='heading'
      :style='headingStyle'
      :class='headingClasses'
      @click.self.stop='showOptionList = !showOptionList'>
      {{ heading.toUpperCase() }}
    </span>
    <span
      class='placeholder'
      v-if='placeholder && selectedOption === null'
      @click.self.stop='showOptionList = !showOptionList'>
      {{ placeholder }}
    </span>
    <span
      class='selected-option'
      :style='selectedOptionStyle'
      :class='selectedOptionClasses'>
      {{ (selectedOption !== null && targetProps !== undefined)
        ? selectedOption[targetProps[targetProps.length - 1]]
        : selectedOption
      }}
    </span>
    <i
      v-if='selectedOption !== null && disableReset === false'
      class='fa fa-times reset-btn font-weight-bold p-2'
      @click='resetHandler'></i>
    <ul
      v-if='showOptionList'
      :class='listClasses'
      :style='listStyle'
      class='option-list m-0 p-0 w-100'>
      <li
        :key='`o-${key}`'
        class='option p-3'
        :class='optionClasses'
        :style='listOptionStyle'
        v-for='(option, key) in options'
        @click='updateSelectedOption(option)'>
        {{ (targetProps !== undefined)
          ? targetProps.map(p => option[p])
            .reduce((s, p) => s + '&nbsp; - &nbsp;' + p)
          : option
        }}
      </li>
    </ul>
  </div>
</template>

<style lang='scss' scoped>
  .select-wrap {
    height: 54px;
    width: 200px;
    cursor: pointer;
    position: relative;
    background: transparent;
    border: $light-dark-slim;
    @include userSelect(none);
    @include borderRadius(5px);

    .heading {
      position: absolute;
      top: -6px;
      left: 9px;
      padding: 0 7.5px;
      font-weight: bold;
      font-size: 0.7rem;
      color: #00203FFF;
      background: #d5d8dc;
    }

    .placeholder {
      color: rgba(0,0,0,0.4);
    }

    .placeholder,
    .selected-option {
      position: absolute;
      top: 15px;
      left: 16.5px;
    }

    .reset-btn {
      cursor: pointer;
      position: absolute;
      top: 10px;
      right: 12px;
    }

    .option-list {
      z-index: 1;
      background: #d5d8dc;
      position: absolute;
      top: 110%;
      left: 0;
      max-height: 250px;
      overflow-y: auto;
      border: $light-dark-slim;
      @include borderRadius(5px);

      li {
        list-style-type: none;

        &:hover {
          background: #00203FFF;
          color: white;
        }

        &:not(:last-of-type) {
          border-bottom: $light-dark-slim;
        }
      }
    }
  }
</style>