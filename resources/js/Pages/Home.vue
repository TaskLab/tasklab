<script lang='ts'>
  import Button from '../Shared/Button.vue'
  import Layout from '../Shared/Layout.vue';
  import Vue from 'vue'
  import ApexCharts from 'apexcharts';

  type HomeData = {
    taskBtns: {
      classes: string,
      path: string,
      styling: string,
      text: string,
      title: string
    }[]
  }

  export default Vue.extend({
    name: 'Home',
    mounted(): void {
      if (this.user) {
        this.$store.commit('updateAuthenticatedUser', this.user);
      }

      // @todo turn mock data into database driven data. This is just a place holder until I finish updating the databases.
      const chart = new ApexCharts(document.querySelector("#chart"), this.chartOptions);
      chart.render();
    },
    components: {
      Button,
      Layout
    },
    props: {
      user: {
        type: Object
      }
    },
    computed: {
      chartOptions(): any {
        return {
          chart: {
            type: 'bar',
            height: 350,
            stacked: true,
            toolbar: {
              show: true
            },
            zoom: {
              enabled: true
            }
          },
          series: [{
            name: 'Org Assigned',
            data: [151,220,115,1]
          },
          {
            name: 'Org Unassigned',
            data: [115,120,330,0]
          },
          {
            name: 'Assigned to me',
            data: [15,20,15,1]
          }],
          xaxis: {
            categories: ['Low', 'Mid', 'High', 'Critical']
          },
          yaxis: {
            title: {
              text: 'Org Tasks'
            }
          },
          legend: {
            position: 'right',
            offsetY: 40
          },
          fill: {
            opacity: 1
          }
        }
      }
    },
    data(): HomeData {
      return {
        taskBtns: [
          {
            text: 'Create a Task',
            path: '/tasks/create',
            title: 'Create a new task',
            styling: 'background:#00203FFF;height:300px',
            classes: 'd-block py-3 w-100 mx-auto rounded font-weight-bold text-light'
          },
          {
            path: '/tasks',
            text: 'View Tasks',
            title: 'View Tasks',
            styling: 'background:#00203FFF;height:300px',
            classes: 'd-block py-3 w-100 mx-auto rounded font-weight-bold text-light'
          }
        ]
      }
    }
  })
</script>

<template>
  <Layout>
    <div id='home'>
      <h4 class='mb-5'>Dashboard</h4>
      <section
        id='task-btn-grid'>
        <Button
          :key='key'
          :path='btn.path'
          :text='btn.text'
          :title='btn.title'
          :styling='btn.styling'
          :classes='btn.classes'
          v-for='(btn, key) in taskBtns'/>
          <div id='chart'></div>
      </section>
    </div>
  </Layout>
</template>

<style lang='scss' scoped>
  #home {

    #task-btn-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      grid-column-gap: 30px;
    }
    
    #chart {
      max-width: 1000px;
    }
  }
</style>