<template>
    <div>
        <input v-model="form.email">
        <input v-model="form.password">

        <button @click="login">Login</button>
    </div>
</template>

<script>
  import axios from "axios";
  import http from '../http'

  export default {
    name: "loginForm",
    data() {
      return {
        form: {
          email: 'sprov03@gmail.com',
          password: 'password'
        },
      }
    },
    methods: {
      login() {
        return axios.get('http://api.testing.local/sanctum/csrf-cookie').then(response => {
          /**
           * Auth is handled by secure http cookies at this point so no need to worry about it for spas
           */
          return http().post('api/login', this.form).then(response => {
            /**
             * Need to set the Auth User Info Here (Only Public data, Aka Name and stuffs like that)
             */
          })
          .catch(error => {
            /**
             * If we failed to validate the cookies, the we can assume that the build is not a spa build
             */
            return axios.post('http://api.testing.local/api/request-access-token', this.form)
            .then(response => {
              // store().commit('auth/setAccessToken', response.data);
              // RealtimeStore.setAxios(axios.create({
              //   baseURL: 'http://api.testing.local/api/client-store',
              //   headers: {'Authorization': 'Bearer ' + response.data},
              //   withCredentials: true
              // }));
            });
          })
        })
      },
    }
  }
</script>

<style scoped>

</style>