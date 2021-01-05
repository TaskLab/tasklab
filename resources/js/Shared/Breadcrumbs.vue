<script lang='ts'>
  import Vue from 'vue'

  type BreadcrumbsData = {
    displayedCrumbListIndex: number|null
  }

  type List = {
    condition?: boolean,
    icon: string,
    link: string,
    name: string
  }[]

  type ListItem = {
    condition?: boolean,
    icon: string,
    link: string,
    name: string
  }

  export default Vue.extend({
    name: 'Breadcrumbs',
    props: {
      crumbs: {
        type: Array,
        required: true
      },
      wrapClasses: {
        type: String
      },
      wrapStyle: {
        type: String
      }
    },
    mounted(): void {
      document.addEventListener('click', e => this.toggleCrumbListsOnOutclick(e));
    },
    beforeDestroy(): void {
      document.removeEventListener('click', e => this.toggleCrumbListsOnOutclick(e));
    },
    data(): BreadcrumbsData {
      return {
        displayedCrumbListIndex: null
      }
    },
    methods: {
      getCrumbListItems(list: List): List {
        return list.filter((item: ListItem): boolean => {
          return item.hasOwnProperty('condition') === false
            || item.hasOwnProperty('condition') && item.condition === true;
        })
      },
      toggleCrumbList(idx: number): void {
        if (this.displayedCrumbListIndex === idx) {
          this.displayedCrumbListIndex = null;
          return;
        }

        this.displayedCrumbListIndex = idx;
      },
      toggleCrumbListsOnOutclick(e: Event): void {
        e = e || event;
        const target = e.target as HTMLElement;
        const openCrumbList = document.querySelector('.crumb-list-wrap-' + this.displayedCrumbListIndex) as HTMLSpanElement;

        if (
          this.displayedCrumbListIndex !== null
          && openCrumbList.contains(target) === false
        ) {
          this.displayedCrumbListIndex = null;
        }
      }
    }
  })
</script>

<template>
  <ul
    id='breadcrumbs'
    :class='wrapClasses'
    :style='wrapStyle'>
    <li
      :key='`c-${key}`'
      class='d-inline-block'
      v-for='(crumb, key) in crumbs'>
      <span
        v-if="crumb.hasOwnProperty('list') === false">
        <a :href='crumb.link'>{{ crumb.name }}</a>
      </span>
      <span
        v-else
        :class='`crumb-list-wrap crumb-list-wrap-${key}`'>
        <span @click='toggleCrumbList(key)' :class='`crumb-list-name-${key}`'>
          {{ crumb.name }} &nbsp; <i class='fa fa-caret-down'></i>
        </span>
        <ul
          v-show='displayedCrumbListIndex === key'
          class='crumblist p-1 rounded'>
          <li
            :key='key'
            v-for='(item, key) in getCrumbListItems(crumb.list)'>
            <a
              :href='item.link'
              class='p-2 rounded text-left'
              v-if="item.hasOwnProperty('link')">
              <i v-if="item.hasOwnProperty('icon')" :class='item.icon'></i>
              <span>{{ item.name }}</span>
            </a>
            <button
              type='button'
              class='p-2 w-100 rounded text-left'
              v-if="item.hasOwnProperty('action')"
              @click="$emit('print')">
              <i v-if="item.hasOwnProperty('icon')" :class='item.icon'></i>
              <span>{{ item.name }}</span>
            </button>
          </li>
        </ul>
      </span>
      <span
        class='text-secondary'
        v-if='crumbs.length > (key + 1)'>
        &nbsp; <i class='fa fa-chevron-right'></i> &nbsp;
      </span>
    </li>
  </ul>
</template>

<style lang='scss' scoped>
  #breadcrumbs {
    display: block;
    width: 100%;

    > li {
      margin-bottom: 20px;

      .crumb-list-wrap {
        position: relative;

        > span:first-of-type {
          cursor: pointer;
        }

        > ul {
          z-index: 1;
          position: absolute;
          left: 0;
          top: 150%;
          background: #fff;
          width: calc(100% + 20px);
          @include boxShadow(0 0 10px rgba(0,0,0,0.2));

          a {

            &:hover {
              text-decoration: none;
            }
          }

          button {
            border: none;
            color: black;
            font-size: 0.9rem;
            background: transparent;
            @include transition(all 0.2s ease-out);

            &:focus,
            &:active {
              outline: none;
            }

            &:hover {
              background: rgba(0,0,0,0.1);
            }

            i {
              line-height: 20px;
              margin-right: 12px;
              float: left;
            }

            span {
              float: left;
            }
          }

          a {
            display: grid;
            color: black;
            font-size: 0.9rem;
            grid-column-gap: 5px;
            grid-template-columns: 20px auto;
            @include transition(all 0.2s ease-out);

            &:hover {
              background: rgba(0,0,0,0.1);
            }

            i {
              line-height: 20px;
            }
          }
        }
      }
    }
  }
</style>