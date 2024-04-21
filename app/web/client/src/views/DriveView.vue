<script setup>
import "../styles.scss";
import { defineComponent } from "vue";
import { useRoute } from "vue-router";
import HeaderLayout from "@/components/layouts/HeaderLayout.vue";
</script>

<template>
  <div class="v-container">
    <HeaderLayout />
    <div class="v-drive-container">
      <div class="v-drive-container-buttons"></div>
    </div>
  </div>
</template>

<script>
export default defineComponent({
  data() {
    const route = useRoute();
    return {
      path: route.query?.path === undefined ? "" : route.query.path,
    };
  },
  methods: {},
  components: {
    HeaderLayout,
  },
  mounted() { },
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
  height: 100%;

  background-color: $neutral_color_3;
}

.v-drive-container {
  width: 100%;
  height: 92%;

  border: 2px solid $neutral_color_2;
  border-top: none;
}

.v-drive-container-buttons {
  display: flex;

  width: 100%;
}
</style>
