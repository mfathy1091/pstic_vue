import Vue from "vue"
import Vuex from "vuex"

import currentUser from "./modules/currentUser";
import main from "./modules/main"

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        currentUser,
        main,
    }
})