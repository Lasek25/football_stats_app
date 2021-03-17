<template>
  <div>
    <h2>STATYSTYKI - ŚREDNIA</h2>
    <div class="d-block d-md-flex justify-center mt-3 mb-3">
      <v-bottom-navigation v-model="isMatchStats" color="primary" grow>
        <v-btn
          v-for="(btn, index) in sortedIsMatchStats"
          :key="index"
          :value="btn.value"
        >
          <span>{{ btn.title }}</span>
          <v-icon>{{ btn.icon }}</v-icon>
        </v-btn>
      </v-bottom-navigation>
      <v-bottom-navigation
        v-model="toggleMatchesQuantity"
        background-color="primary"
        color="secondary"
        grow
      >
        <v-btn
          v-for="(btn, index) in sortedQuantity"
          :key="index"
          :value="btn.value"
        >
          <span>{{ btn.title }}</span>
          <v-icon>{{ btn.icon }}</v-icon>
        </v-btn>
      </v-bottom-navigation>
      <v-bottom-navigation v-model="sortDesc" color="primary" grow>
        <v-btn v-for="(btn, index) in sorted" :key="index" :value="btn.value">
          <span>{{ btn.title }}</span>
          <v-icon>{{ btn.icon }}</v-icon>
        </v-btn>
      </v-bottom-navigation>
    </div>
    <!-- <v-row class="flex-column"> -->
    <v-carousel
      v-model="carouselActiveItem"
      hide-delimiters
      height="auto"
      :show-arrows="getWindowWidth > 600"
    >
      <!-- 650 -->
      <v-carousel-item
        v-for="(avgTypeName, i) in avgTypeNames"
        :key="i"
        reverse-transition="none"
        transition="none"
      >
        <v-row class="justify-center">
          <v-col class="col-12 col-sm-10">
            <!-- do poprawy kolor tla -->
            <v-card class="pb-3 tabBg">
              <!-- :color="
                (this.$vuetify.theme.light == true && 'black') ||
                (this.$vuetify.theme.dark == true && 'blue-grey darken-4')
              " -->
              <!-- <h2 class="d-flex justify-center pb-3 pt-3">
                {{ avgTypeNamesTranslated[i] }}
              </h2> -->
              <div class="d-flex pt-3 justify-space-around">
                <h4
                  v-for="(a, index) in avgTypeNamesTranslated"
                  :key="a"
                  :class="index != i && 'teal--text'"
                  class="row-pointer"
                  @click="setCarouselActiveItem(index)"
                >
                  {{
                    getWindowWidth >= 865
                      ? a
                      : a
                          .split(' ')
                          .map((word) => {
                            return word[0].toUpperCase()
                          })
                          .join('')
                  }}
                </h4>
              </div>
              <h2 class="d-flex justify-center pb-3 pt-3">
                {{ avgTypeNamesTranslated[i] }}
              </h2>
              <div
                v-for="(aq, index) in sortAvg(avgTypeName)"
                :key="index"
                class="pb-2"
              >
                <v-card
                  class="mr-sm-4 ml-sm-4 mr-2 ml-2 pr-1 pr-sm-2 pl-2"
                  :color="
                    (index == 0 && 'secondary') ||
                    (index < 3 && 'primary') ||
                    (index >= 3 && 'accent')
                  "
                >
                  <v-row dense class="align-center">
                    <v-col class="d-flex justify-center col-1">
                      {{ index + 1 + '.' }}
                      <!-- {{
                        index > 0 &&
                        aq[avgTypeName].toFixed(2) ==
                          sortAvg(avgTypeName)[index - 1][
                            avgTypeName
                          ].toFixed(2)
                          ? tmpIndex + '.'
                          : isAvgEquals(index)
                      }} -->
                    </v-col>
                    <v-col class="col-6 col-sm-4">
                      {{ aq.teamsInCompetition.team.name }}
                    </v-col>
                    <v-col class="d-none d-sm-flex col-sm-4">
                      {{ aq.teamsInCompetition.competition.name }}
                    </v-col>
                    <v-col class="col-3 col-sm-2">
                      <v-chip
                        :color="
                          (index == 0 && 'primary') ||
                          (index < 3 && 'secondary') ||
                          (index >= 3 && 'default')
                        "
                      >
                        {{ aq[avgTypeName].toFixed(2) }}
                      </v-chip>
                    </v-col>
                    <v-col class="d-flex justify-center col-2 col-sm-1">
                      <img
                        :src="`TeamLogo/${aq.teamsInCompetition.team.id}.png`"
                        width="25px"
                        height="25px"
                      />
                      <!-- class="float-right" -->
                    </v-col>
                  </v-row>
                </v-card>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-carousel-item>
    </v-carousel>
    <!-- </v-row> -->
  </div>
