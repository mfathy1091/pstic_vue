import axios from "axios";

const state = {
    user:{},
    abilities: {},
    showLoading: false,
};

const getters = {
    isLoading: (state) => () => state.showLoading
};
const actions = {
    async getUser({commit}){
        try{
            const response = await axios.get("/api/user/current");
            commit('setUser', response.data);
        }catch (error){
            console.error(error);
        }
    },
    async getAbilities({commit}){
        try{
            const response = await axios.get("/api/abilities");
            commit('setAbilities', response.data.data)
        }catch(error){
            console.error(error);
        }
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
    },
    setShowLoading(state, data){
        state.showLoading = data;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}