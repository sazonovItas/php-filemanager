import { createRouter, createWebHistory } from "vue-router";

import AboutView from "@/views/AboutView.vue";
import LoginView from "@/views/LoginView.vue";
import DriveView from "@/views/DriveView.vue";
import FilePreview from "@/views/FilePreview.vue";
import NotFoundView from "@/views/NotFoundView.vue";

const routes = [
  {
    path: "/",
    name: "about",
    component: AboutView,
  },
  {
    path: "/login",
    name: "login",
    component: LoginView,
  },
  {
    path: "/drive",
    name: "drive",
    component: DriveView,
    props: true,
  },
  {
    path: "/drive/file",
    name: "file",
    component: FilePreview,
    props: true,
  },
  {
    path: "/:path(.*)*",
    name: "not_found",
    component: NotFoundView,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes: routes,
});

export default router;
