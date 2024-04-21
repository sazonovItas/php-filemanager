<script setup>
import { defineComponent } from "vue";
import AuthService from "@/services/AuthService";
</script>

<template>
  <div class="v-header">
    <img class="v-logo" src="../../assets/logo.png" alt="logo" />
    <div class="v-login">
      <span
        class="v-color-primary-1"
        style="font-size: 18px; margin: 0 15px 0 0; align-self: center"
        >{{ login }}</span
      >
      <button class="v-login-btn btn" :disabled="getIsDisabled" @click="Logout">
        log out
      </button>
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
  methods: {
    Logout() {
      AuthService.logout()
        .catch((e) => {
          console.log(e);
        })
        .finally(() => {
          localStorage.removeItem("accessToken");
          localStorage.removeItem("login");
          this.$router.push({ name: "login" });
        });
    },
  },
});
</script>

<style scoped lang="scss">
@import "../../scss/colors.scss";

.v-header {
  width: 100%;

  display: flex;
  justify-content: space-between;

  border: 2px solid $neutral_color_2;
}

.v-login-btn {
  margin: 5px 0 5px 0;
  max-width: 250px;

  background-color: $neutral_color_3;
  color: $primary_color_2;
  border: 2px solid $primary_color_2;
  align-self: center;
}

.v-login-btn:hover {
  background-color: $primary_color_2;
  color: #f2f2f2;
}

.v-logo {
  padding: 5px;
  max-height: 60px;
}

.v-login {
  padding: 5px 15px 5px 5px;
  display: flex;
  justify-content: center;
}

.v-login-icon {
  fill: $primary_color_2;
}
</style>
