<template>
  <BookingTickets
      :city="$options.city"
      :apiKey="$options.apiKey"
      :backendURL="$options.backendURL"
  ></BookingTickets>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component';
import BookingTickets from './components/BookingTickets.vue';


@Options({
  city: process.env.VUE_APP_CITY,
  apiKey:  process.env.VUE_APP_API_KEY,
  backendURL:  process.env.VUE_APP_BACKEND_URL,
  components: {
    BookingTickets
  }
})
export default class App extends Vue {}
</script>
