import api from '../api';

// "async" is optional
export default async ({ app, router, Vue}) => {
  Vue.prototype.$api = api;
}
