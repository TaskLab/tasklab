<script lang='ts'>
  import Input from '../Shared/Input.vue'
  import Select from '../Shared/Select.vue'
  import SwitchBtn from '../Shared/SwitchBtn.vue'
  import Vue from 'vue'

  export default Vue.extend({
    name: 'Results',
    components: {
      Input,
      Select,
      SwitchBtn
    },
    props: {
      fields: {
        type: [Array, Object],
        required: true
      },
      gridConfig: {
        type: Object,
        validator(config): boolean {
          return typeof config === 'object'
            && config.hasOwnProperty('columns')
            && config.hasOwnProperty('gap');
        }
      },
      items: {
        type: [Array, Object],
        required: true
      }
    },
    methods: {
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
  <main id='results-container'>
    <section id='results'>
      <ul class='m-0 p-2'>
        <li
          class='headers'
          :style='[getResultRowStyling()]'>
          <span
            :key='`f-${key}`'
            class='pb-3 px-3'
            v-for='(field, key) in fields'>
            <b>{{ field.toUpperCase() }}</b>
          </span>
        </li>
        <li
          :key='`rr-${key}`'
          class='result-row rounded mb-2'
          :style='[getResultRowStyling()]'
          v-for='(item, key) in items'>
          <span
            class='cell p-3'
            :key='`rc-${key}`'
            v-for='(field, key) in fields'>
            {{ getResultFieldValue(item, key) }}
          </span>
        </li>
      </ul>
    </section>
  </main>
</template>

<style lang='scss' scoped>
  #results-container {
    width: 100%;
    padding: 35px;
    min-height: calc(100vh - 60px);

    #results {
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
          @include boxShadow(0 0 10px rgba(0,0,0,0.1));

          .cell {
            display: block;
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