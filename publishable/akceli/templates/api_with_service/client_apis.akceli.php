import Vue from 'vue';

export function all() {
  return Vue.http().get(`/api/[[model_names]]`);
}

export function get(id) {
  return Vue.http().get(`/api/[[model_names]]/${id}`);
}

export function create(data, validationErrors) {
  return Vue.http(validationErrors).post(`/api/[[model_names]]`, data);
}

export function update(data, validationErrors) {
  return Vue.http(validationErrors).put(`/api/[[model_names]]/${data.id}`, data);
}

export function destory(id) {
  return Vue.http().delete(`/api/[[model_names]]/${id}`);
}

export function save(data, validationErrors) {
  let apiCall = (data.id) ? this.update : this.create;
  return apiCall(data, validationErrors);
}

