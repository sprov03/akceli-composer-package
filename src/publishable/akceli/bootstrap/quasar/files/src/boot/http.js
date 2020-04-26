import axios from 'axios/index';
import RealtimeStore from "laravel-realtime-database-vuex";


const successHandler = (response) => {
  response = RealtimeStore.apiSuccessMiddleware(response);
  
  return response;
};

const errorHandler = (error, validationErrors) => {
  error = RealtimeStore.apiErrorMiddleware(error);
  
  if (error.response.status === 426) {
    // Snackbar.open({
    //   message: 'We’ve made changes to GitTask recently, please refresh your page to get the latest changes',
    //   type: 'is-warning',
    //   indefinite: true,
    //   actionText: 'Refresh',
    //   onAction() {
    //     location.reload()
    //   }
    // });
    
    return Promise.reject({ ...error })
  }
  
  if (error.response.status === 401 || error.response.status === 403) {
    // auth.logout();
    return Promise.reject({ ...error })
  }
  
  if (error.response.status === 504) {
    // Todo: Need to add error reporting here
    // Snackbar.open({
    //   message: 'Server Timeout: data set is too large',
    //   type: 'is-danger',
    //   indefinite: true
    // });
    
    return Promise.reject({ ...error })
  }
  
  if (error.response.status !== 422) {
    console.log('Error: ', error, error.response);
    validationErrors = error.response.data.messages;
    // Snackbar.open({
    //   message: error.response.data.message,
    //   type: 'is-danger',
    //   indefinite: true
    // });
    
    return Promise.reject({ ...error })
  }
  
  return Promise.reject({ ...error })
};


// "async" is optional
export default async ({app, router, Vue, store}) => {
  let http = (validationErrors) => {
    validationErrors = validationErrors || {};
    
    const httpInstance = axios.create({
      baseURL: 'http://api.gittask.local',
      headers: {'Authorization': 'Bearer ' + store.state.auth.accessToken},
      withCredentials: true
    });
    
    httpInstance.interceptors.response.use(
      response => successHandler(response),
      error => errorHandler(error, validationErrors),
    );
    
    return httpInstance;
  };
  
  Vue.prototype.$http = http;
}
