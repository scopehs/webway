<template>
  <v-row no-gutters justify="space-around">
    <v-col cols="12">
      <v-row no-gutters justify="space-around">
        <v-col cols="2">
          <v-card elevation="10" rounded="xl" class="mb-5" height="1000">
            <v-card-title class="justify-center primary pa-3">Options</v-card-title>
            <v-card-text
              ><v-row no-gutters
                ><v-col cols="12">
                  <v-card flat
                    ><v-card-text class="py-0"
                      ><span class="subheading"> Per Minutes</span
                      ><v-chip-group
                        active-class="primary--text"
                        column
                        v-model="minSelect"
                        @change="updateChartMins()"
                      >
                        <v-chip
                          v-for="(list, index) in minSelectItem"
                          :key="index"
                          filter
                          :value="list.value"
                          outlined
                          small
                        >
                          {{ list.text }}
                        </v-chip>
                      </v-chip-group>
                    </v-card-text>
                  </v-card>
                </v-col></v-row
              >
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="10">
          <v-card elevation="10" rounded="xl" class="mb-5" height="1000">
            <v-card-title class="justify-center primary pa-3"
              >All the Stats - By Hour</v-card-title
            >
            <v-card-text>
              <apexchart
                height="950"
                type="line"
                :options="chartOptions"
                :series="series"
              ></apexchart>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>
