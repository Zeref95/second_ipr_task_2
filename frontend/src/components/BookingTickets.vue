<template>
  <div v-if="currentStep === 1">
    <div>
      <button @click="choseTodayDate">Today</button>
      <button @click="choseTomorrowDate">Tomorrow</button>

      Or chose a day
      <input type="date">
      img ?
    </div>
    <hr>
    <div v-if="error">{{error}}</div>
    <div v-else>
      <div v-for="move in movieList" class="movie" @click="">
        {{move.title}}
        <br>
        <img :src="backendURL + move.poster" alt="">
        <br>
        {{move.description}}

        <hr>
      </div>

    </div>
  </div>
  <div v-if="currentStep === 2">

  </div>
  <div v-if="currentStep === 3">

  </div>

</template>

<script lang="ts">
import {Options, Vue} from 'vue-class-component';
import store from '@/store'

@Options({
  props: {
    city: String
  },
})
export default class BookingTickets extends Vue {
  city!: string
  currentStep: number = 1
  apiKey: string = 'YiL2x3O3CETQzgICNnIFkcgHyfuzVPPTV4Msrg2vOAV6Fd2WBwk9KBRVHw7h5yyI'
  backendURL: string = 'http://127.0.0.1/'
  error:string = ''
  chosenDate:string = ''
  movieList:any = []

  created() {
    this.makeRequest('api/v1/movies', 'get', {city_name: this.city},
        (movieList: any) =>  {
          store.commit('setTodayMovieList', movieList.today);
          store.commit('setTomorrowMovieList', movieList.tomorrow);

          this.movieList = store.state.todayMovieList;
    });
    this.chosenDate = this.dateFormatter(new Date())
  }

  getMovieInfo(movie_id: number) {
    this.makeRequest('api/v1/movie-session', 'get', {city_name: this.city},
        () =>  {

        });
  }
  dateFormatter(date:Date):string {
    return `${date.getFullYear()}-${("0" + (date.getMonth() + 1)).slice(-2)}-${date.getDate()}`;
  }

  choseTodayDate():void {
    this.movieList = store.state.todayMovieList;
  }
  choseTomorrowDate():void {
    this.movieList = store.state.tomorrowMovieList;
  }

  async makeRequest(url: string, method: string, data: any, callback: any) {
    let urlFetch: string = this.backendURL + url;
    method = method.toUpperCase();

    if (method === 'GET') {
      let first: boolean = false;
      for (const dataKey in data) {
        if (data.hasOwnProperty(dataKey)) {
          if (!first)
            first = false;
            urlFetch += `?${dataKey}=${data[dataKey]}`
        } else {
            urlFetch += `&${dataKey}=${data[dataKey]}`
        }
      }
    }

    await fetch(urlFetch, {
      method: method,
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Authorization': this.apiKey
      },
    })
        .then(res => res.json())
        .then((json) => {
          callback(json)
        })
        .catch((err) => {
          console.error(err);
        });
  }
}
</script>

<style scoped lang="scss">
  img {
    width: 150px;
  }
  .movie {
    cursor: pointer;
  }
</style>