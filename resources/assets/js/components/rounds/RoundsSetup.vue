<template>
    <div class="panel panel-default">
        <div class="panel-body">


            <div class="row">
                <div class="col-xs-3">
                    <rounds-list :rounds="rounds" v-on:activate-round="activateRound">
                    </rounds-list>
                    <div class="text-center form-group">
                        <round-add :rounds="rounds"></round-add>
                    </div>
                </div>
                <div class="col-xs-9 form-horizontal">
                    <round-edit :rounds="rounds" :blocks="blocks" :activeRoundIndex="activeRoundIndex"></round-edit>
                </div>
            </div>

            list rounds -> with an edit button or an 'active' which causes another div to be visible<br>
            edit round<br>
            <strike>reorder round</strike>
        </div>
    </div>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                activeRoundIndex: null
            }
        },

        props: [
            'blocks',
            'pages',
            'rounds',
            'skills'
        ],

        methods: {
            // clone the activated round into the editRound object
            activateRound(index) {
                this.activeRoundIndex = index;
            }
        }
    }

</script>