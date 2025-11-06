import http from '@/api/http';

export default {
  all() {
    return http().get(`/api/[[model_names]]`);
  },
  get(id) {
    return http().get(`/api/[[model_names]]/${id}`);
  },
  create(data, validationErrors) {
    return http(validationErrors).post(`/api/[[model_names]]`, data);
  },
  update(data, validationErrors) {
    return http(validationErrors).put(`/api/[[model_names]]/${data.id}`, data);
  },
  delete(id) {
    return http().delete(`/api/[[model_names]]/${id}`);
  },
  save(data, validationErrors) {
    let apiCall = (data.id) ? this.update : this.create;
    return apiCall(data, validationErrors);
  }
}
