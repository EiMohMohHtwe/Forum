<script>
    export default {
        props: ['attributes'],
        data() {
            return {
                editing: false,
                body: this.attributes.body
            };
        },
        mounted() {
            console.log(this.attributes)
        }, 
        methods: {
            update() {
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.body
                });
                this.editing = false;
                flash('Updated!');
            }
        }
    }
</script>