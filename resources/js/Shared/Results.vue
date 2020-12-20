<script lang='ts'>
  import Button from '../Shared/Button.vue'
  import Checkbox from '../Shared/Checkbox.vue'
  import Input from '../Shared/Input.vue'
  import Select from '../Shared/Select.vue'
  import SwitchBtn from '../Shared/SwitchBtn.vue'
  import Vue from 'vue'

  type ResultsData = {
    page: number
    resultsPerPage: number,
    searchTimeout: ReturnType<typeof setTimeout> | null,
    query: string | null
  }

  type ResultsRequestParams = {
    page: number,
    query?: string,
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
      gridConfig: {
        type: Object,
        required: true,
        validator(config): boolean {
          return typeof config === 'object'
            && config.hasOwnProperty('columns')
            && config.hasOwnProperty('gap');
        }
      },
      items: {
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
      resultsRequestURL: {
        type: String,
        required: true
      }
    },
    mounted(): void {
      let url: any = new URL(window.location.href);
      let params = new URLSearchParams(url.search);
      this.query = params.get('query') || null;
    },
    data(): ResultsData {
      return {
        page: 1,
        resultsPerPage: 25,
        searchTimeout: null,
        query: null
      }
    },
    computed: {
      results(): any[] {
        return this.items.data;
      }
    },
    methods: {
      fieldIsLink(key: string): boolean {
        return this.links.map((l: {key: string}): string => l.key)
          .some((lk: string): boolean => lk === key);
      },
      getLinkPath(key: string, item: any): string {
        return this.links
          .find((link: {key: string}): boolean => link.key === key)
          .path + item[key];
      },
      getResultFieldValue(item: any, key: string): string|number {
        return key.includes('.')
          ? (item[key.split('.')[0]] !== null)
            ? item[key.split('.')[0]][key.split('.')[1]]
            : ''
          : item[key];
      },
      getResults(): void {
        const options = { preserveState: true };
        const params: ResultsRequestParams = this.getResultsRequestParams();
        this.$inertia.get(this.resultsRequestURL, params, options);
      },
      getResultsRequestParams(): ResultsRequestParams {
        return {
          page: this.page,
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
        this.page = 1;
        this.getResults();
      },
      resultsQueryUpdateHandler(value: string): void {
        if (value.trim() === '') {
          this.query = null;
          this.getResults();
          return;
        }

        this.query = value;
        if (this.searchTimeout === null) {
          this.searchTimeout = setTimeout(() => {
            this.getResults();
          }, 1000);
          return;
        }

        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
          this.getResults();
        }, 1000);
      },
      updateResultsPerPage(value): void {
        debugger;
      }
    }
  })
</script>

<template>
  <main id='results'>
    <section id='results-header'>
      <h4 class='mb-5 d-inline-block mr-3'>Tasks</h4>
      <small
        v-if='items !== undefined'
        class='text-secondary d-inline-block font-weight-bold mr-3'>
        {{ items.total }} Total
      </small>
      <small
        class='text-secondary d-inline-block font-weight-bold float-right'>
        <inertia-link href='/tasks/create'>Create a Task</inertia-link>
      </small>
    </section>
    <section
      id='result-tools'
      class='mb-4 p-3 rounded'>
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
        classes='d-inline-block text-light px-5 mr-2'
        @click='resetResultsHandler'/>
      <Select
        heading='Per Page'
        :defaultOption='25'
        :disableReset='true'
        :options='[25,50,75]'
        headingStyle='background:#fff;'
        selectedOptionStyle='left:32px'
        wrapClasses='d-inline-block text-center'
        wrapStyle='width:90px;vertical-align:bottom;padding:0!important;'
        @update='updateResultsPerPage'/>
    </section>
    <section id='results-list'>
      <ul class='m-0 p-0'>
        <li
          class='headers'
          :style='[getResultRowStyling()]'>
          <span v-if='checkable'></span>
          <span
            :key='`f-${key}`'
            class='pb-3 px-3'
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
  #results {
    width: 100%;

    #result-tools {
      background: #fff;
    }

    #results-list {
      width: 100%;
      overflow: auto;

      ul {
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