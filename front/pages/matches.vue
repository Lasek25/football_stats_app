<template>
  <div>
    <h1>{{ togglePosNeg > 0 ? 'Terminarz' : 'Wyniki' }}</h1>
    <v-card class="mt-3">
      <v-tabs background-color="primary" show-arrows height="65">
        <v-tabs-slider color="teal lighten-3"></v-tabs-slider>
        <v-tab
          @click="
            competitionsTypeId = 0
            competitionId = 0
          "
        >
          Wszystkie
          <v-img src="http://127.0.0.1:8887/worldwide.png" class="ml-3" />
        </v-tab>
        <v-tab
          v-for="ct in competitionsTypes"
          :key="ct.id"
          @click="setCompetition(ct)"
        >
          {{ ct.country }}
          <v-img
            :src="`http://127.0.0.1:8887/${ct.country}.png`"
            class="ml-3"
          />
        </v-tab>
      </v-tabs>
      <v-tabs
        v-if="filteredCompetitions.length > 0"
        v-model="activeCompetition"
        background-color="primary"
        show-arrows
      >
        <v-tabs-slider color="teal lighten-3"></v-tabs-slider>
        <!-- <v-tab
          v-for="com in competitionType.competitions"
          :key="com.id"
          @click="competitionId = com.id"
        > -->
        <v-tab
          v-for="com in filteredCompetitions"
          :key="com.id"
          @click="competitionId = com.id"
        >
          {{ com.name }}
        </v-tab>
      </v-tabs>
    </v-card>
    <v-container class="grey darken-4">
      <v-row class="d-flex flex-column-reverse flex-md-row">
        <v-col cols="12" md="8" lg="9">
          <v-simple-table v-if="matchesQuery.length > 0">
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-center">Kolejka</th>
                  <th class="text-center">Data</th>
                  <th class="text-center">Mecz</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="match in matchesQuery" :key="match.id">
                  <td class="text-center">{{ match.round }}</td>
                  <td class="text-center">{{ match.date }}</td>
                  <td class="text-center">
                    <span
                      :class="
                        match.teamsInMatches[0].goals >
                          match.teamsInMatches[1].goals && 'font-weight-black'
                      "
                    >
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
                    {{
                      match.teamsInMatches[0].updatedAt === null
                        ? '-'
                        : match.teamsInMatches[0].goals +
                          ' : ' +
                          match.teamsInMatches[1].goals
                    }}
                    <span
                      :class="
                        match.teamsInMatches[1].goals >
                          match.teamsInMatches[0].goals && 'font-weight-black'
                      "
                    >
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
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
          <h2 v-else class="justify-center d-flex">
            Brak meczy w danym okresie czasu dla wybranej ligi
          </h2>
        </v-col>
        <v-col md="4" lg="3">
          <v-btn-toggle v-model="togglePosNeg" mandatory dense class="d-flex">
            <v-btn :value="-1" class="flex-grow-1">Wyniki</v-btn>
            <v-btn :value="1" class="flex-grow-1">Terminarz</v-btn>
          </v-btn-toggle>
          <v-btn-toggle
            v-model="toggleDaysRange"
            mandatory
            dense
            class="mt-3 d-flex"
          >
            <v-btn
              v-for="(days, i) in daysList"
              :key="i"
              :value="days"
              class="flex-grow-1"
            >
              {{ days == 1 ? days + ' dzień' : days + ' dni' }}
            </v-btn>
            <!-- <v-btn :value="1">1 dzień</v-btn>
            <v-btn :value="3">3 dni</v-btn>
            <v-btn :value="7">7 dni</v-btn> -->
          </v-btn-toggle>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import gql from 'graphql-tag'
export default {
  data() {
    return {
      activeCompetition: 0,
      competitionId: 0,
      competitionsTypeId: 0,
      competitions: [],
      daysList: [1, 3, 7],
      toggleDaysRange: 1,
      togglePosNeg: -1,
    }
  },
  computed: {
    filteredCompetitions() {
      return this.competitions.filter((competition) => {
        return competition.competitionsType.id === this.competitionsTypeId
      })
    },
    getDaysRange() {
      return this.toggleDaysRange
    },
    getPosNeg() {
      return this.togglePosNeg
    },
  },
  methods: {
    setCompetition(compType) {
      // console.log(compType)
      // console.log(compType.competitions[0].id)
      this.competitionsTypeId = compType.id
      this.competitionId = compType.competitions[0].id
      this.activeCompetition = compType.competitions[0].id
    },
    isTeamWin(team1, team2) {
      return team1 > team2 ? 'font-weight-black' : ''
    },
  },
  apollo: {
    matchesQuery: {
      query: gql`
        query getMatches($competition: Int, $days: Int) {
          matchesQuery(competition: $competition, days: $days) {
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
        }
      },
    },
    competitions: {
      query: gql`
        query getCompetitions {
          competitions {
            id
            name
            competitionsType {
              id
              country
            }
          }
        }
      `,
    },
    competitionsTypes: {
      query: gql`
        query getCompetitionsTypes {
          competitionsTypes {
            id
            name
            country
            competitions {
              id
              name
            }
          }
        }
      `,
    },
    // competitionType: {
    //   query: gql`
    //     query getCompetitions($compType: ID) {
    //       competitionType(id: $compType) {
    //         id
    //         name
    //         country
    //         competitions {
    //           id
    //           name
    //         }
    //       }
    //     }
    //   `,
    //   variables() {
    //     return {
    //       compType: this.competitionsTypeId,
    //     }
    //   },
    // },
  },
}
</script>
