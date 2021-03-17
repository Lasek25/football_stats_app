<template>
  <!-- <v-container class="cardBg"> -->
  <div>
    <h2>{{ togglePosNeg > 0 ? 'TERMINARZ' : 'WYNIKI' }}</h2>
    <v-row class="d-flex flex-column-reverse flex-md-row">
      <v-col cols="12" md="8" lg="9">
        <v-simple-table v-if="matchesQuery.length > 0" class="flex-column">
          <thead>
            <tr>
              <th class="text-center d-none d-sm-table-cell">Godzina</th>
              <th class="text-center d-none d-sm-table-cell">Kolejka</th>
              <th class="text-center">Mecz</th>
            </tr>
          </thead>
          <tbody v-for="(matchDay, i) in getMatchesDates" :key="i">
            <tr>
              <th class="text-center primary" colspan="3">
                {{ matchDay }}
              </th>
            </tr>
            <tr v-for="match in filteredMatches(matchDay)" :key="match.id">
              <td class="text-center d-none d-sm-table-cell">
                {{ match.date.substr(11, 15) }}
              </td>
              <td class="text-center d-none d-sm-table-cell">
                {{ match.round }}
              </td>
              <th
                class="text-center d-inline-flex justify-center align-center d-sm-none"
              >
                Godzina
              </th>
              <td
                class="text-center d-inline-flex justify-center align-center d-sm-none"
              >
                {{ match.date.substr(11, 15) }}
              </td>
              <!-- <th
                class="text-center d-inline-flex justify-center align-center d-sm-none"
              >
                Kolejka
              </th>
              <td
                class="text-center d-inline-flex justify-center align-center d-sm-none border-bottom-0"
              >
                {{ match.round }}
              </td> -->
              <td class="text-center d-sm-table-cell d-block">
                <div class="justify-center align-center d-flex">
                  <img
                    :src="`TeamLogo/${match.teamsInMatches[0].teamsInCompetition.team.id}.png`"
                    width="20px"
                    height="20px"
                    class="mr-2"
                  />
                  <span
                    :class="
                      match.teamsInMatches[0].goals >
                        match.teamsInMatches[1].goals && 'font-weight-black'
                    "
                  >
                    <!-- with isTeamWin -->
                    <!-- <span
                    :class="
                      isTeamWin(
                        match.teamsInMatches[0].goals,
                        match.teamsInMatches[1].goals
                      )
                    "
                  > -->
                    {{ match.teamsInMatches[0].teamsInCompetition.team.name }}
                  </span>
                  <span class="font-weight-bold ml-2 mr-2 text-no-wrap">
                    {{
                      match.teamsInMatches[0].updatedAt === null
                        ? '-'
                        : match.teamsInMatches[0].goals +
                          ' : ' +
                          match.teamsInMatches[1].goals
                    }}
                  </span>
                  <span
                    :class="
                      match.teamsInMatches[1].goals >
                        match.teamsInMatches[0].goals && 'font-weight-black'
                    "
                  >
                    <!-- with isTeamWin -->
                    <!-- <span
                    :class="
                      isTeamWin(
                        match.teamsInMatches[1].goals,
                        match.teamsInMatches[0].goals
                      )
                    "
                  > -->
                    {{ match.teamsInMatches[1].teamsInCompetition.team.name }}
                  </span>
                  <img
                    :src="`TeamLogo/${match.teamsInMatches[1].teamsInCompetition.team.id}.png`"
                    width="20px"
                    height="20px"
                    class="ml-2"
                  />
                </div>
              </td>
            </tr>
          </tbody>
        </v-simple-table>
        <h2 v-else class="justify-center d-flex">
          Brak meczy w danym okresie czasu dla wybranej ligi
        </h2>
      </v-col>
      <v-col md="4" lg="3">
        <v-btn-toggle v-model="togglePosNeg" mandatory dense class="d-flex">
          <v-btn :value="-1" class="flex-grow-1" @click="setSortOrder('DESC')">
            Wyniki
          </v-btn>
          <v-btn :value="1" class="flex-grow-1" @click="setSortOrder('ASC')">
            Terminarz
          </v-btn>
        </v-btn-toggle>
        <days-buttons :days-list="daysList" />
      </v-col>
    </v-row>
  </div>
  <!-- </v-container> -->
</template>

<script>
import gql from 'graphql-tag'
import DaysButtons from './DaysButtons.vue'
export default {
  components: { DaysButtons },
  props: {
    daysList: {
      type: Array,
      default() {
        return Array.of(3, 7, 14)
      },
    },
  },
  data() {
    return {
      togglePosNeg: -1,
      sortOrder: 'DESC',
      matchesQuery: [],
    }
  },
  computed: {
    competitionId() {
      return this.$store.getters.getCompetitionId
    },
    toggleDaysRange() {
      return this.$store.getters.getToggleDaysRange
    },
    getMatchesDates() {
      return this.matchesQuery
        .map((match) => match.date.substr(0, 10))
        .filter((value, index, self) => self.indexOf(value) === index)
    },
  },
  methods: {
    // with isTeamWin
    // isTeamWin(team1, team2) {
    //   return team1 > team2 ? 'font-weight-black' : ''
    // },
    setSortOrder(newSortOrder) {
      this.sortOrder = newSortOrder
    },
    filteredMatches(tmpDate) {
      return this.matchesQuery.filter((match) => {
        return match.date.substr(0, 10) === tmpDate
      })
    },
  },
  apollo: {
    matchesQuery: {
      query: gql`
        query getMatches($competition: Int, $days: Int, $orderBy: SortOrder) {
          matchesQuery(
            competition: $competition
            days: $days
            orderBy: $orderBy
          ) {
            id
            date
            round
            teamsInMatches {
              id
              goals
              updatedAt
              teamsInCompetition {
                id
                competition {
                  id
                  name
                }
                team {
                  id
                  name
                }
              }
            }
          }
        }
      `,
      variables() {
        return {
          competition: this.competitionId,
          days: this.toggleDaysRange * this.togglePosNeg,
          orderBy: this.sortOrder,
        }
      },
    },
  },
}
</script>
