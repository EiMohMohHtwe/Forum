<script>
export default {
    data() {
        return {
            body: '',
            endpoint: ''
        };
    },

    methods: {
        addReply() {
            axios.post(this.endpoint, { body: this.body })
             .then(({data}) => {
                  this.body = '';

                flash('Your reply has been posted.');

                this.$emit('created', data);
             });
        }
    }
    
}
</script>