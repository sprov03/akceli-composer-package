import Vue from 'vue';

export function all() {
return Vue.http().get(`/api/[[model_names]]`);
}

export function get(id) {
return Vue.http().get(`/api/[[model_names]]/${id}`);
}

export function create(data) {
return Vue.http().post(`/api/[[model_names]]`, data);
}

export function update(data) {
return Vue.http().put(`/api/[[model_names]]/${data.id}`, data);
}

export function destory(id) {
return Vue.http().delete(`/api/[[model_names]]/${id}`);
}

export function save(data) {
let apiCall = (data.id) ? this.update : this.create;
return apiCall(data);
}

