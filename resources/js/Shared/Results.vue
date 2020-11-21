<script lang='ts'>
  import Button from '../Shared/Button.vue'
  import Checkbox from '../Shared/Checkbox.vue'
  import Input from '../Shared/Input.vue'
  import Select from '../Shared/Select.vue'
  import SwitchBtn from '../Shared/SwitchBtn.vue'
  import Vue from 'vue'

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
      }
    },
    computed: {
      results(): any[] {
        return this.items;
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
      getResultRowStyling(): object {
        return {
          display: 'grid',
          'min-width': `${this.gridConfig.minWidth}`,
          'grid-template-columns': `${this.gridConfig.columns}`,
          'grid-column-gap': `${this.gridConfig.gap}`
        };
      }
    }
  })
</script>

<template>
  <main id='results'>
    <h4 class='mb-5'>Tasks</h4>
    <section
      class='mb-4 p-3'
      id='result-tools'>
      <Input
        heading='Search'
        wrapClasses='mr-2 mb-0'
        headingStyle='background:#fff;'
        placeholder='Search Results'/>
      <Button
        text='Filter'
        styling='height:54px;background:#00203FFF;'
        classes='d-inline-block text-light px-5'/>
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