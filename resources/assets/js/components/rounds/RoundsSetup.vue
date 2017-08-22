<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="text-center">
                <round-add :rounds="rounds"></round-add>
            </div>

            <div class="row">
                <div class="col-xs-3">
                    <rounds-list :rounds="rounds"></rounds-list>
                </div>
                <div class="col-xs-9">
                    <h4>Edit...</h4>

                </div>
            </div>

            list rounds -> with an edit button or an 'active' which causes another div to be visible<br>
            edit round<br>
            reorder round<br>
        </div>
    </div>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {

            }
        },

        props: [
            'pages',
            'rounds',
            'skills'
        ],

        methods: {


        }
    }

</script>