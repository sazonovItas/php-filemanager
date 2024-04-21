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
    <div class="container-fluid container-links">
      <a href="login">Login</a>
      <a href="drive">Files</a>
    </div>
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

a {
  padding: 0 10px 0 10px;
  color: $container_link_color;
  font-size: 28px;
}

h1 {
  color: $container_font_color;
}
</style>
