import { createApp } from "vue";
import "./styles.scss";
import "./style.css";

import App from "./App.vue";
import router from "./router/router";
import store from "./store/store";

const app = createApp(App);
app.use(store);
app.use(router);
app.mount("#app");
