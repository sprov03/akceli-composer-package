import Vue from 'vue';

export function all() {
  return Vue.http().get(`/api/projects`);
}

export function get(id) {
  return Vue.http().get(`/api/projects/${id}`);
}

export function create(data, validationErrors) {
  return Vue.http(validationErrors).post(`/api/projects`, data);
}

export function update(data, validationErrors) {
  return Vue.http(validationErrors).put(`/api/projects/${data.id}`, data);
}

export function destory(id) {
  return Vue.http().delete(`/api/projects/${id}`);
}

export function save(data, validationErrors) {
  let apiCall = (data.id) ? this.update : this.create;
  return apiCall(data, validationErrors);
}

