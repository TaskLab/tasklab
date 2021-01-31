<script lang='ts'>
  import axios from 'axios'
  import Button from '../Shared/Button.vue'
  import Checkbox from '../Shared/Checkbox.vue'
  import Input from '../Shared/Input.vue'
  import Select from '../Shared/Select.vue'
  import SwitchBtn from '../Shared/SwitchBtn.vue'
  import Vue from 'vue'

  type ResultsData = {
    page: number
    filter: string|null,
    dataItems: Object|null,
    listElement: HTMLElement|null,
    horizontalScrollBtns: HTMLButtonElement[]|null,
    overflowShadows: HTMLDivElement[]|null,
    resultsPerPage: number,
    searchTimeout: ReturnType<typeof setTimeout> | null,
    query: string | null
  }

  type ResultsRequestParams = {
    page: number,
    query?: string,
    filter?: string,
    resultsPerPage: number
  }

  export default Vue.extend({
    name: 'Results',
    components: {
      Button,
      Checkbox,
      Input,
      Select,
      SwitchBtn
    },
    props: {
      checkable: {
        type: Boolean
      },
      fields: {
        type: [Array, Object],
        required: true
      },
      filters: {
        type: [Array, Object]
      },
      gridConfig: {
        type: Object,
        required: true,
        validator(config): boolean {
          return typeof config === 'object'
            && config.hasOwnProperty('columns')
            && config.hasOwnProperty('gap');
        }
      },
      propItems: {
        type: [Array, Object],
        required: true
      },
      links: {
        type: Array,
        validator(links: {key: string, path: string}[]): boolean {
          return links.every(f => {
            return f.hasOwnProperty('key')
              && f.hasOwnProperty('path')
              && typeof f.key === 'string'
              && typeof f.path === 'string';
          });
        }
      },
      getRequestURL: {
        type: String,
        required: true
      }
    },
    mounted(): void {
      let url: any = new URL(window.location.href);
      let params = new URLSearchParams(url.search);
      this.query = params.get('query') || null;
      this.setOverflowShadows();
    },
    updated(): void {
      this.setComponentElements();
    },
    beforeDestroy(): void {
      this.query = null;
    },
    data(): ResultsData {
      return {
        page: 1,
        filter: null,
        dataItems: null,
        listElement: null,
        horizontalScrollBtns: null,
        overflowShadows: null,
        resultsPerPage: 25,
        searchTimeout: null,
        query: null
      }
    },
    computed: {
      results(): any[] {
        return (this.dataItems)
          ? this.dataItems.data
          : this.propItems.data;
      }
    },
    methods: {
      createNewItem(): void {
        this.$inertia.get(this.newItemPath)
      },
      fieldIsLink(key: string): boolean {
        return this.links.map((l: {key: string}): string => l.key)
          .some((lk: string): boolean => lk === key);
      },
      getLinkPath(key: string, item: any): string {
        return this.links
          .find((link: {key: string}): boolean => link.key === key).path
          .replace(/({})/g, item[key]);
      },
      getResultFieldValue(item: any, key: string): string|number {
        return key.includes('.')
          ? (item[key.split('.')[0]] !== null)
            ? item[key.split('.')[0]][key.split('.')[1]]
            : ''
          : item[key];
      },
      async getResults(): Promise<void> {
        try {
          const params: ResultsRequestParams = this.getResultsRequestParams();
          const { data } = await axios.get(this.getRequestURL, { params });
          this.dataItems = data;
        } catch (err) {
          alert(err.message);
        }
      },
      getResultsRequestParams(): ResultsRequestParams {
        return {
          page: this.page,
          filter: this.filter,
          resultsPerPage: this.resultsPerPage,
          query: this.query,
        }
      },
      getResultRowStyling(): object {
        return {
          display: 'grid',
          'min-width': `${this.gridConfig.minWidth}`,
          'grid-template-columns': `${this.gridConfig.columns}`,
          'grid-column-gap': `${this.gridConfig.gap}`
        };
      },
      resetResultsHandler(): void {
        this.query = null;
        this.filter = this.filters[0] || null;
        this.page = 1;
        this.getResults();
      },
      resultsScrollHandler(): void {
        const listWrapWidth: number = this.listElement?.offsetWidth;
        const resultRowWidth: number = this.listElement.children[0].offsetWidth;
        const tableRowOverflow: number = resultRowWidth - listWrapWidth;
        const [leftScrollBtn, rightScrollBtn] = this.horizontalScrollBtns;
        const [leftShadow, rightShadow] = this.overflowShadows;

        if (this.listElement.scrollLeft > 0) {
          leftShadow.classList.add('active');
          leftScrollBtn.classList.add('active');
        } else {
          leftShadow.classList.remove('active');
          leftScrollBtn.classList.remove('active');
        }

        if (this.listElement.scrollLeft !== tableRowOverflow) {
          rightShadow.classList.add('active');
          rightScrollBtn.classList.add('active');
        } else {
          rightShadow.classList.remove('active');
          rightScrollBtn.classList.remove('active');
        }
      },
      resultsQueryUpdateHandler(value: string): void {
        if (value.trim() !== '' && value.trim() === this.query) {
          return;
        }

        if (value.trim() === '') {
          this.query = null;
          this.getResults();
          return;
        }

        this.query = value;
        if (this.searchTimeout) {
          clearTimeout(this.searchTimeout);
          this.searchTimeout = setTimeout(() => {
            this.getResults();
          }, 1000);

          return;
        }

        this.searchTimeout = setTimeout(() => {
          this.getResults();
        }, 1000);
      },
      setComponentElements(): void {
        this.overflowShadows = document.querySelectorAll('.overflow-shadow-' + this._uid);
        this.horizontalScrollBtns = document.querySelectorAll('.h-scroll-btn-' + this._uid);
        this.listElement = document.querySelector('.list-' + this._uid);

        if (this.overflowShadows && this.listElement) {
          this.setOverflowShadows();
        }
      },
      setOverflowShadows(): void {
        setTimeout(() => {
          const [, rightShadow] = this.overflowShadows;
          const [, rightScrollBtn] = this.horizontalScrollBtns;

          if (this.listElement.offsetWidth < this.listElement.children[0].offsetWidth) {
            rightShadow.classList.add('active');
            rightScrollBtn.classList.add('active');
            return;
          }

          rightShadow.classList.remove('active');
          rightScrollBtn.classList.remove('active');
        });
      },
      sideScrollHandler(side: string): void {
        const widthOfTable: number = this.listElement.offsetWidth;
        const resultRowWidth: number = this.listElement.children[0].offsetWidth;

        if (side === 'right') {
          this.listElement.scrollLeft = ((widthOfTable * 2) < resultRowWidth)
            ? this.listElement.scrollLeft + widthOfTable
            : (resultRowWidth - widthOfTable);
          return;
        }

        this.listElement.scrollLeft = ((widthOfTable * 2) < resultRowWidth)
          ? (this.listElement.scrollLeft - widthOfTable)
          : 0;
      },
      updateResultsFilter(value): void {
        this.filter = value;
        this.getResults();
      },
      updateResultsPerPage(value): void {
        this.resultsPerPage = value;
      }
    }
  })
