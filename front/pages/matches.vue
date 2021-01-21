<template>
  <div>
    <h1>{{ daysRange > 0 ? 'Terminarz' : 'Wyniki' }}</h1>
    <v-card class="mb-3">
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
    <div>
      <v-btn @click="daysRange = -14">Wyniki</v-btn>
      <v-btn @click="daysRange = 14">Terminarz</v-btn>
    </div>
    <v-simple-table>
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
              {{ match.teamsInMatches[0].teamsInCompetition.team.name }}
              {{
                match.teamsInMatches[0].updatedAt === null
                  ? '-'
                  : match.teamsInMatches[0].goals +
                    ' : ' +
                    match.teamsInMatches[1].goals
              }}
              {{ match.teamsInMatches[1].teamsInCompetition.team.name }}
            </td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
    <ul>
      <!--  -->
      <!-- {{
        competition.name
      }} -->
      <!-- {{
        matchesQuery[0].teamsInMatches[0].teamsInCompetition.competition.name
      }} -->
      <!--  -->
      <li v-for="match in matchesQuery" :key="match.id">
        {{ match.round }}
        {{ match.date }}
        {{ match.teamsInMatches[0].teamsInCompetition.team.name }}
        {{
          match.teamsInMatches[0].updatedAt === null
            ? '-'
            : match.teamsInMatches[0].goals +
              ' : ' +
              match.teamsInMatches[1].goals
        }}
        {{ match.teamsInMatches[1].teamsInCompetition.team.name }}
      </li>
    </ul>
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
      daysRange: -14,
      competitions: [],
    }
  },
  computed: {
    filteredCompetitions() {
      return this.competitions.filter((competition) => {
        return competition.competitionsType.id === this.competitionsTypeId
      })
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
          days: this.daysRange,
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
