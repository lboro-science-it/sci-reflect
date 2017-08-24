<template>
    <div class="form-inline">
        <button class="btn btn-lg" v-on:click="toggleAddRound" v-show="!addRound">
            Add round
        </button>

        <div class="form-group" v-show="addRound">
            <input id="addRoundInput" type="text" class="form-control input-lg" placeholder="New round name..."
                   v-model="addRoundName"
                   v-on:keyup.enter="storeRound(addRoundName)">
        </div>

        <button class="btn btn-lg" v-on:click="toggleAddRound" v-show="addRound">
            Cancel
        </button>

        <button class="btn btn-lg" v-show="addRound" v-on:click="storeRound(addRoundName)">
            {{ saveCaption }}
        </button>

    </div>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                addRound: false,
                addRoundName: '',
                saveCaption: 'Save'
            }
        },

        props: [
            'rounds'
        ],

        methods: {
            // posts the round name to the server to create a new round
            storeRound(round) {
                let self = this;
                this.saveCaption = 'Saving...';

                axios.post('rounds', {
                    title: this.addRoundName
                }).then(response => {
                    // put the added round at the end of the rounds array
                    if (response.status == 200) {
                        this.saveCaption = 'Saved!';
                        this.rounds.push(response.data);
                    } else {
                        this.saveCaption = 'Failed!';
                    }

                    this.addRoundName = '';
                    setTimeout(function() {
                        self.saveCaption = 'Save';
                        self.addRound = false;
                    }, 500);
                });
            },

            // toggles whether the add round form is being shown
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