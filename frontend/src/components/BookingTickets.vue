<template>
  <div v-if="currentStep === 1">
    <div>
      <button @click="choseTodayDate"
              class="button"
              :class="{'chosen-day': chosenDate === 'today'}">
        Today
      </button>
      <button
          @click="choseTomorrowDate"
          class="button"
          :class="{'chosen-day': chosenDate === 'tomorrow'}">
        Tomorrow
      </button>

      Or chose a day
      <input type="date" v-model="inputDate" :class="{'chosen-day': chosenDate === 'input'}">
    </div>
    <hr>
    <div v-if="movieList.length === 0">Sorry, there is no session on this day</div>
    <div class="movie-list">
      <div v-for="move in movieList" class="movie" @click="openMove(move.id)">
        <h3>{{ move.title }}</h3>
        <img :src="backendURL + move.poster" alt="">
      </div>

    </div>
  </div>

  <div v-if="currentStep === 2">
    <button @click="goBack"
            class="button">
      Go back
    </button>
    <div class="move-info">
      <div class="move-info-left">
        <img :src="backendURL + selectedFilmInfo.poster" alt="">
        <p>{{selectedFilmInfo.description}}</p>
      </div>
      <div class="move-info-center">
        <div>
          <ul>
            <li v-for="session in selectedFilmInfo.sessions">
              {{session.time}}
            </li>
          </ul>
        </div>
        <div>
          <div>
            Your chose
          </div>
          <div>
            Available
          </div>
          <div>
            Unavailable
          </div>
        </div>
      </div>
      <div class="move-info-right">
        Screen
      </div>
    </div>
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
  chosenDate: string = 'today';
  selectedFilmInfo: any = {};

  created() {
    this.makeRequest('api/v1/movies', 'get', {city_name: this.city},
        (movieList: any) => {
          store.commit('setTodayMovieList', movieList.today);
          store.commit('setTomorrowMovieList', movieList.tomorrow);

          this.movieList = store.state.todayMovieList;
        });
  }

  openMove(movie_id: number) {
    this.currentStep = 2;
    let date:string = '';
    if (this.chosenDate === 'today') {
      date = this.dateFormatter(new Date())
    } else if (this.chosenDate === 'tomorrow') {
      let tempDate = new Date();
      tempDate.setDate(tempDate.getDate()+1);
      date = this.dateFormatter(tempDate)
    } else {
      date = this.inputDate;
    }

    this.selectedFilmInfo = this.movieList.find((move: any) => {
      return move.id === movie_id;
    })
    this.makeRequest('api/v1/movie-session', 'get',
        {movie_id: movie_id, date: date, city_name: this.city},
        (moveInfo: any) => {
          this.selectedFilmInfo.sessions = moveInfo
        });
  }

  dateFormatter(date: Date): string {
    return `${date.getFullYear()}-${("0" + (date.getMonth() + 1)).slice(-2)}-${("0" + date.getDate()).slice(-2)}`;
  }

  choseTodayDate(): void {
    this.movieList = store.state.todayMovieList;
    this.chosenDate = 'today'
  }

  goBack() {
    if (this.currentStep === 2) {
      this.selectedFilmInfo = {};
      this.currentStep = 1;
    }
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
.move-info {
  margin-top: 10px;
  display: flex;
  .move-info-left {
    min-width: 350px;
    max-width: 30%;
    img {
      width: 350px;
    }
  }
  .move-info-center {
    padding: 0 10px;
    min-width: 100px;
    max-width: 10%;
  }
  .move-info-right {
    min-width: 500px;
    max-width: 60%;
  }
}
.button {
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