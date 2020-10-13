<script lang='ts'>
  import Layout from '../Shared/Layout.vue';
  import Vue from 'vue';
  import Input from '../Shared/Input.vue';
  import { mapState } from 'vuex';
  import SwitchBtn from '../Shared/SwitchBtn.vue';
  import Vue from 'vue';

  interface MissingOrgType {
    orgVal: string,
    useOrg: boolean
  }

  export default Vue.extend({
    name: 'Home',
    components: {
      Layout,
      Input,
      SwitchBtn
    },
    data(): MissingOrgType {
      return {
        orgVal: '',
        useOrg: false
      };
    },
    methods: {
      handleSwitch(e: Event): void {
        this.useOrg = e;
      },
      getPayload(): string {
        const payload: any = {
          value: this.orgVal,
          is_org: this.useOrg
        };

        return JSON.stringify(payload);
      },
      orgVals(e: any): void {
        this.orgVal = e;
      },
      submitPayload(): void {
        try {
          const payload: string = this.getPayload();
          this.$inertia.post('/org/create', payload, {
            replace: false,
            preserveState: true,
            preserveScroll: false,
            _token: this.csrfToken
          });
        } catch (e) {
          alert(e.message);
        }
      },
    },
    computed: {
      heading(): string {
        return this.useOrg ? 'New Organization' : 'Enter Org-Key';
      },
      placeholder(): string {
        return this.useOrg ? 'A Task Inside' : '1WX93PA2';
      }
    },
  })
</script>

<template>
  <Layout>
    <section id='center-module' class='mw-100'>
      <div class='message pt-5'>
        <h3>You're not a member of an organization yet!</h3>
        <p>Trying to join an organization? If so enter your registration key below.</p>
        <p>Or create a new organization and start getting busy!</p>
      </div>
      <SwitchBtn
        :styleType='1'
        wrapStyle='line-height:45px;margin-top:3px;width:112px;display:inline-flex;'
        :firstOp='{name: "New Org", value: false}'
        :secondOp='{name: "Org Key", value: true}'
        @switch="handleSwitch"/>
      <Input
        v-model='orgVal'
        @update='orgVals'
        :heading='heading'
        :defaultVal='orgVal'
        headingStyle='background:white;'
        inputStyle='border:1px solid rgba(0,0,0,0.2);background:white;'
        wrapClasses='d-block my-3 mx-auto'
        wrapStyle='width:500px;'
        :placeholder='placeholder'/>
      <button class='btn btn-primary' @click='submitPayload'>Submit</button>
    </section>
  </Layout>
</template>

<style lang='scss' scoped>
  #center-module {
    margin: 60px;
    padding: 20px;
    background: white;
    text-align: center;
    align-content: center;
  }
</style>