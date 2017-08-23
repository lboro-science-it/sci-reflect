<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-3">
                    <rounds-list :rounds="rounds"
                                 v-on:activate-round="activateRound"
                                 v-on:renumber-rounds="renumberRounds">
                    </rounds-list>

                    <div class="text-center form-group">
                        <round-add :rounds="rounds"></round-add>
                    </div>
                </div>
                <div class="col-xs-9 form-horizontal">
                    <ul class="nav nav-tabs nav-justified">

                        <li role="presentation" :class="{ active: activeTab == 'edit' }" v-on:click.prevent="activeTab = 'edit'">
                            <a href="#">Edit</a>
                        </li>

                        <li role="presentation" :class="{ active: activeTab == 'pages'}" v-on:click.prevent="activeTab = 'pages'">
                            <a href="#">Pages</a>
                        </li>

                    </ul>

                    <round-edit :rounds="rounds" 
                                :blocks="blocks" 
                                :activeRoundIndex="activeRoundIndex"
                                v-on:renumber-rounds="renumberRounds">
                    </round-edit>
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
                activeRoundIndex: null,
                activeTab: 'edit'
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
            },

            // change rounds' round_number to match the order of the array
            renumberRounds() {
                let roundsLength = this.rounds.length;
                for (let i = 0; i < roundsLength; i++) {
                    let round = this.rounds[i];
                    round.round_number = i + 1;
                }
            }
        }
    }

</script>