<template>
    <div>
        <h1>The master component</h1>
        <h2>current counter state is: {{ counter}}</h2>
        <div>
            <comA />
            <comB />
            <comC />
        </div>
        <button @click="changeCounter">Change state state of counter</button>
    </div>
</template>

<script>
import comA from './comA'
import comB from './comB'
import comC from './comC'
import {mapActions, mapGetters} from 'vuex'

export default {
    data() {
        return {
            
        }
    },
    methods: {
        ...mapActions({
            'changeCounterAction' : 'changeCounter'
        })
    },
    computed: {
        ...mapGetters({
            'counter': 'getCounter'
        })
    },
    created(){
        console
    },
    methods: {
        changeCounter(){
            this.$store.dispatch('changeCounterAction', 1)
            // this.changeCounter
            // this.$store.commit('changeTheCounter', 1)
        }
    },
    components: {
        comA,
        comB,
        comC,
    },
}
</script>