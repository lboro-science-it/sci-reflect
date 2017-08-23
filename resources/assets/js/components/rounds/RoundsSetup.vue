<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-3">
                    <rounds-list :rounds="rounds" v-on:activate-round="activateRound"></rounds-list>

                    <div class="text-center form-group">
                        <round-add :rounds="rounds"></round-add>
                    </div>
                </div>
                <div class="col-xs-9 form-horizontal">
                    <round-edit :rounds="rounds" :blocks="blocks" :activeRoundIndex="activeRoundIndex"></round-edit>
                </div>
            </div>

            todo: add edit button when round is active, otherwise list its pages.
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
            // deal with the rounds list emitted event
            activateRound(index) {
                this.activeRoundIndex = index;
            }
        }
    }

</script>