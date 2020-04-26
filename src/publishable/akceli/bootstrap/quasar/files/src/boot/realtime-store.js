import { boot } from 'quasar/wrappers'
import RealtimeStore from "laravel-realtime-database-vuex";
import Pusher from 'pusher-js';

export default boot(async ({ app, router, store, Vue }) => {
  // something to do
    RealtimeStore.setVue(Vue);
    RealtimeStore.setStore(store);
    RealtimeStore.setPusher(new Pusher('ddd8dee85fbbcc81cdfd', {cluster: 'us2', forceTLS: true}));
});