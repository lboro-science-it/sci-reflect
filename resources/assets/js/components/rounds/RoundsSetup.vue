<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-3">

                    <!-- Stuff only visible when editView == 'round' i.e. editing rounds general settings -->
                    <transition name="fade" mode="out-in">
                        <div v-show="editView == 'round'">

                            <!-- List of rounds, select active round, add rounds, change order -->
                            <rounds-list :rounds="rounds"
                                         v-on:activate-round="activateRound"
                                         v-on:renumber-rounds="renumberRounds">
                            </rounds-list>

                            <!-- Edit pages toggle button, changes editView -->
                            <div class="text-center form-group">
                                <button class="btn btn-lg" 
                                        :class="{ disabled: activeRoundIndex == null }"
                                        v-on:click="editView = 'pages'">
                                    Edit pages ({{ totalPages }})
                                </button>
                            </div>

                        </div>
                    </transition>

                    <!-- Stuff only visible when editing round's pages -->
                    <transition name="fade" mode="out-in">
                        <div v-show="editView == 'pages'">
                            <div class="text-center form-group">
                                <button class="btn btn-lg"
                                         v-on:click="editView = 'round'">
                                    Back to Rounds list
                                </button>
                            </div>

                            <page-list :round="rounds[activeRoundIndex]"
                                       :blocks="blocks"
                                       :pages="pages"
                                       :skills="skills"
                                       v-on:activate-page="activatePage">
                            </page-list>
                        </div>
                    </transition>

                </div>

                <div class="col-xs-9 form-horizontal">
                    <transition name="fade" mode="out-in">
                        <div v-show="editView == 'round'">
                            <round-edit :rounds="rounds" 
                                        :blocks="blocks" 
                                        :index="activeRoundIndex"
                                        v-on:renumber-rounds="renumberRounds">
                            </round-edit>
                        </div>
                    </transition>

                    <transition name="fade" mode="out-in">
                        <div v-show="editView == 'pages'">
                            <!-- todo: page edit view, where we can...
                                 * edit the page title
                                 * add skills
                                 * add blocks
                                 * delete the page (if it's not the only page)
                             -->
                        </div>
                    </transition>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                activePageIndex: null,
                activeRoundIndex: null,
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
            // calculate the total pages within the current active round
            totalPages() {
                if (this.activeRoundIndex !== null && typeof this.rounds[this.activeRoundIndex].page_pivots !== 'undefined') {
                    return this.rounds[this.activeRoundIndex].page_pivots.length;
                } else {
                    return 0;
                }
            }
        },

        methods: {
            // set the active page
            activatePage(index) {
                this.activePageIndex = index;
            },

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