<template>
  <div v-if="currentStep === 1">
    <div>
      Today
      Tomorrow
      Or chose a day
      <input type="date">
      img ?
    </div>
    <hr>
    <div v-if="error">{{error}}</div>
    <div v-else>
      <div v-for="move in todayMovieList">
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

@Options({
  props: {
    city: String
  }
})
export default class BookingTickets extends Vue {
  city!: string
  currentStep: number = 1
  apiKey: string = '4T3jSHclRYM3zJrntnuwo1lFv2vBw1n3BDxkPrKyKcpdPHPwqEo5kD7tw0irp0l4'
  backendURL: string = 'http://127.0.0.1/'
  error:string = ''
  todayMovieList:any = []

  created() {
    this.makeRequest('api/v1/movies', 'get', {city_name: this.city},
        (movieList: any) =>  {
      if (movieList.length === 0) {
        this.error = 'There is no sessions on today and tomorrow';
      } else {
        this.todayMovieList = movieList;
      }
    });
  }

  async makeRequest(url: string, method: string, data: object, callback: any) {
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
</style>