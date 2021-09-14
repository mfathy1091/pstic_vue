import Vue from 'vue'
import Vuex from 'vuex'
// import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
    state : {
        counter: 1000,
        nationalities: [],
        abilities: [],
    },

    getters: {
        getCounter(state){
            return state.counter
        },
        getAbilities(state){
            return state.abilities
        },
        getCounterByHalf(state){
            return state.counter / 2
        },
        getNationalitiesgetter(state){
            return state.nationalities
        }
    },
    actions: {
        changeCounterAction({commit}, data){
            commit('changeTheCounter', data)
        },
        fetchAbilities({commit}){
			// this.$Progress.start();
			axios.get('/api/abilities')
            .then(({data}) => {
                this.abilities = data.data
                commit('setAbilities', data.data)
            });
			// this.$Progress.finish();
		},
        getNationalities({commit}){
			// this.$Progress.start();
			axios.get('/api/nationalities')
            .then(({data}) => {
                this.nationalities = data.data
                commit('setNationalities', data.data)
            });
			// this.$Progress.finish();
		},

    },

    mutations: {
        changeTheCounter(state, number){
            state.counter += number
        },
        setAbilities(state, abilities) {
            state.abilities = abilities
        },
        setNationalities(state, nationalities) {
            state.nationalities = nationalities
        }
    },

})