import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

import * as path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default defineConfig({
  build: {
    outDir: "./static",
  },
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "src"),
      "~bootstrap": path.resolve(__dirname, "node_modules/bootstrap"),
      "~bootstrap-vue": path.resolve(__dirname, "node_modules/bootstrap-vue"),
    },
  },
  plugins: [vue()],
});
