<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-inline">
                        <button class="btn btn-lg"
                                v-on:click="toggleAddRound"
                                v-show="addRound == false">
                            Add round
                        </button>

                        <div class="form-group"
                             v-show="addRound == true">
                            <input id="addRoundInput"
                                   type="text"
                                   class="form-control input-lg"
                                   placeholder="New round name..."
                                   v-model="addRoundName"
                            >
                        </div>
                        <button class="btn btn-lg"
                                v-on:click="toggleAddRound"
                                v-show="addRound == true">
                            Cancel
                        </button>
                        <button class="btn btn-lg"
                                v-show="addRound == true">
                            Save
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-3">
                    <div class="list-group">
                        <draggable :list="rounds" class="dragArea"
                                   v-on:end="doneSort"
                                   :options="{ handle: '.glyphicon' }">
                            <div class="list-group-item" v-for="round in rounds"><span class="glyphicon glyphicon-move"></span> {{ round.title }}</div>
                        </draggable>
                    </div>
                </div>
                <div class="col-xs-9">
                    content here
                </div>
            </div>


            <ul>
                <li v-for="round in rounds">{{ round.title }} - {{ round.round_number }}</li>
            </ul>

            list rounds -> with an edit button or an 'active' which causes another div to be visible<br>
            edit round<br>
            reorder round<br>
        </div>
    </div>
</template>

<script>

    export default {
        data () {
            return {
                addRound: false,
                addRoundName: ''
            }
        },

        props: [
            'pages',
            'rounds',
            'skills'
        ],

        methods: {
            // update the round numbers after sorting them
            doneSort() {
                let roundsLength = this.rounds.length;
                for (let i = 0; i < roundsLength; i ++) {
                    this.rounds[i].round_number = i + 1;
                }
                console.log(this.rounds);
                console.log(sciReflect.rounds);
            },

            toggleAddRound() {
                this.addRound = !this.addRound;
                if (this.addRound) {
                    Vue.nextTick(function() { 
                        document.getElementById('addRoundInput').focus();
                    });
                }
            }
        }
    }

</script>