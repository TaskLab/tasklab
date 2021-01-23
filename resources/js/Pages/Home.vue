<script lang='ts'>
  import axios from 'axios';
  import ApexCharts from 'apexcharts';
  import Button from '../Shared/Button.vue'
  import Layout from '../Shared/Layout.vue';
  import Vue from 'vue'

  type HomeData = {
    chartSeries: any[]|null,
    taskBtns: {
      classes: string,
      path: string,
      styling: string,
      text: string,
      title: string
    }[]
  }

  type OrgUserTaskStats = {
    assigned: number[],
    assignedToMe: number[],
    unassigned: number[]
  }

  export default Vue.extend({
    name: 'Home',
    mounted(): void {
      this.getOrgAndUserStats();

      if (this.user) {
        this.$store.commit('updateAuthenticatedUser', this.user);
      }
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
    data(): HomeData {
      return {
        chartSeries: null,
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
    },
    computed: {
      chartOptions(): any {
        return {
          chart: {
            type: 'bar',
            height: 500,
            stacked: true,
            toolbar: {
              show: true
            },
            zoom: {
              enabled: true
            }
          },
          legend: {
            position: 'top'
          },
          series: this.chartSeries,
          xaxis: {
            categories: ['Low', 'Mid', 'High', 'Critical']
          },
          yaxis: {
            title: {
              text: 'Org Tasks'
            }
          },
          fill: {
            opacity: 1
          }
        }
      }
    },
    methods: {
      async getOrgAndUserStats(): Promise<void> {
        try {
          const { data } = await axios.get('/dashboard/org-user-stats');
          if ('stats' in data) {
            this.setOrgUserTaskStatsData(data.stats);
          }
        } catch (err) {
          alert(err.message);
        }
      },
      setOrgUserTaskStatsData(stats: OrgUserTaskStats): void {
        let chartSeries: {name: string, data: number[]}[] = [
          {
            name: 'Assigned',
            data: stats['assigned']
          },
          {
            name: 'Unassigned',
            data: stats['unassigned']
          },
          {
            name: 'Assigned to me',
            data: stats['assignedToMe']
          }
        ];

        this.chartSeries = chartSeries;
        Vue.nextTick()
          .then(() => {
            const chartEl: HTMLElement = document.getElementById('chart')!;
            const chart: ApexCharts= new ApexCharts(chartEl, this.chartOptions);
            chart.render();
          });
      }
    }
  })
</script>

<template>
  <Layout>
    <div id='home'>
      <h4 class='mb-5'>Dashboard</h4>
      <section
        class='mb-4'
        id='task-btn-grid'>
        <Button
          :key='key'
          :path='btn.path'
          :text='btn.text'
          :title='btn.title'
          :styling='btn.styling'
          :classes='btn.classes'
          v-for='(btn, key) in taskBtns'/>
      </section>
      <div
        id='chart-wrapper'
        class='p-2 rounded'
        v-if='chartSeries !== null && chartSeries.length > 0'>
        <section id='chart'></section>
      </div>
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

    #chart-wrapper {
      background: #fff;
      @include boxShadow(0 0 10px rgba(0,0,0,0.1));

      #chart {
        width: 100%;
      }
    }
  }
</style>