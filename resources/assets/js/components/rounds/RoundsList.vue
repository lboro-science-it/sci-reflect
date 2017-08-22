<template>
    <div>
        <h4>
            Rounds 

            <button class="pull-right" v-on:click.prevent="orderRounds = true" v-show="!orderRounds">
                Re-order
            </button>

            <button class="pull-right" v-on:click.prevent="saveRoundOrder" v-show="orderRounds">
                {{ saveCaption }}
            </button>
            
            <button class="pull-right" v-on:click.prevent="cancelRoundOrder" v-show="orderRounds">
                Cancel
            </button>
        </h4>
        <div class="list-group">
            <draggable :list="editRounds" class="dragArea" :options="{ handle: '.glyphicon' }">

                <div v-for="(round, index) in editRounds"
                     class="list-group-item"
                     :class="{ active: index == activeIndex }"
                     role="button"
                     v-on:click="activateRound(index)">

                    <span class="glyphicon glyphicon-move" v-show="orderRounds"></span>
                    {{ round.round_number }}: {{ round.title }}
                </div>
            </draggable>
        </div>
    </div>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                activeIndex: null,
                editRounds: this.rounds,
                orderRounds: false,
                saveCaption: 'Save'
            }
        },

        props: [
            'rounds'
        ],

        methods: {
            activateRound(index) {
                if (!this.orderRounds) {
                    this.activeIndex = index;
                    this.$emit('activate-round', index);
                }
            },

            cancelRoundOrder() {
                this.editRounds.sort(function(a, b) {
                    return a.round_number - b.round_number;
                });
                this.orderRounds = false;
            },

            // send the new round_id => round_numbers to the server
            saveRoundOrder() {
                this.saveCaption = 'Saving...';
                let self = this;
                
                let roundsLength = this.editRounds.length;
                let newRounds = {};
                for (let i = 0; i < roundsLength; i++) {
                    let round = this.editRounds[i];
                    // update the round order
                    round.round_number = i + 1;
                    newRounds[round.id] = round.round_number;
                }

                // generate an array of round_ids => round_numbers
                axios.put('rounds/order', {
                    rounds: newRounds
                }).then(response => {
                    if (response.status == 200) {
                        this.rounds = this.editRounds;
                        this.saveCaption = 'Saved!';
                    } else {
                        this.saveCaption = 'Failed!';
                    }
                    setTimeout(function() {
                        self.orderRounds = false;
                        self.saveCaption = 'Save';
                    }, 2000);
                });

            }
        }
    }
</script>