</template>

<script>
import gql from 'graphql-tag'
export default {
  props: {
    teamCount: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      avgQuery: [],
      sortDesc: true,
      isMatchStats: false,
      carouselActiveItem: 0,
      avgTypeNames: [
        'avgGoals',
        'avgCorners',
        'avgYellowCards',
        'avgRedCards',
        'avgFouls',
        'avgOffsides',
        'avgShotsOnGoal',
      ],
      avgTypeNamesTranslated: [
        'Bramki strzelone',
        'Rzuty rożne',
        'Żółte kartki',
        'Czerwone kartki',
        'Faule',
        'Spalone',
        'Strzały celne',
      ],
      sorted: [
        {
          // icon: 'mdi-sort-descending',
          icon: 'mdi-arrow-down-thick',
          title: 'Malejąco',
          value: true,
        },
        {
          icon: 'mdi-arrow-up-thick',
          title: 'Rosnąco',
          value: false,
        },
      ],
      sortedQuantity: [
        {
          icon: 'mdi-numeric-5-box',
          title: 'Ostatnich',
          value: 5,
        },
        {
          icon: 'mdi-numeric-10-box',
          title: 'Ostatnich',
          value: 10,
        },
        {
          icon: 'mdi-infinity',
          title: 'Wszystkie',
          value: 0,
        },
      ],
      sortedIsMatchStats: [
        {
          icon: 'mdi-soccer-field',
          title: 'Wg meczu',
          value: true,
        },
        {
          icon: 'mdi-tshirt-crew',
          title: 'Wg drużyny',
          value: false,
        },
      ],
    }
  },
  computed: {
    getWindowWidth() {
      return this.$vuetify.breakpoint.width
    },
    competitionId() {
      return this.$store.getters.getCompetitionId
    },
    toggleMatchesQuantity: {
      get() {
        return this.$store.getters.getToggleMatchesQuantity
      },
      set(quantity) {
        this.$store.commit('setToggleMatchesQuantity', quantity)
      },
    },
  },
  created() {
    this.$store.commit('setToggleMatchesQuantity', 0)
  },
  methods: {
    sortAvg(avgType) {
      // console.log('sorting')
      const tmp = [...this.avgQuery]
        // .sort((a, b) => (a[avgType] < b[avgType] ? 1 : -1))
        .sort((a, b) => {
          if (a[avgType] < b[avgType]) return this.sortDesc ? 1 : -1
          if (a[avgType] > b[avgType]) return this.sortDesc ? -1 : 1
          if (a.teamsInCompetition.team.id < b.teamsInCompetition.team.id)
            return -1
          if (a.teamsInCompetition.team.id > b.teamsInCompetition.team.id)
            return 1
        })
      if (this.teamCount !== 0) return tmp.slice(0, this.teamCount)
      else return tmp
    },
    setCarouselActiveItem(index) {
      this.carouselActiveItem = index
    },
    // isAvgEquals(index) {
    //   this.tmpIndex = index + 1
    //   return index + 1 + '.'
    // },
  },
  apollo: {
    avgQuery: {
      query: gql`
        query getAvg(
          $competition: Int
          $isMatchStats: Boolean
          $matchesQuantity: Int
        ) {
          avgQuery(
            competition: $competition
            isMatchStats: $isMatchStats
            matchesQuantity: $matchesQuantity
          ) {
            teamsInCompetition {
              id
              team {
                id
                name
              }
              competition {
                id
                name
              }
            }
            avgGoals
            avgCorners
            avgYellowCards
            avgRedCards
            avgFouls
            avgOffsides
            avgShotsOnGoal
          }
        }
      `,
      variables() {
        return {
          competition: this.competitionId,
          isMatchStats: this.isMatchStats,
          matchesQuantity: this.toggleMatchesQuantity,
        }
      },
    },
  },
}
</script>
