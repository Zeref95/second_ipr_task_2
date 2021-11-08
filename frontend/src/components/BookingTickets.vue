<template>
  <div v-if="currentStep === 1">
    <div>
      <button @click="choseTodayDate"
              class="day-button"
              :class="{'chosen-day': chosenDate === 'today'}"
              ref="btnToday">
        Today
      </button>
      <button
          @click="choseTomorrowDate"
          class="day-button"
          :class="{'chosen-day': chosenDate === 'tomorrow'}">
        Tomorrow
      </button>

      Or chose a day
      <input type="date" v-model="inputDate" :class="{'chosen-day': chosenDate === 'input'}">
    </div>
    <hr>
    <div v-if="movieList.length === 0">Sorry, there is no session on this day</div>
    <div class="movie-list">
      <div v-for="move in movieList" class="movie" @click="">
        <h3>{{ move.title }}</h3>
        <img :src="backendURL + move.poster" alt="">
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
  watch: {
    inputDate(val: string) {
      this.chosenDate = 'input'
      this.makeRequest('api/v1/get-movies-by-date', 'get', {city_name: this.city, date: val},
          (movieList: any) => {
            this.movieList = movieList;
          });
    }
  }
})

export default class BookingTickets extends Vue {
  city!: string
  currentStep: number = 1
  apiKey: string = 'YiL2x3O3CETQzgICNnIFkcgHyfuzVPPTV4Msrg2vOAV6Fd2WBwk9KBRVHw7h5yyI'
  backendURL: string = 'http://127.0.0.1/'
  movieList: any = []
  inputDate: string = ''
  chosenDate: string = 'today'

  created() {
    this.makeRequest('api/v1/movies', 'get', {city_name: this.city},
        (movieList: any) => {
          store.commit('setTodayMovieList', movieList.today);
          store.commit('setTomorrowMovieList', movieList.tomorrow);

          this.movieList = store.state.todayMovieList;
        });
  }

  getMovieInfo(movie_id: number) {
    this.makeRequest('api/v1/movie-session', 'get', {city_name: this.city},
        () => {

        });
  }

  dateFormatter(date: Date): string {
    return `${date.getFullYear()}-${("0" + (date.getMonth() + 1)).slice(-2)}-${date.getDate()}`;
  }

  choseTodayDate(): void {
    this.movieList = store.state.todayMovieList;
    this.chosenDate = 'today'
  }

  choseTomorrowDate(): void {
    this.movieList = store.state.tomorrowMovieList;
    this.chosenDate = 'tomorrow'
  }

  async makeRequest(url: string, method: string, data: any, callback: any) {
    let urlFetch: string = this.backendURL + url;
    method = method.toUpperCase();
    if (method === 'GET') {
      let first: boolean = true;
      for (const dataKey in data) {
        if (data.hasOwnProperty(dataKey)) {
          if (first) {
            first = false;
            urlFetch += `?${dataKey}=${data[dataKey]}`
          } else {
            urlFetch += `&${dataKey}=${data[dataKey]}`
          }
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
.chosen-day {
  background-color: #2c3e50;
  color: white;
}
.day-button {
  margin: 0 5px;
  width: 100px;
  font-size: 1.1em;
  border-radius: 5px;
  cursor: pointer;
  &:hover {
    background-color: aquamarine;
  }
}
.movie-list {
  display: flex;
  flex-wrap: wrap;
  .movie {
    cursor: pointer;
    width: 300px;
    display: flex;
    flex-direction: column;
    h3 {
      text-align: center;
    }
    img {
      width: 150px;
      margin: 0 auto;
    }
  }
}



</style>