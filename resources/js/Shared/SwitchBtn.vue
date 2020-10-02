<script lang='ts'>
  import Vue from 'vue';

  interface SwitchBtnData {
    selectedOp: any,
    value: any
  }

  export default Vue.extend({
    name: 'SwitchBtn',
    props: {
      defaultVal: {
        type: [Boolean, String, Number]
      },
      fieldKey: {
        type: String
      },
      firstOp: {
        type: Object
      },
      name: {
        type: String
      },
      namePosition: {
        type: String
      },
      pillClasses: {
        type: String
      },
      pillStyle: {
        type: String
      },
      secondOp: {
        type: Object
      },
      styleType: {
        type: [String, Number],
        validator(value) {
          return ['1', '2'].indexOf(value.toString()) > -1
        }
      },
      startOp: {
        type: String
      },
      wrapClasses: {
        type: String
      },
      wrapStyle: {
        type: String
      }
    },
    mounted(): void {
      if (this.startOp === 'second') {
          this.selectedOp = this.secondOp;
          this.$refs.circle.classList.add('switch');
          this.$refs.selectedOp.classList.add('switch');
      }

      if ([2, '2'].includes(this.styleType)) {
        this.setDefaultSwitchValue();
      }
    },
    data(): object {
      return {
        selectedOp: this.firstOp,
        value: this.defaultVal || ''
      }
    },
    computed: {
      pill(): HTMLDivElement|null {
        return document.querySelector('.pill-' + this._uid) as HTMLDivElement;
      }
    },
    watch: {
      defaultVal(): void {
        this.value = this.defaultVal;
        this.setDefaultSwitchValue();
      }
    },
    methods: {
      setDefaultSwitchValue(): void {
        if (this.defaultVal === true) {
          if (this.pill.classList.contains('active') === false) {
            this.pill.classList.add('active');
            return;
          }
        }

        if (this.defaultVal === false) {
          if (this.pill.classList.contains('active')) {
            this.pill.classList.remove('active');
            return;
          }
        }
      },
      toggleSwitchVal(): void {
        if (this.selectedOp.value === this.firstOp.value) {
          this.selectedOp = this.secondOp;
          this.$refs.circle.classList.add('switch');
          this.$refs.selectedOp.classList.add('switch');
          this.$emit('switch', this.secondOp.value);
          return;
        }

        this.selectedOp = this.firstOp;
        this.$refs.circle.classList.remove('switch');
        this.$refs.selectedOp.classList.remove('switch');
        this.$emit('switch', this.firstOp.value);
      },
      toggleType2Switch(): void {
        this.value = (this.value === false || this.value === '') ? true : false;
        if (this.pill.classList.contains('active')) {
          this.pill.classList.remove('active');
        } else {
          this.pill.classList.add('active');
        }

        this.$emit('switch', this.value);
      }
    },
  })
</script>

<template>
  <section
    id='switch-wrap'
    class='mr-3 style-1'
    :class='wrapClasses'
    @click='toggleSwitchVal'
    :style='`${wrapStyle}  ${selectedOp.style}`'
    v-if="[1, '1', undefined].includes(styleType)">
    <span class='circle d-block bg-secondary' ref='circle'></span>
    <span ref='selectedOp' class='selected-val font-weight-bold d-block' >
      {{ selectedOp.name }}
    </span>
  </section>
  <section
    v-else
    id='switch-wrap'
    :style='wrapStyle'
    class='style-2'
    :class='wrapClasses'
    @click='toggleType2Switch'>
    <span
      v-if="namePosition === 'left'"
      class='font-weight-bold d-inline-block switch-name'>
      {{ name }} &nbsp;
    </span>
    <div
      :style='`${pillStyle}`'
      :class='[`pill-${_uid} pill d-inline-block`, `${pillClasses}`]'>
      <span class='circle d-inline-block bg-light text-center'>
        <i v-if='value' class='fas fa-check text-primary'></i>
      </span>
    </div>
    <span
      v-if="namePosition === 'right'"
      class='font-weight-bold d-inline-block switch-name'>
      &nbsp; {{ name }}
    </span>
  </section>
</template>

<style lang='scss' scoped>

  #switch-wrap.style-1 {
    cursor: pointer;
    height: 35px;
    position: relative;
    @include borderRadius(16px);
    border: $light-dark-slim;
    @include transition(all 2s ease-out);

    > .circle {
      width: 28.5px;
      height: 28.5px;
      left: 3px; right: auto;
      @extend %abs-vert-center;
      @include borderRadius(50%);
      @include boxShadow(0 0 5px rgba(0,0,0,0.1));
      @include transition(all 2s ease-out);

      &.switch {
        left: auto;
        right: 3px;
      }
    }

    > .selected-val {
      left: auto; right: 8px;
      @extend %abs-vert-center;
      @include transition(all 2s ease-out);

      &.switch {
        left: 8px;
        right: auto;
      }
    }
  }

  #switch-wrap.style-2 {
    cursor: pointer;

    .pill {
      width: 65px;
      height: 35px;
      position: relative;
      border: $light-dark-slim;
      @include borderRadius(20px);
      background: rgba(0,0,0,0.1);
      @include transition(all 0.2s ease-out);

      &.active {
        background: #007bff;

        .circle {
          left: auto;
          right: 3px;
        }
      }

      .circle {
        width: 28.5px;
        height: 28.5px;
        left: 3px; right: auto;
        @extend %abs-vert-center;
        @include borderRadius(50%);
        @include boxShadow(0 0 5px rgba(0,0,0,0.1));
        @include transition(all 0.2s ease-out);

        i {
          position: absolute;
          top: 55%;
          left: 50%;
          line-height: 28.5px;
          @include transform(translate(-50%,-50%));
        }
      }
    }

    .switch-name {
      line-height: 35px;
      vertical-align: top;
    }
  }
</style>