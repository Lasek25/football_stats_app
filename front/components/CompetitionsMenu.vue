<template>
  <v-card class="mt-3">
    <v-tabs background-color="primary" show-arrows height="65">
      <v-tabs-slider color="teal lighten-3"></v-tabs-slider>
      <v-tab v-if="isMatchesPage" @click="setCompetition(0)">
        Wszystkie
        <v-img src="http://127.0.0.1:8887/worldwide.png" class="ml-3" />
      </v-tab>
      <v-tab
        v-for="ct in competitionsTypes"
        :key="ct.id"
        @click="setCompetition(ct)"
      >
        {{ ct.country }}
        <v-img :src="`http://127.0.0.1:8887/${ct.country}.png`" class="ml-3" />
      </v-tab>
    </v-tabs>
    <v-tabs
      v-if="competitionId != 0"
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
        @click="setFilteredCompetition(com)"
      >
        {{ com.name }}
      </v-tab>
    </v-tabs>
  </v-card>
</template>

<script>
import gql from 'graphql-tag'
export default {
  props: {
    isMatchesPage: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      activeCompetition: '0',
      // competitionId: 0,
      competitionsTypeId: '1',
      competitions: [],
      // daysList: [1, 3, 7],
    }
  },
  computed: {
    filteredCompetitions() {
      return this.competitions.filter((competition) => {
        return competition.competitionsType.id === this.competitionsTypeId
      })
    },
    competitionId() {
      return this.$store.getters.getCompetitionId
    },
  },
  methods: {
    setCompetition(compType) {
      // this.competitionsTypeId = compType.id
      // this.competitionId = compType.competitions[0].id
      // this.activeCompetition = compType.competitions[0].id
      if (compType === 0) {
        this.$store.commit('setCompetitionId', 0)
        this.competitionsTypeId = 0
      } else {
        this.competitionsTypeId = compType.id
        this.$store.commit('setCompetitionId', compType.competitions[0].id)
        this.activeCompetition = compType.competitions[0].id
      }
    },
    setFilteredCompetition(comp) {
      this.$store.commit('setCompetitionId', comp.id)
    },
  },
  apollo: {
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
  },
}
</script>
