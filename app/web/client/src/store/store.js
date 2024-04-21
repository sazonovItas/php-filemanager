import { createStore } from "vuex";

const store = createStore({
  state() {
    return {
      drivePath: "",
      filePath: "",
    };
  },
});

export default store;
