import axios from "axios";
import RealtimeStore from "laravel-realtime-database-vuex";
import Vue from 'vue';
import store from '../store';
import http from '../http';
import router from './../router';

const redirectURL = 'pipeline';

export default {
  register(data) {
    return http().post('/api/register', data)
    .then(response => {
      router.push({ name: redirectURL });
      
      return {data: response.data}
    });
  },
  login(loginForm) {
    return axios.get('http://api.example.local/sanctum/csrf-cookie').then(response => {
      return axios.get('http://api.gittask.local/api/validate-cookies')
      .then(() => {
        /**
         * Auth is handled by secure http cookies at this point so no need to worry about it for spas
         */
        return Vue.http().post('api/login', loginInfo).then(response => {
          /**
           * Need to set the Auth User Info Here (Only Public data, Aka Name and stuffs like that)
           */
        });
      })
      .catch(error => {
        /**
         * If we failed to validate the cookies, the we can assume that the build is not a spa build
         */
        return axios.post('http://api.gittask.local/api/request-access-token', loginInfo)
        .then(response => {
          store().commit('auth/setAccessToken', response.data);
          RealtimeStore.setAxios(axios.create({
            baseURL: 'http://api.gittask.local/api/client-store',
            headers: {'Authorization': 'Bearer ' + response.data},
            withCredentials: true
          }));
        });
      })
    })
  },
  logout() {
    RealtimeStore.unsubscribeFromAllChannels();
    store().commit('auth/setAccessToken', null);
    RealtimeStore.setAxios(axios.create({
      baseURL: 'http://api.gittask.local/api/client-store',
      headers: {'Authorization': 'Bearer ' + null},
      withCredentials: true
    }));
  
    return Vue.http().post('api/logout').catch(error => {
      // This is here to keep the stateful error form being an issue if its not a spa build.
    });
  },
}

