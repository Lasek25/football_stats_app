<template>
  <!-- <v-row class="justify-center">
    <v-col class="col-12 col-sm-10"> -->
  <v-card class="pb-3 tabBg">
    <v-container>
      <v-row>
        <!-- <h2 class="d-flex justify-center pb-3 pt-3">Tabela</h2> -->
        <v-col cols="12" sm="3">
          <h2>TABELA</h2>
        </v-col>
        <v-col sm="9">
          <v-btn-toggle
            v-model="toggleMatchesQuantity"
            mandatory
            dense
            class="float-sm-right"
          >
            <v-btn
              v-for="(quantity, i) in matchesQuantityList"
              :key="i"
              :value="quantity"
            >
              {{
                quantity == 0
                  ? 'Wszystkie'
                  : getWindowWidth > 500
                  ? quantity + ' ostatnich'
                  : quantity + ' ost.'
              }}
            </v-btn>
          </v-btn-toggle>
        </v-col>
      </v-row>
    </v-container>
    <v-card
      class="mr-sm-4 ml-sm-4 mr-2 ml-2 pr-1 pr-sm-2 pl-2 w-100 overflow-x-auto"
    >
      <v-row dense class="align-center long-content">
        <v-col class="d-flex justify-center col-1 font-weight-black">
          POZ.
        </v-col>
        <v-col class="col-4 font-weight-black">DRUŻYNA</v-col>
        <v-col class="col-2 d-flex justify-center font-weight-black">
          {{ getWindowWidth > 750 ? 'MECZE' : 'M' }}
        </v-col>
        <v-col class="col-1 d-flex justify-center font-weight-black">
          PKT.
        </v-col>
        <v-col class="col-2 d-flex justify-center font-weight-black">
          BRAMKI
        </v-col>
        <v-col class="col-2 d-flex justify-center font-weight-black">
          LOGO
        </v-col>
      </v-row>
      <div
        v-for="(r, index) in sortResults('points')"
        :key="index"
        :class="(index == 0 && 'secondary') || (index < 3 && 'primary')"
        class="long-content"
      >
        <v-row dense class="align-center">
          <v-col class="d-flex justify-center col-1">
            {{ index + 1 + '.' }}
          </v-col>
          <v-col class="col-4 text-no-wrap">
            {{ r.teamsInCompetition.team.name }}
          </v-col>
          <v-col class="col-2 d-flex justify-center">
            {{ r.points.length }}
          </v-col>
          <v-col class="col-1 d-flex justify-center">
            {{ sumType(r.points) }}
          </v-col>
          <v-col class="col-2 d-flex justify-center">
            {{ sumType(r.goalsScored) + ':' + sumType(r.goalsAgainst) }}
          </v-col>
          <v-col class="d-flex justify-center col-2">
            <img
              :src="`TeamLogo/${r.teamsInCompetition.team.id}.png`"
              width="25px"
              height="25px"
            />
          </v-col>
        </v-row>
        <v-divider></v-divider>
        <!-- </v-card> -->
      </div>
    </v-card>
  </v-card>
  <!-- </v-col>
  </v-row> -->
</template>

<script>
import gql from 'graphql-tag'
export default {
  data() {
    return {
      sortDesc: true,
      matchesQuantityList: [0, 5, 10],
      resultsQuery: [],
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
    sumType(teamType) {
      return teamType.reduce(function (a, b) {
        return a + b
      })
    },
    sortResults(type) {
      // console.log('sorting')
      return [...this.resultsQuery]
        .sort((a, b) => {
          const aType = this.sumType(a[type])
          const bType = this.sumType(b[type])
          if (aType < bType) return this.sortDesc ? 1 : -1
          if (aType > bType) return this.sortDesc ? -1 : 1
          const aGoalsScored = this.sumType(a.goalsScored)
          const bGoalsScored = this.sumType(b.goalsScored)
          const aGoalsAgainst = this.sumType(a.goalsAgainst)
          const bGoalsAgainst = this.sumType(b.goalsAgainst)
          if (aGoalsScored - aGoalsAgainst < bGoalsScored - bGoalsAgainst)
            return 1
          if (aGoalsScored - aGoalsAgainst > bGoalsScored - bGoalsAgainst)
            return -1
          if (a.teamsInCompetition.team.id < b.teamsInCompetition.team.id)
            return -1
          if (a.teamsInCompetition.team.id > b.teamsInCompetition.team.id)
            return 1
        })
        .slice(0, this.teamCount)
    },
  },
  apollo: {
    resultsQuery: {
      query: gql`
        query getResults($competition: Int, $matchesQuantity: Int) {
          resultsQuery(
            competition: $competition
            matchesQuantity: $matchesQuantity
          ) {
            teamsInCompetition {
              id
              season
              team {
                id
                name
              }
              competition {
                id
                name
              }
            }
            points
            goalsScored
            goalsAgainst
            corners
            yellowCards
            redCards
            fouls
            offsides
            shotsOnGoal
          }
        }
      `,
      variables() {
        return {
          competition: this.competitionId,
          matchesQuantity: this.toggleMatchesQuantity,
        }
      },
    },
  },
}
</script>
