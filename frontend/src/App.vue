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
  city: "Taganrog",
  apiKey:  'YiL2x3O3CETQzgICNnIFkcgHyfuzVPPTV4Msrg2vOAV6Fd2WBwk9KBRVHw7h5yyI',
  backendURL:  'http://127.0.0.1/',
  components: {
    BookingTickets
  },
})
export default class App extends Vue {}
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
</style>
