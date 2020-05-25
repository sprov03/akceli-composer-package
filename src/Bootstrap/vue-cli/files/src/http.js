import axios from 'axios/index';
import RealtimeStore from "laravel-realtime-database-vuex";
import auth from "./api/auth";

function http() {
  const httpInstance = axios.create({
    baseURL: 'http://api.testing.local',
    //headers: {'Authorization': `Bearer ${LocalStorageService.getApiToken()}`}
  });
  
  httpInstance.interceptors.response.use(
    response => successHandler(response),
    error => errorHandler(error),
  );
  
  return httpInstance;
}

const successHandler = (response) => {
  response = RealtimeStore.apiSuccessMiddleware(response);
  
  return response;
};

const errorHandler = (error) => {
  error = RealtimeStore.apiErrorMiddleware(error);
  
  if (error.response.status === 401 || error.response.status === 403) {
    auth.logout();
    return Promise.reject({ ...error })
  }
  
  if (error.response.status === 422) {
    return Promise.reject({ ...error })
  
  }
  
  /**
   * Add global Error Handling Here
   */
  
  return Promise.reject({ ...error })
};

export default http;