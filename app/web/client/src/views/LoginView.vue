<script setup>
import { defineComponent, ref } from "vue";
import AuthService from "@/services/AuthService";
</script>

<template>
  <div class="container-fluid container-login">
    <div class="container-fluid v-login-container">
      <p>register</p>
      <input class="v-input" type="text" placeholder="login" v-model="login" />
      <input class="v-input" type="password" placeholder="password" v-model="password" />
      <input class="v-input" type="password" placeholder="verify password" v-model="verifyPassword" />
      <p class="msg">{{ registerMsg }}</p>
      <button class="v-login-btn btn" :disabled="getIsDisabled" @click="SignUp">
        sign up
      </button>
    </div>
    <div class="container-fluid v-login-container">
      <p>login</p>
      <input class="v-input" type="text" placeholder="login" v-model="login" />
      <input class="v-input" type="password" placeholder="password" v-model="password" />
      <p class="msg">{{ loginMsg }}</p>
      <div class="check">
        <input class="form-check-input v-checkbox" type="checkbox" id="flexCheckDefault" v-model="rememberMe" />
        <label class="form-check-label" for="flexCheckDefault">
          remember me
        </label>
      </div>
      <button class="v-login-btn btn" :disabled="getIsDisabled" @click="SignIn">
        sign in
      </button>
    </div>
  </div>
</template>

<script>
export default defineComponent({
  data() {
    const login = ref("");
    const password = ref("");
    const verifyPassword = ref("");
    const rememberMe = ref(false);
    const registerMsg = ref("");
    const loginMsg = ref("");
    const isDisabled = ref(true);

    return {
      login: login,
      password: password,
      verifyPassword: verifyPassword,
      rememberMe: rememberMe,
      registerMsg: registerMsg,
      loginMsg: loginMsg,
      isDisabled: isDisabled,
    };
  },
  methods: {
    SignUp() {
      this.registerMsg = this.validateSignUp();
      if (this.registerMsg !== "") {
        return;
      }

      this.isDisabled = false;

      AuthService.signUp(this.login, this.password)
        .then((response) => {
          if (response.status >= 200 && response.status <= 299) {
            return this.SignIn(this.login, this.password);
          }
        })
        .catch((e) => {
          console.log(e.message);
          this.registerMsg = e.response.data.message ?? e.message;
          this.isDisabled = true;
        });
    },
    SignIn() {
      this.loginMsg = this.validateSignIn();
      if (this.loginMsg !== "") {
        return;
      }

      this.isDisabled = false;

      AuthService.signIn(this.login, this.password)
        .then((response) => {
          const body = response.data;
          if (response.status >= 200 && response.status <= 299) {
            localStorage.setItem("accessToken", body.accessToken);
            localStorage.setItem("login", body.login);
            this.$router.push({ name: "drive", query: { path: "" } });
          }
        })
        .catch((e) => {
          console.log(e.message);
          this.loginMsg = e.response.data.message ?? e.message;
          this.isDisabled = true;
        });
    },
    validateSignIn() {
      if (!/^([a-zA-Z0-9_-]){4,20}$/.test(this.login)) {
        return "invalid login format";
      }

      if (!/^([a-zA-Z0-9_-]){4,20}$/.test(this.password)) {
        return "invalid password format";
      }

      return "";
    },
    validateSignUp() {
      if (!/^([a-zA-Z0-9_-]){4,20}$/.test(this.login)) {
        return "invalid login format";
      }

      if (!/^([a-zA-Z0-9_-]){4,20}$/.test(this.password)) {
        return "invalid password format";
      }

      if (this.password !== this.verifyPassword) {
        return "password do not match";
      }

      return "";
    },
  },
  computed: {
    getIsDisabled() {
      return !this.isDisabled;
    },
  },
  mounted() {
    if (localStorage.getItem("accessToken") != undefined) {
      this.$router.push({ name: "drive", query: { path: "" } });
    }
  },
});
</script>

<style lang="scss" scoped>
@import "../scss/colors.scss";

$input_focus_border_color: $primary_color_2;
$input_border_color: $neutral_color_1;
$login_btn_color: $primary_color_2;
$login_btn_onhover_color: $primary_color_4;

p {
  margin: 40px 0 20px 0;
}

.container-login {
  text-align: center;
  max-width: 800px;

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

.msg {
  margin: 0 0 0 0;
  align-self: center;
  text-align: left;
  max-width: 250px;

  color: #ff0000;
}

.v-checkbox {
  margin: 5px 5px 5px 0;
  max-width: 250px;

  color: $login_btn_color;
  background-color: $login_btn_color;
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
