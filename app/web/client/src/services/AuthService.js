import axiosApi from "../http";

export default class AuthService {
  static async signUp(login, password) {
    return axiosApi.post(import.meta.env.VITE_API_SIGN_UP_ENDPOINT, {
      login: login,
      password: password,
    });
  }

  static async signIn(login, password, remember_me) {
    return axiosApi.post(import.meta.env.VITE_API_SIGN_IN_ENDPOINT, {
      login: login,
      password: password,
      remember_me: remember_me,
    });
  }

  static async logout() {
    return axiosApi.post(import.meta.env.VITE_API_LOGOUT_ENDPOINT);
  }
}
