import { createStore } from 'vuex'

export default createStore({
  state: {
    todayMovieList: [],
    tomorrowMovieList: [],
  },
  mutations: {
    setTodayMovieList (state: any, movieList: any) {
      state.todayMovieList = movieList;
    },
    setTomorrowMovieList (state: any, movieList: any) {
      state.tomorrowMovieList = movieList;
    }
  },
  actions: {
  },
  modules: {
  }
})
