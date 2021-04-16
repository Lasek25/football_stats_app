export const state = () => ({
  toggleMatchesQuantity: 0,
  competitionId: '1',
  toggleDaysRange: 1,
  snackbar: false,
  snackbarText: '',
})
export const mutations = {
  setCompetitionId(state, payload) {
    state.competitionId = payload
  },
  setToggleMatchesQuantity(state, payload) {
    state.toggleMatchesQuantity = payload
  },
  setToggleDaysRange(state, payload) {
    state.toggleDaysRange = payload
  },
  setSnackbar(state, payload) {
    state.snackbar = payload
  },
  setSnackbarText(state, payload) {
    state.snackbarText = payload
  },
}
export const getters = {
  getCompetitionId: (state) => state.competitionId,
  getToggleMatchesQuantity: (state) => state.toggleMatchesQuantity,
  getToggleDaysRange: (state) => state.toggleDaysRange,
  getSnackbar: (state) => state.snackbar,
  getSnackbarText: (state) => state.snackbarText,
}
