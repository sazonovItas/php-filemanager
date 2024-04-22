<script setup>
import { defineComponent, ref } from "vue";
import FileService from "../services/FileService";
</script>

<template>
  <div class="v-file-preview-container">
    <div class="v-file-preview">
      <img
        :src="img"
        v-if="file && file.mimeType?.split('/')[0] == 'image'"
        id="download_img"
      />
      <textarea
        v-else-if="file && file.mimeType?.split('/')[0] == 'text'"
        v-model="text"
        id="download_text"
      >
      </textarea>
      <p v-else><span style="color: #dd0000">Not supported file type</span></p>
    </div>
    <div class="v-file-info">
      <p><span style="color: #dddd00">Name:</span> {{ file?.name }}</p>
      <p>
        <span style="color: #dddd00">Path:</span> {{ `${path}/${file?.name}` }}
      </p>
      <p><span style="color: #dddd00">Size:</span> {{ file?.size }}</p>
      <p><span style="color: #dddd00">Mime:</span> {{ file?.mimeType }}</p>
      <p>{{ infoMessage }}</p>
    </div>
  </div>
</template>

<script>
export default defineComponent({
  data() {
    if (this.$route.query?.path === undefined) {
      this.$router.push({ name: "drive" });
    }

    const path = ref(this.$route.query.path);
    return {
      path: path,
      file: null,
      img: null,
      text: null,
      infoMessage: "",
    };
  },
  methods: {
    getFile() {},
  },
  mounted() {
    FileService.getFileInfo(this.path)
      .then((response) => {
        if (response.status >= 200 && response.status <= 299) {
          this.file = response.data;

          if (!this.file || !this.file?.mimeType) {
            return;
          }

          if (this.file.mimeType.split("/")[0] == "image") {
            console.log("image");
            FileService.getFilePreview(this.path).then((resp) => {
              this.img = URL.createObjectURL(resp.data);
            });
          }

          if (this.file.mimeType.split("/")[0] == "text") {
            console.log("text");
            FileService.getFilePreview(this.path).then((resp) => {
              const parseText = async () => {
                this.text = await resp.data.text();
              };
              parseText();
            });
          }
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
});
</script>

<style lang="scss" scoped>
@import "../scss/colors.scss";

.v-file-preview-container {
  display: flex;
}

p {
  margin: 5px;
  line-height: 20px;
}

#download_img {
  max-width: 600px;
  max-height: 600px;
  border-radius: 8px;
}

#download_text {
  width: 600px;
  height: 600px;
}

.v-file-preview {
  max-width: 600px;
  max-height: 600px;

  margin: 15px;
  border: 2px solid $neutral_color_2;
  border-radius: 8px;
}

.v-file-info {
  margin: 15px;
  background-color: $neutral_color_2;

  border-radius: 8px;
  box-shadow: 5px 3px 3px neutral_color_3;
}
</style>