</script>

<template>
  <main class='results'>
    <section
      class='result-tools mb-4 p-3 rounded'>
      <Input
        heading='Search'
        :defaultVal='query'
        wrapClasses='mr-2 mb-0'
        headingStyle='background:#fff;'
        placeholder='Search Results'
        @update='resultsQueryUpdateHandler'/>
      <Button
        text='Reset'
        styling='height:54px;background:#00203FFF;'
        classes='d-inline-block text-light px-5 mr-2 font-weight-bold'
        @click='resetResultsHandler'/>
      <Select
        heading='Per Page'
        :defaultOption='25'
        :disableReset='true'
        :options='[25,50,75]'
        headingStyle='background:#fff;'
        selectedOptionStyle='left:32px'
        wrapClasses='d-inline-block text-center mr-2'
        wrapStyle='width:90px;vertical-align:bottom;padding:0!important;'
        @update='updateResultsPerPage'/>
      <Select
        heading='Filter'
        :options='filters'
        :disableReset='true'
        headingStyle='background:#fff;'
        wrapClasses='d-inline-block mr-2'
        :defaultOption='filter || filters[0]'
        wrapStyle='width:175px;vertical-align:bottom;padding:0!important;'
        @update='updateResultsFilter'/>
      <Button
        icon='fas fa-plus'
        title='Create new item'
        styling='height:54px;background:#00203FFF;'
        classes='d-inline-block text-light px-4 mr-2 font-weight-bold'
        @click='createNewItem'/>
      <div class='d-inline-block total-wrap rounded'>
        <span>TOTAL</span>
        <span><b>{{ propItems.total }}</b></span>
      </div>
    </section>
    <section
      class='list-wrap'>
      <div :class='`overflow-shadow overflow-shadow-${_uid} rounded-left`'></div>
      <div :class='`overflow-shadow overflow-shadow-${_uid} rounded-right`'></div>
      <button
        type='button'
        title='Scroll Left'
        :class='`h-scroll-btn h-scroll-btn-${_uid}`'
        v-show='results !== null && results !== undefined && results.length > 0'
        @click="sideScrollHandler('left')">
        <i class='fas fa-arrow-left'></i>
      </button>
      <button
        type='button'
        title='Scroll Right'
        :class='`h-scroll-btn h-scroll-btn-${_uid}`'
        v-show='results !== null && results !== undefined && results.length > 0'
        @click="sideScrollHandler('right')">
        <i class='fas fa-arrow-right'></i>
      </button>
      <ul
        :class='`m-0 p-0 list-${_uid}`'
        @scroll='resultsScrollHandler'>
        <li
          class='headers mb-2'
          :style='[getResultRowStyling()]'>
          <span v-if='checkable'></span>
          <span
            :key='`f-${key}`'
            class='py-3 px-3'
            v-for='(field, key) in fields'>
            <b>{{ field.toUpperCase() }}</b>
          </span>
        </li>
        <li
          :key='`rr-${key}`'
          v-for='(result, key) in results'
          :style='[getResultRowStyling()]'
          class='result-row rounded mb-2'>
          <span
            class='cell'
            v-if='checkable'>
            <Checkbox/>
          </span>
          <span
            class='cell p-3'
            :key='`rc-${key}`'
            v-for='(field, key) in fields'>
            <inertia-link
              :href='getLinkPath(key, result)'
              v-if='links !== undefined && fieldIsLink(key)'>
              {{ getResultFieldValue(result, key) }}
            </inertia-link>
            <span v-else>
              {{ getResultFieldValue(result, key) }}
            </span>
          </span>
        </li>
      </ul>
    </section>
  </main>
