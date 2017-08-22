<template>
    <div>
        <h4>Rounds 
            <a href="#"
               class="pull-right"
               v-on:click.prevent="orderRounds = true"
               v-show="!orderRounds"
            >
                Re-order
            </a>
            <a href="#"
               class="pull-right"
               v-on:click.prevent="saveRoundOrder"
               v-show="orderRounds"
            >
                {{ saveCaption }}
            </a>
        </h4>
        <div class="list-group">
            <draggable :list="rounds" class="dragArea"
                       v-on:end="roundDropped"
                       :options="{ handle: '.glyphicon' }">
                <div class="list-group-item" v-for="round in rounds">
                    <span class="glyphicon glyphicon-move"
                          v-show="orderRounds"></span>
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
                orderRounds: false,
                saveCaption: 'Save'
            }
        },

        props: [
            'rounds'
        ],

        methods: {
            // update the round numbers after sorting them
            roundDropped() {
                let roundsLength = this.rounds.length;
                for (let i = 0; i < roundsLength; i ++) {
                    this.rounds[i].round_number = i + 1;
                }
            },

            // send the new round_id => round_numbers to the server
            saveRoundOrder() {
                this.saveCaption = 'Saving...';
                let self = this;
                
                let roundsLength = this.rounds.length;
                let newRounds = {};
                for (let i = 0; i < roundsLength; i++) {
                    let round = this.rounds[i];
                    newRounds[round.id] = round.round_number;
                }

                // generate an array of round_ids => round_numbers
                axios.put('rounds/order', {
                    rounds: newRounds
                }).then(response => {
                    if (response.status == 200) {
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