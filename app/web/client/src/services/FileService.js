import axiosApi from "../http";

export default class FileService {
  static async getFiles(path) {
    return axiosApi.get(
      `${import.meta.env.VITE_BASE_API_URL}${import.meta.env.VITE_API_GET_FILES_ENDPOINT}?path=${path}`,
    );
  }

  static async deleteFile(path) {
    return axiosApi.delete(
      `${import.meta.env.VITE_BASE_API_URL}${import.meta.env.VITE_API_DELETE_FILE_ENDPOINT}`,
      {
        data: {
          path,
        },
      },
    );
  }

  static async getFileInfo(path) {
    return axiosApi.get(
      `${import.meta.env.VITE_BASE_API_URL}${import.meta.env.VITE_API_GET_FILE_INFO_ENDPOINT}?path=${path}`,
    );
  }

  static async getFilePreview(path) {
    return axiosApi.get(
      `${import.meta.env.VITE_BASE_API_URL}${import.meta.env.VITE_API_GET_FILE_PREVIEW_ENDPOINT}?path=${path}`,
      {
        responseType: "blob",
      },
    );
  }
}
