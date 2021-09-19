import Vue from 'vue'
import Vuex from 'vuex'
// import axios from 'axios'
import ability from './services/ability'

const updateAbilities = (store) => {
    ability.update(store.state.rules) // take rules from your state structure

    return store.subscribe((mutation) => {
        switch (mutation.type) {
        case 'login':
            ability.update(mutation.payload.rules)
            break
        case 'logout':
            ability.update([{ actions: 'read', subject: 'all' }]) // read only mode
            // or `ability.update([])` to remove all permissions
            break
        }
    })
}

Vue.use(Vuex)

export default new Vuex.Store({
    plugins: [
        updateAbilities
    ],
    state : {
        counter: 1000,
        nationalities: [],
        abilities: [],
        rules: [],
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