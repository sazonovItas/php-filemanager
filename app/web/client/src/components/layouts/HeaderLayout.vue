<script setup>
import { defineComponent } from "vue";
</script>

<template>
  <div class="v-header">
    <img class="v-logo" src="../../assets/logo.png" alt="logo" />
    <div class="v-login">
      <span class="v-color-primary-2" style="font-size: 18px">{{ login }}</span>
    </div>
  </div>
</template>

<script>
export default defineComponent({
  data() {
    return {
      login: localStorage.getItem("login"),
    };
  },
});
</script>

<style scoped lang="scss">
@import "../../scss/colors.scss";

.v-header {
  width: 100%;
  height: 8%;

  display: flex;
  justify-content: space-between;

  border: 2px solid $neutral_color_2;
}

.v-logo {
  padding: 5px;
  max-height: 100%;
}

.v-login {
  padding: 5px 15px 5px 5px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.v-login-icon {
  fill: $primary_color_2;
}
</style>
