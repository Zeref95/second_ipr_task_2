import { createStore } from 'vuex'

export default createStore({
  state: {
    todayMovieList: [],
    tomorrowMovieList: [],
    sessionList: {},
  },
  mutations: {
    setTodayMovieList (state: any, movieList: any) {
      state.todayMovieList = movieList;
    },
    setTomorrowMovieList (state: any, movieList: any) {
      state.tomorrowMovieList = movieList;
    },
    PushToMovieSessionList (state: any, obj: any) {
      state.sessionList[obj.key] = obj.data;
    }
  },
  actions: {
  },
  modules: {
  }
})
