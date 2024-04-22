<script setup>
import { defineComponent, ref } from "vue";
import { useRoute } from "vue-router";
import HeaderLayout from "@/components/layouts/HeaderLayout.vue";
import FileIcon from "@/components/FileIcon.vue";
import axiosApi from "../http";

import FileService from "../services/FileService";
</script>

<template>
  <div class="v-container">
    <HeaderLayout />
    <div class="v-drive-container">
      <div class="v-drive-container-buttons">
        <div>
          <input
            class="v-func-button btn btn-sm"
            type="file"
            @change="handleUploadFile"
            ref="file"
          />
          <button class="v-func-button btn" @click="submitFile">Upload</button>
        </div>
        <button class="v-func-button btn">Create</button>
        <p style="align-self: center; color: #dddd00">{{ infoMessage }}</p>
      </div>
      <div class="v-drive-files-container">
        <h3 style="align-self: center">Path: {{ path }}</h3>
        <div class="v-drive-files">
          <div v-if="files.length === 0">Not found files</div>
          <div
            v-else
            class="v-drive-file"
            v-for="(file, index) in files"
            v-bind:key="index"
          >
            <FileIcon
              :fileType="file.type"
              :mimeType="file.mimeType"
              @click="fileOnClick(file)"
            />
            <div class="v-drive-file-info">
              <p>{{ file.name }}</p>
              <p>Size: {{ file.size }}</p>
              <p>Mime: {{ file.mimeType }}</p>
            </div>
            <div class="dropdown">
              <button
                class="btn btn-secondary dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Edit
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" href="#">Move</a>
                </li>
                <li><a class="dropdown-item" href="#">Rename</a></li>
                <li v-if="file.type == 2" @click="deleteFile(file)">
                  <a class="dropdown-item" href="#">Delete</a>
                </li>
                <li v-if="file.type == 2" @click="downloadFile(file)">
                  <a class="dropdown-item" href="#">Download</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default defineComponent({
  data() {
    const route = useRoute();
    const files = ref([]);

    return {
      path: route.query?.path === undefined ? "" : route.query.path,
      uploadFiles: null,
      infoMessage: "",
      files: files,
    };
  },
  methods: {
    fileOnClick(file) {
      if (file.type == 1) {
        if (file.name === ".." && this.path !== "") {
          this.path = this.path.split("/").slice(0, -1).join("/");
        } else if (file.name !== "..") {
          this.path = this.path.concat("/", file.name);
        }
        this.getFiles();
        this.$router.push({
          path: this.$route.path,
          query: { path: this.path },
        });
      }
    },
    deleteFile(file) {
      FileService.deleteFile(file.path)
        .then((response) => {
          if (response.status >= 200 && response.status <= 299) {
            this.getFiles();
          }
          console.log(response);
        })
        .catch((e) => {
          console.log(e);
          this.infoMessage = e.response.data.message ?? e.message;
          if (e.response.status == 401) {
            localStorage.removeItem("accessToken");
            this.$router.push({ name: "login" });
          }
        });
    },
    handleUploadFile() {
      this.uploadFiles = this.$refs.file.files[0];
    },
    submitFile() {
      if (!this.uploadFiles) {
        this.infoMessage = "need to choose file";
        return;
      }

      const formData = new FormData();
      formData.append("file", this.$refs.file.files[0]);

      const headers = {
        "Content-Type": "multipart/form-data",
        "X-Load-Path": this.path,
      };
      console.log(formData);
      console.log(this.uploadFiles.name);
      axiosApi
        .post(
          `${import.meta.env.VITE_BASE_API_URL}${import.meta.env.VITE_API_UPLOAD_ENDPOINT}`,
          formData,
          {
            headers: headers,
          },
        )
        .then((res) => {
          console.log(res);
          if (res.status >= 200 && res.status <= 299) {
            this.infoMessage = "file uploaded";
            this.getFiles();
          }
        })
        .catch((e) => {
          console.log(e);
          this.infoMessage = e.response.data.message ?? e.message;
          if (e.response.status == 401) {
            localStorage.removeItem("accessToken");
            this.$router.push({ name: "login" });
          }
        });
    },
    getFiles() {
      FileService.getFiles(this.path)
        .then((response) => {
          if (response.status >= 200 && response.status <= 299) {
            this.files = response.data;
          }
          console.log(response);
        })
        .catch((e) => {
          console.log(e);
          this.infoMessage = e.response.data.message ?? e.message;
          if (e.response.status == 401) {
            localStorage.removeItem("accessToken");
            this.$router.push({ name: "login" });
          }
        });
    },
    downloadFile(file) {
      axiosApi
        .get(
          `${import.meta.env.VITE_BASE_API_URL}${import.meta.env.VITE_API_DOWNLOAD_ENDPOINT}?path=${file.path}`,
          {
            responseType: "blob", // important
          },
        )
        .then((response) => {
          // create file link in browser's memory
          const href = URL.createObjectURL(response.data);

          // create "a" HTML element with href to file & click
          const link = document.createElement("a");
          link.href = href;
          link.setAttribute("download", file.name); //or any other extension
          document.body.appendChild(link);
          link.click();

          // clean up "a" element & remove ObjectURL
          document.body.removeChild(link);
          URL.revokeObjectURL(href);
        });
    },
  },
  components: {
    HeaderLayout,
    FileIcon,
  },
  mounted() {
    this.getFiles();
  },
  computed: {
    getUserLogin() {
      return localStorage.getItem("login");
    },
  },
});
</script>

<style scoped lang="scss">
@import "../scss/colors.scss";

.v-container {
  width: 100%;

  background-color: $neutral_color_4;
}

.v-drive-files {
  display: flex;
  flex-wrap: wrap;
}

.v-drive-file {
  margin: 15px;
  background-color: $neutral_color_2;

  display: flex;
  border-radius: 8px;
  box-shadow: 5px 3px 3px $neutral_color_3;
}

p {
  line-height: 14px;
}

.v-drive-file-info {
  margin: 10px 10px 0 0;
}

.v-drive-container {
  width: 100%;
  height: 92%;

  border: 2px solid $neutral_color_2;
  border-top: none;
}

.v-func-button {
  margin: 10px;
  max-width: 250px;

  background-color: $primary_color_2;
  color: #f2f2f2;
  border: 2px solid $primary_color_2;
  align-self: center;
}

.v-func-button:hover {
  background-color: $primary_color_3;
}

.v-drive-container-buttons {
  padding: 10px;
  display: flex;

  width: 100%;
}
</style>
