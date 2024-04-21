import axiosApi from "../http";

export default class FileService {
  static async getFiles(path) {
    return axiosApi.get(
      `${import.meta.env.VITE_BASE_API_URL}${import.meta.env.VITE_API_GET_FILES_ENDPOINT}?path=${path}`,
    );
  }
}
