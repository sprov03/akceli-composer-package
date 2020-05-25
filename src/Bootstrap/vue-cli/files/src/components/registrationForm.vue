<template>
    <div>
        <input v-model="form.email">
        <input v-model="form.password">
        <input v-model="form.password_confirmation">

        <button @click="register">Register</button>
    </div>
</template>

<script>
  import axios from "axios";
  import http from '../http'

  export default {
    name: "registrationForm",
    data() {
      return {
        form: {
          name: 'Shawn Pivonka',
          email: 'sprov03@gmail.com',
          password: 'password',
          password_confirmation: 'password',
        }
      }
    },
    methods: {
      register() {
        return http().post('/api/register', this.form)
        .then(response => {
          return {data: response.data}
        });
      },
    }
  }
</script>

<style scoped>

</style>