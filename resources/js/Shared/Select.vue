<script lang='ts'>
  import Vue from 'vue'

  interface SelectData {
    selectedOption: {id: string|number, name: string}|null,
    showOptionList: boolean
  }

  export default Vue.extend({
    name: 'Select',
    props: {
      heading: {
        type: String
      },
      headingClasses: {
        type: String
      },
      headingStyle: {
        type: String
      },
      options: {
        type: Array,
        required: true
      },
      optionClasses: {
        type: String
      },
      optionStyle: {
        type: String
      },
      placeholder: {
        type: String
      },
      returnKey: {
        type: Boolean
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
        selectedOption: null,
        showOptionList: false
      }
    },
    methods: {
      resetHandler(): void {
        this.selectedOption = null;
        this.showOptionList = false;
      },
      updateSelectedOption(option: {id: string|number, name: string}): void {
        this.selectedOption = option;
        this.showOptionList = false;

        this.$emit(
          'update',
          (this.returnKey === true) ? option.id : option
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
    <span class='selected-option' v-else>{{ selectedOption.name }}</span>
    <i
      v-if='selectedOption !== null'
      class='fa fa-times reset-btn font-weight-bold p-2'
      @click='resetHandler'></i>
    <ul
      v-if='showOptionList'
      class='option-list m-0 p-0 w-100'>
      <li
        :key='`o-${key}`'
        :style='optionStyle'
        :class='optionClasses'
        class='option p-3'
        v-for='(option, key) in options'
        @click='updateSelectedOption(option)'>
        {{ option.id }} | {{ option.name }}
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
    background: #d5d8dc;
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
      background: #d5d8dc;
      position: absolute;
      top: 110%;
      left: 0;
      border: $light-dark-slim;
      overflow: hidden;
      @include borderRadius(5px);

      li {
        list-style-type: none;

        &:hover {
          background: #00203FFF;
          color: white;
        }
      }
    }
  }
</style>