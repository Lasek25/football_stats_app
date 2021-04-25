<template>
  <div>
    <h1>Najlepsi w swojej kategorii</h1>
    <v-container class="cardBg mt-3 mb-12 mb-sm-0">
      <best-tab :team-count="10" />
    </v-container>
  </div>
</template>

<script>
import BestTab from '../components/BestTab.vue'
export default {
  components: { BestTab },
  created() {
    this.$store.commit('setCompetitionId', 0)
    this.$store.commit('setToggleMatchesQuantity', 0)
  },
}
</script>
