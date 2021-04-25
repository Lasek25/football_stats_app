<template>
  <div>
    <h1>Rozgrywki</h1>
    <competitions-menu :is-matches-page="false" />
    <v-container class="cardBg mb-12 mb-sm-0">
      <v-divider></v-divider>
      <v-row class="mt-3 mt-sm-0">
        <v-col class="d-inline-flex" cols="12" sm="8" lg="9">
          <v-card class="white mr-5">
            <img
              :src="`CompetitionLogo/c${competitionId}.png`"
              :width="getWindowWidth < 600 ? '50px' : '80px'"
              :height="getWindowWidth < 600 ? '50px' : '80px'"
              class="mt-2 mr-2 ml-2"
            />
          </v-card>
          <h1 class="d-sm-flex d-none align-center">
            {{
              findCompetitionName ? findCompetitionName.name : 'Premier League'
            }}
          </h1>
          <h2 class="d-flex d-sm-none align-center">
            {{
              findCompetitionName ? findCompetitionName.name : 'Premier League'
            }}
          </h2>
        </v-col>
        <v-spacer></v-spacer>
        <v-col sm="4" lg="3" class="d-flex align-center">
          <v-select
            v-model="selectDT"
            :items="dynamicTabs"
            dense
            solo
            return-object
            item-text="title"
            item-value="value"
          />
        </v-col>
      </v-row>
      <v-divider class="mb-2"></v-divider>
      <div class="mt-4">
        <component :is="getDynamicTab" />
      </div>
    </v-container>
  </div>
</template>

<script>
import gql from 'graphql-tag'
import CompetitionsTab from '../components/CompetitionsTab.vue'
import MatchesTab from '../components/MatchesTab.vue'
import BestTab from '../components/BestTab.vue'
import CompetitionsMenu from '../components/CompetitionsMenu.vue'
export default {
  components: { CompetitionsTab, MatchesTab, BestTab, CompetitionsMenu },
  data() {
    return {
      activeCompetition: '0',
      competitionsTypeId: '1',
      competitions: [],
      selectDT: {
        title: 'TABELA',
        value: 'competitions-tab',
      },
      dynamicTabs: [
        {
          title: 'TABELA',
          value: 'competitions-tab',
        },
        {
          title: 'WYNIKI/TERMINARZ',
          value: 'matches-tab',
        },
        {
          title: 'STATYSTYKI',
          value: 'best-tab',
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
    getDynamicTab() {
      return this.selectDT.value
    },
    findCompetitionName() {
      return this.competitions.find(
        (element) => element.id === this.competitionId
      )
    },
  },
  created() {
    this.$store.commit('setCompetitionId', 1)
    this.$store.commit('setToggleMatchesQuantity', 0)
    this.$store.commit('setToggleDaysRange', 0)
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
  },
}
</script>
