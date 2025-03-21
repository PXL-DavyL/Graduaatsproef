import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";
import axios from 'axios'
import Vue3Toasity from "vue3-toastify";
import "vue3-toastify/dist/index.css";

axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true
axios.defaults.headers.common['Accept'] = 'application/json';

const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(Vue3Toasity, {
    autoClose: 7500,
    clearOnUrlChange: false,
});

app.mount("#app");
