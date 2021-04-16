<template>
  <div>
    <v-snackbar
      v-model="snackbar"
      timeout="5000"
      transition="slide-x-reverse-transition"
      color="primary"
    >
      {{ getSnackbarText }}
      <template v-slot:action="{ attrs }">
        <v-btn color="red" text v-bind="attrs" @click="setSnackbar">
          Zamknij
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
export default {
  computed: {
    snackbar: {
      get() {
        return this.$store.getters.getSnackbar
      },
      set(status) {
        this.$store.commit('setSnackbar', status)
      },
    },
    getSnackbarText() {
      return this.$store.getters.getSnackbarText
    },
  },
  methods: {
    setSnackbar() {
      this.$store.commit('setSnackbar', false)
    },
  },
}
</script>
