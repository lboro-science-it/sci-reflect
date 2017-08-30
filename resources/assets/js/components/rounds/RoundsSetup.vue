<template>
    <div>
        <!-- Round editing view -->
        <transition name="fade" mode="out-in">
            <div class="panel panel-default" v-show="editView == 'round'">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <!-- Rounds list -->
                            <sortable-list :items="rounds"
                                           number-prop="round_number"
                                           title-prop="title"
                                           title="Rounds"
                                           add-caption="Add Round"
                                           v-on:activate-item="activateRound"
                                           v-on:add-item="addRound"
                                           v-on:reorder-list="reorderRounds">
                            </sortable-list>

                            <!-- Edit pages toggle button -->
                            <div class="text-center form-group">
                                <button class="btn btn-lg" 
                                        :class="{ disabled: !canEditPages }"
                                        v-on:click="editPages">
                                    Edit pages ({{ activeRound.page_pivots.length }})
                                </button>
                            </div>
                        </div>

                        <!-- Round edit panel -->
                        <div class="col-xs-9 form-horizontal">
                            <round-edit :round="activeRound"
                                        :block="blocks[activeRound.block_id]"
                                        :can-save="activeRound.id !== null"
                                        :can-delete="activeRound.id !== null && rounds.length > 1"
                                        v-on:update-round="updateRound"
                                        v-on:delete-round="deleteRound">
                            </round-edit>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Page editing view -->
        <transition name="fade" mode="out-in">
            <div v-show="editView == 'pages'">
                <div class="text-center form-group">
                    <button class="btn btn-lg" v-on:click="editView = 'round'">
                        Back to Rounds list
                    </button>
                </div>

                <pages-setup :round="activeRound"
                             :pages="activeRoundPages"
                             :blocks="blocks"
                             :skills="skills">
                </pages-setup>

            </div>
        </transition>
    </div>
</template>

<script>
    import 'axios';

    const defaultRound = {
        id: null,
        title: '',
        block_id: null,
        page_pivots: []
    };

    export default {
        data () {
            return {
                activeRound: defaultRound,
                activeRoundPages: [],
                editView: 'round'
            }
        },

        props: [
            'blocks',
            'pages',
            'rounds',
            'skills'
        ],

        computed: {
            canEditPages() {
                return this.activeRound.id !== null;
            }
        },

        watch: {
            // update activeRoundPages whenever activeRound is changed
            activeRound(activeRound) {
                this.activeRoundPages = [];
                let pagePivotsLength = activeRound.page_pivots.length;
                for (let i = 0; i < pagePivotsLength; i++) {
                    let pagePivot = activeRound.page_pivots[i];
                    this.activeRoundPages[i] = JSON.parse(JSON.stringify(this.pages[pagePivot.page_id]));
                    this.activeRoundPages[i].page_number = pagePivot.page_number;
                }
            }
        },

        methods: {
            // sets activeRound to either an actual round or a placeholder
            activateRound(round) {
                this.activeRound = (round) ? round : defaultRound;
            },

            // post the roundName to the server, update the local rounds array
            addRound(roundName) {
                axios.post('rounds', {
                    title: roundName
                }).then(response => {
                    if (response.status == 200) {
                        this.rounds.push(response.data);
                    }
                });
            },

            // deletes the round, updates the local rounds array
            deleteRound(round) {
                if (this.rounds.length > 1 && confirm("Are you sure?")) {
                    axios.delete('rounds/' + round.id)
                    .then(response => {
                        if (response.status == 204) {
                            let roundsLength = this.rounds.length;
                            for (let i = 0; i < roundsLength; i++) {
                                let currentRound = this.rounds[i];
                                if (currentRound.id == round.id) {
                                    this.rounds.splice(i, 1);
                                    break;
                                }
                            }
                            this.renumberRounds();      // makes sure local round numbers are ok
                            this.activateRound(null);   // set nothing to activeRound
                        }
                    });
                }
            },

            // set editView to pages
            editPages() {
                if (this.canEditPages) {
                    this.editView = 'pages';
                }
            },

            // updates rounds round_number properties to match the array order
            renumberRounds() {
                let roundsLength = this.rounds.length;
                for (let i = 0; i < roundsLength; i++) {
                    let round = this.rounds[i];
                    round.round_number = i + 1;
                }
            },

            // send an array of round_id => round_number to server
            reorderRounds() {
                let roundNumbers = {};
                let roundsLength = this.rounds.length;
                for (let i = 0; i < roundsLength; i++) {
                    let round = this.rounds[i];
                    roundNumbers[round.id] = round.round_number;
                }

                axios.put('rounds/order', {
                    rounds: roundNumbers
                });
            },

            // save round to server
            updateRound(round, blockContent) {
                let activeRound = this.activeRound;

                axios.put('rounds/' + round.id, {
                    round: round,
                    blockContent: blockContent
                }).then(response => {
                    // if response was ok, update the local round object to match the database response
                    if (response.status == 200) {
                        for (var property in response.data) {
                            activeRound[property] = response.data[property];
                        }

                        // if response includes a block, store that too, creating the block locally if needed
                        if (typeof response.data.block !== 'undefined') {
                            if (typeof this.blocks[response.data.block_id] === 'undefined') {
                                this.blocks[response.data.block_id] = {
                                    id: response.data.block_id
                                };
                            }
                            // update the local block content to match the server response
                            this.blocks[response.data.block_id].content = response.data.block.content;
                        }
                    }
                });
            }
        }
    }

</script>