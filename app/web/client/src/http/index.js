import axios from "axios";

const axiosApi = axios.create({
  withCredentials: true,
  baseURL: import.meta.env.VITE_BASE_API_URL,
});

axiosApi.interceptors.request.use((config) => {
  config.headers.Authorization = `Bearer ${localStorage.getItem("accessToken")}`;
  return config;
});

axiosApi.interceptors.response.use(
  (config) => {
    return config;
  },
  async (error) => {
    const originalRequest = error.config;
    if (
      error.response.status == 401 &&
      error.config &&
      !error.config._isRetry
    ) {
      originalRequest._isRetry = true;
      try {
        const response = await axios.get(
          `${import.meta.env.VITE_BASE_API_URL}${import.meta.env.VITE_API_REFRESH_ENDPOINT}`,
          { withCredentials: true },
        );
        localStorage.setItem("accessToken", response.data.accessToken);
        return axiosApi.request(originalRequest);
      } catch (error) {
        console.log("not authorized");
      }
    }

    throw error;
  },
);

export default axiosApi;
