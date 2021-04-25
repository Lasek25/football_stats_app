<template>
  <div>
    <h1>Wyniki/Terminarz</h1>
    <competitions-menu :is-matches-page="true" />
    <v-container class="cardBg mb-12 mb-sm-0">
      <matches-tab :days-list="daysList" />
    </v-container>
  </div>
</template>

<script>
import MatchesTab from '../components/MatchesTab.vue'
import CompetitionsMenu from '../components/CompetitionsMenu.vue'
export default {
  components: { MatchesTab, CompetitionsMenu },
  data() {
    return {
      daysList: [1, 3, 7],
    }
  },
  created() {
    this.$store.commit('setCompetitionId', 0)
    this.$store.commit('setToggleDaysRange', 0)
  },
}
</script>
