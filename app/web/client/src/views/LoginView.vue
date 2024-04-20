<script setup>
import { defineComponent, ref } from "vue";
</script>

<template>
  <div class="container-fluid container-login">
    <div class="container-fluid v-login-container">
      <p>register</p>
      <input class="v-input" type="text" placeholder="login" v-model="login" />
      <input
        class="v-input"
        type="password"
        placeholder="password"
        v-model="password"
      />
      <input
        class="v-input"
        type="password"
        placeholder="verify password"
        v-model="verifyPassword"
      />
      <button class="v-login-btn btn">sign up</button>
    </div>
    <div class="container-fluid v-login-container">
      <p>login</p>
      <input class="v-input" type="text" placeholder="login" v-model="login" />
      <input
        class="v-input"
        type="password"
        placeholder="password"
        v-model="password"
      />
      <button class="v-login-btn btn">sign in</button>
    </div>
  </div>
</template>

<script>
export default defineComponent({
  data() {
    const login = ref("");
    const password = ref("");
    const verifyPassword = ref("");

    return {
      login: login,
      password: password,
      verifyPassword: verifyPassword,
    };
  },
  methods: {},
  mounted() {},
});
</script>

<style lang="scss" scoped>
@import "../scss/colors.scss";

$input_focus_border_color: $primary_color_2;
$input_border_color: $neutral_color_1;
$login_btn_color: $primary_color_2;
$login_btn_onhover_color: $primary_color_4;

.container-login {
  max-width: 800px;
  text-align: center;

  display: flex;
  justify-content: space-between;
}

.v-login-container {
  display: flex;
  flex-direction: column;

  align-content: center;
}

.v-login-btn {
  margin: 5px 0 5px 0;
  max-width: 250px;

  background-color: $login_btn_color;
  align-self: center;
}

.v-login-btn:hover {
  background-color: $login_btn_onhover_color;
}

.v-input {
  margin: 5px 0 5px 0;
  border: 1.5px solid $input_border_color;
  border-radius: 5px;
  outline: none;

  align-self: center;
  max-width: 250px;
}

.v-input:focus {
  border: 3px solid $input_focus_border_color;
}
</style>
