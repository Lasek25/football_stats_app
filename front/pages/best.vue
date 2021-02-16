<template>
  <div>
    <h1>Najlepsi w swojej kategorii</h1>
    <v-container class="cardBg mt-3">
      <v-row class="d-flex flex-md-row">
        <v-col>
          <h2>Bramki</h2>
          <ul>
            <li v-for="aq in avgQuery" :key="aq.id">
              {{ aq.teamsInCompetition.team.name }}
              {{ aq.avgGoals }}
            </li>
          </ul>
        </v-col>
        <v-col>
          <h2>Rzuty rożne</h2>
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
      competitionId: 0,
      matchesQuantity: 0,
    }
  },
  apollo: {
    avgQuery: {
      query: gql`
        query getAvg($competition: Int, $matchesQuantity: Int) {
          avgQuery(
            competition: $competition
            matchesQuantity: $matchesQuantity
          ) {
            teamsInCompetition {
              id
              team {
                id
                name
              }
            }
            avgGoals
            goals
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
          matchesQuantity: this.matchesQuantity,
        }
      },
    },
  },
}
</script>
