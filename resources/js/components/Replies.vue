<template>

<new-reply></new-reply>
    
</template>

<script>

import NewReply from './NewReply.vue';

export default {
    props: ['data'],

    components: { NewReply },

    data() {
        return {
            items: this.data
        };
    },
    methods: {
        remove(index) {
            this.items.splice(index, 1);

            this.$emit('removed');

            flash('Reply was deleted!');
        }
    }
}
</script>