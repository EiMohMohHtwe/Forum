<script>

import 'jquery.caret';
import 'at.js';

export default {
    data() {
        return {
            body: '',
            endpoint: ''
        };
    },

    mounted() {
        $('#body').atwho({
            at: "@",
            delay: 750,
            callbacks: {
                remoteFilter: function(query, callback) {
                    //console.log('called');
                   $.getJSON("/api/users", {name: query}, function(usernames) {
                       callback(usernames)
                    });
                }
            }
        });
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