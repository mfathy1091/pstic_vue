<template>
    <div>
        <div>
            <input v-model="number" type="text" :placeholder="template">
        </div>
    </div>
</template>
<script>
export default {
    props: [
        'template'
    ],

    data() {
        return {
            number: '',
            format: '',
            regex: '^',
        }
    },
    mounted() {
        let x = 1;
        this.format = this.template.replace(/X+/g, () => '$' + x++)
        this.template.match(/X+/g).forEach((char, key) => {
            // console.log(char.length);
            // console.log(key);

            this.regex += '(\\d{' + char.length + '})?'
            console.log(this.regex);
        });
    },
    watch: {
        number(next, prev) {
            if (next.length > prev.length) {
                this.number = this.number.replace(/[^0-9]/g, '')
            
            .replace(new RegExp(this.regex, 'g'), this.format)
            .substr(0, this.template.length);
            }
        },

            // this.fileNumber = this.fileNumber.replace(/[^0-9]/g, '')
            // .replace(/^(\d{3})?(\d{2})?(\d{5})?/g, '$1-$2C$3');


    },
}
</script>