<script setup>
import { defineComponent } from "vue";
</script>

<template>
  <div
    v-if="getMime == 'folder'"
    style="background-color: #0040cc"
    class="v-file-icon"
  >
    <img src="../assets/folder_icon.png" alt="folder" class="v-file-icon-img" />
  </div>

  <div
    v-else-if="getMime == 'text'"
    style="background-color: #40dd40"
    class="v-file-icon"
  >
    <img src="../assets/text_icon.png" alt="folder" class="v-file-icon-img" />
  </div>
  <div
    v-else-if="getMime == 'image'"
    style="background-color: #dddd00"
    class="v-file-icon"
  >
    <img src="../assets/image_icon.png" alt="folder" class="v-file-icon-img" />
  </div>
  <div v-else style="background-color: #dd4000" class="v-file-icon">
    <img src="../assets/other_icon.png" alt="folder" class="v-file-icon-img" />
  </div>
</template>

<script>
export default defineComponent({
  props: {
    fileType: String,
    mimeType: String,
  },
  data() {
    return {};
  },
  computed: {
    getMime() {
      if (this.fileType == "1") {
        return "folder";
      }

      return this.mimeType.split("/")[0];
    },
  },
});
</script>

<style lang="scss">
.v-file-icon {
  margin: 8px;
  padding: 5px;
  display: flex;
  justify-content: center;

  width: 45px;
  height: 45px;

  border-radius: 5px;
}

.v-file-icon:hover {
  border: 3px solid #dddddd;
}

.v-file-icon-img {
  align-self: center;

  width: 30px;
}
</style>
