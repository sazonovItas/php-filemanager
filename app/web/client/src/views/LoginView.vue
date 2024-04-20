<script setup>
import { defineComponent, ref } from "vue";
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

      fetch(import.meta.env.VITE_API_SIGN_UP_ENDPOINT, {
        method: "post",
        credentials: "include",
        body: JSON.stringify({
          login: this.login,
          password: this.password,
        }),
      })
        .then(async (response) => {
          if (response.ok) {
            this.SignIn();
          } else {
            const body = await response.json();
            this.registerMsg = body.message;
            this.isDisabled = true;
          }
        })
        .catch((e) => {
          this.registerMsg = e.message;
          this.isDisabled = true;
        });
    },
    SignIn() {
      this.loginMsg = this.validateSignIn();
      if (this.loginMsg !== "") {
        return;
      }

      this.isDisabled = false;

      fetch(import.meta.env.VITE_API_SIGN_IN_ENDPOINT, {
        method: "post",
        credentials: "include",
        body: JSON.stringify({
          login: this.login,
          password: this.password,
          remember_me: this.rememberMe ? "yes" : "no",
        }),
      })
        .then(async (response) => {
          const body = await response.json();
          if (response.ok) {
            localStorage.setItem("accessToken", body.accessToken);
            localStorage.setItem("login", body.login);

            this.$router.push({ name: "drive", query: { path: "" } });
          } else {
            this.loginMsg = body.message;
            this.isDisabled = true;
          }
        })
        .catch((e) => {
          this.loginMsg = e.message;
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
    if (localStorage.getItem("accessToken") && localStorage.getItem("login")) {
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

.container-login {
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
