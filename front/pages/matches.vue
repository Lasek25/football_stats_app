<template>
  <div>
    <h1>{{ daysRange > 0 ? 'Terminarz' : 'Wyniki' }}</h1>
    <div>
      <v-btn
        v-for="com in competitions"
        :key="com.id"
        @click="competitionId = com.id"
      >
        {{ com.name }}
      </v-btn>
    </div>
    <div>
      <v-btn @click="daysRange = -7">Wyniki</v-btn>
      <v-btn @click="daysRange = 7">Terminarz</v-btn>
    </div>
    <ul>
      <!-- {{
        competition.name
      }} -->
      <!-- {{
        matchesQuery[0].teamsInMatches[0].teamsInCompetition.competition.name
      }} -->
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
      competitionId: 0,
      daysRange: -7,
    }
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
              country
            }
          }
        }
      `,
    },
    // competition: {
    //   query: gql`
    //     query getCompetition($id: ID) {
    //       competition(id: $id) {
    //         id
    //         name
    //         competitionsType {
    //           country
    //         }
    //       }
    //     }
    //   `,
    //   variables() {
    //     return {
    //       id: this.competitionId,
    //     }
    //   },
    // },
  },
}
</script>
