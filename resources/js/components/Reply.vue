<script>
    export default {
        props: ['attributes'],
        data() {
            return {
                editing: false,
                body: this.attributes.body,
                isBest: false
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
            },

            markBestReply() {
                this.isBest = true;

                axios.post('/replies/' + this.data.id + '/best');
            }
        }
    }
</script>