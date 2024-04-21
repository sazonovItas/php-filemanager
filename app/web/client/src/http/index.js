import axios from "axios";

const axiosApi = axios.create({
  withCredentials: true,
  baseURL: import.meta.env.VITE_BASE_API_URL,
});

axiosApi.interceptors.request.use((config) => {
  config.headers.Authorization = `Bearer ${localStorage.getItem("accesToken") ?? ""}`;
  return config;
});

export default axiosApi;
