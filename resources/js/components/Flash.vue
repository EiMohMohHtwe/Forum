<template>
   <div class="alert alert-success alert-flash" role="alert" v-show="show">
        <strong>Success!</strong> {{ body }}
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: this.message,
                show: false
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
