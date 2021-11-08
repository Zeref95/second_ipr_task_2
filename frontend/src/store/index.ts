import { createStore } from 'vuex'

export default createStore({
  state: {
    todayMovieList: []
  },
  mutations: {
    setTodayMovieList (state: any, movieList: any) {
      state.todayMovieList = movieList;
    }
  },
  actions: {
  },
  modules: {
  }
})
