<template>
  <div v-if="currentStep === 1">
    <div>
      <button @click="choseTodayDate"
              class="input"
              :class="{'chosen-day': chosenDate === 'today'}">
        Today
      </button>
      <button
          @click="choseTomorrowDate"
          class="input"
          :class="{'chosen-day': chosenDate === 'tomorrow'}">
        Tomorrow
      </button>
      <b>Or chose a day: </b>
      <input class="input" type="date" v-model="inputDate" :class="{'chosen-day': chosenDate === 'input'}">
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
    <nav>
      <button @click="goBack"
              class="input">
        Go back
      </button>
      <button
          v-if="chosenPlaces.length > 0"
      class="input"
      @click="makeOrder">
        Order
      </button>
    </nav>
    <div class="move-info">
      <div class="move-info-left">
        <img :src="backendURL + selectedFilmInfo.poster" alt="">
        <p>{{ selectedFilmInfo.description }}</p>
      </div>
      <div class="move-info-center">
        <div>

          <ul>
            <li
                v-for="session in selectedFilmInfo.sessions"
                :class="{'active': selectedFilmInfo?.chosenTime === session.time}"
                @click="selectedFilmInfo.chosenTime = session.time"
            >
              {{ session.time }}
            </li>
          </ul>
        </div>
        <div>
          <div class="flex-center">
            <div class="cube cube-your-chose"></div>
            Your chose
          </div>
          <div class="flex-center">
            <div class="cube cube-available"></div>
            Available
          </div>
          <div class="flex-center">
            <div class="cube cube-unavailable"></div>
            Unavailable
          </div>
        </div>
      </div>
      <div class="move-info-right" v-if="chosenSession">
        <div class="screen">Screen</div>
        <div class="alert-danger" v-if="isOldSession">
          Session id old. You can not make order
        </div>
        <div v-else class="plates" v-if="chosenSession">
          <div
              v-for="place in chosenSession.places"
             class="cube-container"
          >
            <div
                @click="selectPlace(place.place)"
                class="cube"
                :class="{
                  'cube-available':place.status === 'free',
                  'cube-unavailable':place.status === 'taken',
                  'cube-your-chose':place.status === 'your-chose'
                }"
            >
              {{ place.place }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-if="currentStep === 3">
    <div class="order-finish">
      <div>
        <img :src="backendURL + selectedFilmInfo.poster" alt="">
      </div>
      <div class="order-info">
        <h2>Thanks for your order</h2>
        <div>
          <table>
            <tr>
              <td>Movie:</td>
              <td>{{answerResponse.move_name}}</td>
            </tr>
            <tr>
              <td>Date:</td>
              <td>{{answerResponse.date_time}}</td>
            </tr>
            <tr>
              <td>Seats</td>
              <td>{{answerResponse.seats}}</td>
            </tr>
          </table>
        </div>

      </div>
    </div>
  </div>

</template>

<script lang="ts">
import {Options, Vue} from 'vue-class-component';
import store from '@/store'
import { computed } from 'vue'

export interface MovieInterface {
  id: number,
  description: string,
  poster: string ,
  title: string
}
export interface AnswerResponseInterface {
  date_time?: string,
  message?: string,
  move_name?: string,
  seats?: string,
  status?: string
}
export interface PlaceInterface {
  row: number,
  place: number,
  status: string ,
}
export interface SessionsInterface {
  city_id: number,
  id: number,
  date: string ,
  movie_id: number,
  time: string,
  places: PlaceInterface[]
}

@Options({
  props: {
    city: String,
    apiKey: String,
    backendURL: String
  },
  watch: {
    inputDate(val: string) {
      this.chosenDate = 'input'
      this.makeRequest('api/v1/get-movies-by-date', 'get', {city_name: this.city, date: val},
          (movieList: any) => {
            this.movieList = movieList;
          });
    }
  },
})

export default class BookingTickets extends Vue {
  city!: string;
  apiKey!: string;
  backendURL!: string;
  currentStep: number = 1;
  movieList: MovieInterface[] = [];
  inputDate: string = '';
  chosenDate: string = 'today';
  selectedFilmInfo: any = {};
  chosenSession: any = computed(() => {
    if (this.selectedFilmInfo.chosenTime && this.selectedFilmInfo.sessions) {
      return this.selectedFilmInfo.sessions.find((session: SessionsInterface) => {
        return session.time === this.selectedFilmInfo.chosenTime;
      })
    }
  })
  inAwaiting: boolean = false;
  chosenPlaces: any = computed(() => {
    if (this.chosenSession?.places) {
      let places: PlaceInterface[] = this.chosenSession.places.filter((place: PlaceInterface) => {
        return place.status === 'your-chose';
      });
      let placesArray: number[] = [];
      places.forEach((place: PlaceInterface) => {
        placesArray.push(place.place)
      })
      return placesArray;
    } else {
      return [];
    }
  })
  answerResponse: AnswerResponseInterface = {};

  isOldSession: any = computed(():boolean => {
    if (!this.chosenSession)
      return true;
    let sessionDate = new Date(`${this.chosenSession.date}T${this.chosenSession.time}:00`);
    return sessionDate < new Date;
  })

  created() {
    this.makeRequest('api/v1/movies', 'get', {city_name: this.city},
        (movieList: {today: MovieInterface[], tomorrow: MovieInterface[]} ) => {
          store.commit('setTodayMovieList', movieList.today);
          store.commit('setTomorrowMovieList', movieList.tomorrow);

          this.movieList = store.state.todayMovieList;
        });
  }

  openMove(movie_id: number) {
    this.currentStep = 2;
    let date: string = '';
    if (this.chosenDate === 'today') {
      date = this.dateFormatter(new Date())
    } else if (this.chosenDate === 'tomorrow') {
      let tempDate: Date = new Date();
      tempDate.setDate(tempDate.getDate() + 1);
      date = this.dateFormatter(tempDate)
    } else {
      date = this.inputDate;
    }
    let key: string = `${movie_id}/${date}`

    this.selectedFilmInfo = this.movieList.find((move: MovieInterface) => {
      return move.id === movie_id;
    })

    if (store.state.sessionList[key]) {
      this.selectedFilmInfo.sessions = store.state.sessionList[key];
      return;
    }
    this.makeRequest('api/v1/movie-session', 'get',
        {movie_id: movie_id, date: date, city_name: this.city},
        (moveInfo: MovieInterface) => {
          store.commit('PushToMovieSessionList', {key: key, data: moveInfo});
          this.selectedFilmInfo.sessions = store.state.sessionList[key];
        });
  }

  dateFormatter(date: Date): string {
    return `${date.getFullYear()}-${("0" + (date.getMonth() + 1)).slice(-2)}-${("0" + date.getDate()).slice(-2)}`;
  }

  choseTodayDate(): void {
    this.movieList = store.state.todayMovieList;
    this.chosenDate = 'today'
  }

  goBack():void {
    if (this.currentStep === 2) {
      this.selectedFilmInfo = {};
      this.currentStep = 1;
    }
  }

  choseTomorrowDate(): void {
    this.movieList = store.state.tomorrowMovieList;
    this.chosenDate = 'tomorrow'
  }

  selectPlace(place: number): void {
    let chosenPlace:PlaceInterface = this.chosenSession.places.find((placeInfo: PlaceInterface) => {
      return placeInfo.place === place;
    })
    if (chosenPlace.status === 'free') {
      if (this.chosenPlaces.length < 6) {
        chosenPlace.status = 'your-chose';
      } else {
        alert('You cannot take more then 6 tickets')
      }

    } else if (chosenPlace.status === 'your-chose'){
      chosenPlace.status = 'free';
    }
  }

  makeOrder() {
    if (this.inAwaiting)
      return;
    this.inAwaiting = true
    this.makeRequest('api/v1/order', 'POST', {
          session_id: this.chosenSession.id,
          places: this.chosenPlaces,
          is_test: process.env.VUE_APP_IS_TEST
        },
        (data: any) => {
          this.answerResponse = data;
          if (data.error) {
            alert('Something was wrong');
          } else  {
            this.currentStep = 3;
            this.inAwaiting = false;
          }
        });
  }

  async makeRequest(url: string, method: string, data: any, callback: (date:any) => void) {
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

    let fetchObj: {method: string, headers: any, body?:string } = {
      method: method,
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Authorization': this.apiKey
      },
    };
    if (method === 'POST') {
      fetchObj.body = JSON.stringify(data);
      fetchObj.headers['Content-Type'] = 'application/json'
    }

    await fetch(urlFetch, fetchObj)
        .then(res => res.json())
        .then((json: string) => {
          callback(json)
        })
        .catch((err: {message: string}) => {
          if (err.message) {
            alert(err.message)
          } else {
            console.error(err);
          }
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
    text-align: justify;
    min-width: 350px;
    max-width: 30%;
    img {
      width: 350px;
    }
  }
  .move-info-center {
    padding: 0 10px;
    min-width: 150px;
    max-width: 15%;
    ul {
      border: 1px solid black;
      border-radius: 5px;
      list-style: none;
      padding: 0;
      text-align: center;
      li {
        background-color: #85a8cb;
        color: white;
        margin: 2px;
        cursor: pointer;
      }
      .active {
        background-color: #2c3e50;
      }
    }
  }

  .move-info-right {
    min-width: 500px;
    border: 1px solid black;
    flex-grow: 1;
    .screen {
      height: 18px;
      background-color: #2c3e50;
      color: white;
      text-align: center;
      width: 80%;
      margin: 10px auto;
    }
    .plates {
      margin: 20vh auto 0;
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      grid-auto-rows: 100px;

    }
  }
}

.input {
  margin: 0 5px;
  width: 150px;
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

nav {
  display: flex;
  justify-content: space-between;
}

.cube {
  width: 40px;
  height: 40px;
  border-radius: 3px;
  margin-right: 5px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.cube-container {
  display: flex;
  align-items: center;
  justify-content: center;
  .cube-available, .cube-your-chose {
    cursor: pointer;
  }
}

.cube-your-chose {
  border: #dbc60c 3px solid;
}

.cube-available {
  border: #27d227 3px solid;
}

.cube-unavailable {
  border: #338ffd 3px solid;
}

.flex-center {
  display: flex;
  align-items: center;
  div {
    margin: 5px;
  }
}

.alert-danger {
  background-color: #ea8080;
  border: 1px solid crimson;
  font-size: 1.2em;
  text-align: center;
}

.order-finish {
  display: flex;
  img {
    width: 300px;
  }
}
.order-info {
  width: 100%;
  h2 {
    text-align: center;
  }
  div {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-top: 50px;
    table {
      font-size: 1.3em;
      td {
        padding: 0 40px;
      }
    }
  }
}

</style>