</template>

<style lang='scss' scoped>
  .results {
    width: 100%;

    .result-tools {
      background: #fff;

      .total-wrap {
        height: 54px;
        padding: 0 35px;
        position: relative;
        border: $light-dark-slim;
        vertical-align: bottom;

        span {

          &:first-of-type {
            top: -6px;
            position: absolute;
            padding: 0 7.5px;
            font-weight: bold;
            font-size: 0.7rem;
            color: #00203FFF;
            background: white;
            @extend %abs-horiz-center;
          }

          &:last-of-type {
            @extend %abs-center;
          }
        }
      }
    }

    .list-wrap {
      width: 100%;
      overflow: auto;
      position: relative;

      .overflow-shadow {
        top: 0;
        width: 0px;
        height: 55px;
        position: absolute;

        &:nth-of-type(1) {
          left: 0;
          background: linear-gradient(to left, rgba(244,248,249,0.1), rgba(0,0,0,0.05));
        }

        &:nth-of-type(2) {
          right: 0;
          background: linear-gradient(to right, rgba(244,248,249,0.1), rgba(0,0,0,0.05));
        }

        &.active {
          width: 70px;
        }
      }

        .h-scroll-btn {
          top: 6px;
          display: none;
          position: absolute;
          cursor: pointer;
          margin-top: 1px;
          width: 40px;
          height: 40px;
          border: none;
          background: #fff;
          @include boxShadow(0 0 10px rgba(0,0,0,0.1));
          @include borderRadius(50%);

          &:first-of-type {
            left: 8px;
          }

          &:last-of-type {
            right: 8px;
          }

          &.active {
            display: block;
          }

          &:active, &:focus {
            outline: none;
          }
        }

      ul {
        scroll-behavior: smooth;
        list-style-type: none;
        @include overflowY(auto);

        .headers {
          color: rgb(155,172,181);
        }

        .result-row {
          background: #fff;

          .cell {
            display: block;
            position: relative;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
          }
        }

        &::-webkit-scrollbar {
          height: 10px;
        }

        &::-webkit-scrollbar-track {
          background: rgba(0,0,0,0.1);
          @include borderRadius(10px);
        }

        &::-webkit-scrollbar-thumb {
          background: rgba(0,0,0,0.1);
          @include borderRadius(10px);
        }
      }
    }
  }
</style>