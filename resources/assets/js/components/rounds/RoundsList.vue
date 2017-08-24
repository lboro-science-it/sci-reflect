<template>
    <div>
        <h4>Rounds</h4>

        <div class="list-group">
            <draggable :list="rounds" class="dragArea" :options="{ handle: '.glyphicon' }">
                <div role="button" class="list-group-item"
                     :class="{ active: index == activeIndex }"
                     v-for="(round, index) in rounds"
                     v-on:click="activateRound(index)"
                     >
                    <span class="glyphicon glyphicon-move" v-show="orderRounds"></span>
                    {{ round.round_number }}: {{ round.title }}

                </div>
            </draggable>
        </div>

        <div class="text-center form-group">
            <button class="btn btn-lg" v-on:click="reorder" v-show="!orderRounds">
                Re-order
            </button>

            <button class="btn btn-lg" v-on:click="saveRoundOrder" v-show="orderRounds">
                {{ saveCaption }}
            </button>
            
            <button class="btn btn-lg" v-on:click="cancelRoundOrder" v-show="orderRounds">
                Cancel
            </button>
        </div>

        <div class="text-center form-group">
            <round-add :rounds="rounds"></round-add>
        </div>
    </div>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                activeIndex: null,          // index of the active round
                orderRounds: false,
                saveCaption: 'Save'
            }
        },

        props: [
            'rounds'
        ],

        methods: {
            // set active index on click of round, emit event to other components
            activateRound(index) {
                if (!this.orderRounds) {
                    this.activeIndex = index;
                    this.$emit('activate-round', index);
                }
            },

            // returns rounds to order based on round_number i.e. cancels reordering
            cancelRoundOrder() {
                this.rounds.sort(function(a, b) {
                    return a.round_number - b.round_number;
                });
                this.orderRounds = false;
            },

            // turns reordering mode on - makes it impossible to edit the 'active' round
            reorder() {
                this.activateRound(null);
                this.orderRounds = true;
            },

            // send the new round_id => round_numbers to the server
            saveRoundOrder() {
                this.saveCaption = 'Saving...';
                let self = this;
                
                // object to store new round_id => round_number details
                let newRounds = {};

                let roundsLength = this.rounds.length;
                for (let i = 0; i < roundsLength; i++) {
                    let round = this.rounds[i];
                    // update the round order
                    round.round_number = i + 1;
                    newRounds[round.id] = round.round_number;
                }

                // send the round_id => round_number array to server
                axios.put('rounds/order', {
                    rounds: newRounds
                }).then(response => {
                    if (response.status == 200) {
                        this.saveCaption = 'Saved!';
                        setTimeout(function() {
                            self.orderRounds = false;
                            self.saveCaption = 'Save';
                        }, 500);     
                    } else {
                        this.saveCaption = 'Failed!';
                        setTimeout(function() {
                            self.orderRounds = false;
                            self.saveCaption = 'Save';
                        }, 2000);                        
                    }
                });

            }
        }
    }
</script>