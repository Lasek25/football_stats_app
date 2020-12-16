<template>
  <div>
    <h1>Matches</h1>
    <div>
      <v-btn @click="competitionId = 0">Wszystkie rozgrywki</v-btn>
      <v-btn @click="competitionId = 1">Premier League</v-btn>
      <v-btn @click="competitionId = 2">Ligue 1</v-btn>
    </div>
    <div>
      <v-btn @click="daysRange = -7">Wyniki</v-btn>
      <v-btn @click="daysRange = 7">Terminarz</v-btn>
    </div>
    <ul>
      <li v-for="match in matchesQuery" :key="match.id">
        {{ match.teamsInMatches[0].teamsInCompetition.competition.name }}
        {{ match.round }}
        {{ match.date }}
        {{ match.teamsInMatches[0].teamsInCompetition.team.name }}
        {{ match.teamsInMatches[0].goals }}
        :
        {{ match.teamsInMatches[1].goals }}
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
  },
}
</script>
