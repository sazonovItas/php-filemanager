import { createApp } from "vue";
import "./styles.scss";
import "./style.css";
import "bootstrap/dist/js/bootstrap.bundle.js";

import App from "./App.vue";
import router from "./router/router";
import store from "./store/store";
import PrimeVue from "primevue/config";
import "primevue/resources/themes/aura-dark-green/theme.css";

const app = createApp(App);
app.use(store);
app.use(router);
app.use(PrimeVue);
app.mount("#app");
