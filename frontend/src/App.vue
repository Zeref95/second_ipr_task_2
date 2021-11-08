<template>
  <BookingTickets city="Taganrog"></BookingTickets>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component';
import BookingTickets from './components/BookingTickets.vue';


@Options({
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
