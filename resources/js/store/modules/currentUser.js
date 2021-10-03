import axios from "axios";

const state = {
    user:{},
    abilities: {}
};

const getters = {};
const actions = {
    getUser({commit}){
        axios
        .get("/api/user/current")
        .then(response => {
            commit('setUser', response.data);
        });
    },
    getAbilities({commit}){
        axios
        .get("/api/abilities")
        .then(response => {
            commit('setAbilities', response.data.data)
        });
    },
    loginUser({}, user) {
        axios
        .post("/api/login",
        {
            email: user.email,
            password: user.password
        })
        .then( response => {
            if(response.data.access_token) {
                
                // save token
                localStorage.setItem("blog_token", response.data.access_token)
                
                // redirect tpo SPA
                window.location.replace("/app")
            }
        })
    },
    logoutUser(){
        // remove token from browser local storage
        localStorage.removeItem("blog_token");
        //console.log("removed")

        // change route to login
        window.location.replace("/login")
    }
};
const mutations = {
    setUser(state, data){
        state.user = data;
    },
    setAbilities(state, data){
        state.abilities = data;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}