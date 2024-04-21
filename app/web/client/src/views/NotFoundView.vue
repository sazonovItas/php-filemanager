<script setup>
import "@/styles.scss";
</script>

<template>
  <div class="container container-fluid">
    <img
      class="w-25 container-fluid pt-5 pb-5"
      src="@/assets/logo.png"
      alt="file manager logo"
    />
    <h1 class="container-fluid">File Manager</h1>
    <h2 class="container-fluid">404 Page Not Found</h2>
  </div>
</template>

<style lang="scss" scoped>
@import "../scss/colors.scss";

$container_background_color: $neutral_color_2;
$container_font_color: $neutral_color_0;
$container_link_color: $primary_color_2;

.container {
  text-align: center;
  background-color: $container_background_color;
}

.container-links {
  padding: 0 0 20px 0;
}

h2 {
  padding: 0 0 20px 0;
  color: $container_link_color;
}

h1 {
  color: $container_font_color;
}
</style>
