import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth-store'
import RealtimeStore from "laravel-realtime-database-vuex";

Vue.use(Vuex);

/*
 * If not building with SSR mode, you can
 * directly export the Store instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Store instance.
 */

export default function (/* { ssrContext } */) {
  return new Vuex.Store({
    modules: {
      auth,
    },
    mutations: {
      ...RealtimeStore.storeMutations
    },

    // enable strict mode (adds overhead!)
    // for dev mode only
    strict: process.env.DEV
  })
}