<script>
import { mapState } from "pinia";
import moment from "moment";
export default {
  props: {},
  async created() {
    Echo.private("charts").listen("ChartsUpdate", (e) => {
      if (e.flag.flag == 2) {
      }
      if (e.flag.flag == 3) {
      }
    });
  },

  async mounted() {
    await this.updateChartHour();
  },
  data() {
    return {
      series: [
        {
          name: "Sigs Added",
          data: [],
        },
      ],

      chartOptions: {
        chart: {
          id: "sig_count",
          foreColor: "#f8f8f8",
          type: "line",
          stacked: false,
          width: "100%",
          animations: {
            speed: 300,
          },
          zoom: {
            type: "x",
            enabled: true,
            autoScaleYaxis: true,
          },
          toolbar: {
            autoSelected: "zoom",
            tools: {
              download: false,
            },
          },
        },
        dataLabels: {
          enabled: false,
        },
        markers: {
          size: 0,
        },

        grid: {},
        title: {
          text: "",
          align: "left",
        },

        noData: {
          text: "Loading...",
        },

        yaxis: {
          title: {
            text: "Count",
          },
          // forceNiceScale: true,
          // tickAmount: 10,
        },

        xaxis: {
          type: "datetime",
        },

        markers: {
          hover: {
            sizeOffset: 4,
          },
        },
      },

      minSelectItem: [
        { text: "5", value: 5 },
        // { text: "Highsec", value: 7 },
        { text: "10", value: 10 },
        { text: "30", value: 30 },
      ],
      minSelect: [],
    };
  },

  methods: {
    async updateChartYear() {
      await this.$store.dispatch("getChartDataYear");
      const newuserCount = this.userCountData;
      const newsigsAddData = this.sigsAddData;
      const newsigsUpdateData = this.sigsUpdateData;
      const newsigsConnectionData = this.sigsConnectionData;
      const newsigsInfoUpdateData = this.sigsInfoUpdateData;

      this.chartOptions = {
        tooltip: {
          shared: true,
          theme: "dark",

          x: {
            show: true,
            style: {
              fontSize: "16px",
            },
            formatter: function (value) {
              let s = moment.utc(value).format("YYYY-MM-DD HH:mm:ss");
              let e = moment.utc(value).add(1, "y").format("YYYY-MM-DD HH:mm:ss");

              let test = s + " - " + e;
              return test;
            },
          },
        },
      };

      this.series = [
        {
          name: "User Count",
          data: newuserCount,
        },

        {
          name: "Sigs Added",
          data: newsigsAddData,
        },
        {
          name: "Sigs Updated",
          data: newsigsUpdateData,
        },

        {
          name: "Sigs Info Update",
          data: newsigsInfoUpdateData,
        },

        {
          name: "Connections Made",
          data: newsigsConnectionData,
        },
      ];
    },

    async updateChartMonth() {
      await this.$store.dispatch("getChartDataMonth");
      const newuserCount = this.userCountData;
      const newsigsAddData = this.sigsAddData;
      const newsigsUpdateData = this.sigsUpdateData;
      const newsigsConnectionData = this.sigsConnectionData;
      const newsigsInfoUpdateData = this.sigsInfoUpdateData;

      this.chartOptions = {
        tooltip: {
          shared: true,
          theme: "dark",

          x: {
            show: true,
            style: {
              fontSize: "16px",
            },
            formatter: function (value) {
              let s = moment.utc(value).format("YYYY-MM-DD HH:mm:ss");
              let e = moment.utc(value).add(1, "m").format("YYYY-MM-DD HH:mm:ss");

              let test = s + " - " + e;
              return test;
            },
          },
        },
      };

      this.series = [
        {
          name: "User Count",
          data: newuserCount,
        },

        {
          name: "Sigs Added",
          data: newsigsAddData,
        },
        {
          name: "Sigs Updated",
          data: newsigsUpdateData,
        },

        {
          name: "Sigs Info Update",
          data: newsigsInfoUpdateData,
        },

        {
          name: "Connections Made",
          data: newsigsConnectionData,
        },
      ];
    },

    async updateChartWeek() {
      await this.$store.dispatch("getChartDataWeek");
      const newuserCount = this.userCountData;
      const newsigsAddData = this.sigsAddData;
      const newsigsUpdateData = this.sigsUpdateData;
      const newsigsConnectionData = this.sigsConnectionData;
      const newsigsInfoUpdateData = this.sigsInfoUpdateData;

      this.chartOptions = {
        tooltip: {
          shared: true,
          theme: "dark",

          x: {
            show: true,
            style: {
              fontSize: "16px",
            },
            formatter: function (value) {
              let s = moment.utc(value).format("YYYY-MM-DD HH:mm:ss");
              let e = moment.utc(value).add(7, "d").format("YYYY-MM-DD HH:mm:ss");

              let test = s + " - " + e;
              return test;
            },
          },
        },
      };

      this.series = [
        {
          name: "User Count",
          data: newuserCount,
        },

        {
          name: "Sigs Added",
          data: newsigsAddData,
        },
        {
          name: "Sigs Updated",
          data: newsigsUpdateData,
        },

        {
          name: "Sigs Info Update",
          data: newsigsInfoUpdateData,
        },

        {
          name: "Connections Made",
          data: newsigsConnectionData,
        },
      ];
    },

    async updateChartDay() {
      await this.$store.dispatch("getChartDataHour");
      const newuserCount = this.userCountData;
      const newsigsAddData = this.sigsAddData;
      const newsigsUpdateData = this.sigsUpdateData;
      const newsigsConnectionData = this.sigsConnectionData;
      const newsigsInfoUpdateData = this.sigsInfoUpdateData;

      this.chartOptions = {
        tooltip: {
          shared: true,
          theme: "dark",

          x: {
            show: true,
            style: {
              fontSize: "16px",
            },
            formatter: function (value) {
              let s = moment.utc(value).format("YYYY-MM-DD HH:mm:ss");
              let e = moment.utc(value).add(1, "d").format("YYYY-MM-DD HH:mm:ss");

              let test = s + " - " + e;
              return test;
            },
          },
        },
      };

      this.series = [
        {
          name: "User Count",
          data: newuserCount,
        },

        {
          name: "Sigs Added",
          data: newsigsAddData,
        },
        {
          name: "Sigs Updated",
          data: newsigsUpdateData,
        },

        {
          name: "Sigs Info Update",
          data: newsigsInfoUpdateData,
        },

        {
          name: "Connections Made",
          data: newsigsConnectionData,
        },
      ];
    },

    async updateChartHour() {
      await this.$store.dispatch("getChartDataHour");
      const newuserCount = this.userCountData;
      const newsigsAddData = this.sigsAddData;
      const newsigsUpdateData = this.sigsUpdateData;
      const newsigsConnectionData = this.sigsConnectionData;
      const newsigsInfoUpdateData = this.sigsInfoUpdateData;

      this.chartOptions = {
        tooltip: {
          shared: true,
          theme: "dark",

          x: {
            show: true,
            style: {
              fontSize: "16px",
            },
            formatter: function (value) {
              let s = moment.utc(value).format("YYYY-MM-DD HH:mm:ss");
              let e = moment.utc(value).add(1, "h").format("YYYY-MM-DD HH:mm:ss");

              let test = s + " - " + e;
              return test;
            },
          },
        },
      };

      this.series = [
        {
          name: "User Count",
          data: newuserCount,
        },

        {
          name: "Sigs Added",
          data: newsigsAddData,
        },
        {
          name: "Sigs Updated",
          data: newsigsUpdateData,
        },

        {
          name: "Sigs Info Update",
          data: newsigsInfoUpdateData,
        },

        {
          name: "Connections Made",
          data: newsigsConnectionData,
        },
      ];
    },

    async updateChartMins() {
      await this.$store.dispatch("getChartDataMins", this.minSelect);
      const newuserCount = this.userCountData;
      const newsigsAddData = this.sigsAddData;
      const newsigsUpdateData = this.sigsUpdateData;
      const newsigsConnectionData = this.sigsConnectionData;
      const newsigsInfoUpdateData = this.sigsInfoUpdateData;

      switch (this.minSelect) {
        case 5:
          this.chartOptions = {
            tooltip: {
              shared: true,
              theme: "dark",

              x: {
                show: true,
                style: {
                  fontSize: "16px",
                },
                formatter: function (value) {
                  let s = moment.utc(value).format("YYYY-MM-DD HH:mm:ss");
                  let e = moment.utc(value).add(5, "m").format("YYYY-MM-DD HH:mm:ss");

                  let test = s + " - " + e;
                  return test;
                },
              },
            },
          };

          break;

        case 10:
          this.chartOptions = {
            tooltip: {
              shared: true,
              theme: "dark",

              x: {
                show: true,
                style: {
                  fontSize: "16px",
                },
                formatter: function (value) {
                  let s = moment.utc(value).format("YYYY-MM-DD HH:mm:ss");
                  let e = moment.utc(value).add(10, "m").format("YYYY-MM-DD HH:mm:ss");

                  let test = s + " - " + e;
                  return test;
                },
              },
            },
          };

          break;

        case 30:
          this.chartOptions = {
            tooltip: {
              shared: true,
              theme: "dark",

              x: {
                show: true,
                style: {
                  fontSize: "16px",
                },
                formatter: function (value) {
                  let s = moment.utc(value).format("YYYY-MM-DD HH:mm:ss");
                  let e = moment.utc(value).add(30, "m").format("YYYY-MM-DD HH:mm:ss");

                  let test = s + " - " + e;
                  return test;
                },
              },
            },
          };

          break;
      }

      this.series = [
        {
          name: "User Count",
          data: newuserCount,
        },

        {
          name: "Sigs Added",
          data: newsigsAddData,
        },
        {
          name: "Sigs Updated",
          data: newsigsUpdateData,
        },

        {
          name: "Sigs Info Update",
          data: newsigsInfoUpdateData,
        },

        {
          name: "Connections Made",
          data: newsigsConnectionData,
        },
      ];
    },
  },

  computed: {
    // ...mapState([
    //   "usercount",
    //   "sigsAddedMaxCount",
    //   "sigsUpdatedMaxCount",
    //   "sigsAddedCount",
    //   "sigsUpdatedCount",
    //   "sigConnectioncount",
    //   "sigInfoUpdateCount",
    // ]),

    sigAddMax() {
      return this.sigsAddedMaxCount;
    },

    userCountData() {
      return this.usercount;
    },

    sigsUpdateMax() {
      return this.sigsUpdatedMaxCount;
    },

    sigsAddData() {
      return this.sigsAddedCount;
    },

    sigsUpdateData() {
      return this.sigsUpdatedCount;
    },

    sigsConnectionData() {
      return this.sigConnectioncount;
    },

    sigsInfoUpdateData() {
      return this.sigInfoUpdateCount;
    },
  },

  async beforeDestroy() {
    Echo.leave("charts");
  },
};
</script>

<style></style>
