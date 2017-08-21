<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>Manage rounds</h3>
                <p>
                    Add one or more rounds to your activity. For each round, students will reflect on the skills you specify. 
                    At the end, they'll be able to see how their skills have evolved.
                </p>
                <div class="form-inline">
                    <div class="form-group">
                        <input v-model="newRoundTitle"
                               type="text"
                               class="form-control input-lg"
                               placeholder="Add"
                               v-on:keyup.enter="addRound"
                        >
                    </div>
                    <button class="btn btn-lg btn-primary"
                            v-on:click.prevent="addRound">
                        {{ addRoundCaption }}
                    </button>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-4">
                        <div class="list-group" role="group">
                            <div class="list-group-item"
                                 role="button" 
                                 v-for="(round, index) in rounds"
                                 :class="{ active: index == activeIndex }"
                                 v-on:click.prevent="activeIndex = index"
                            >
                                {{ round.title }}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-8">
                        content about editing the round
                    </div>
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
                activeIndex: 0,
                addRoundCaption: 'Add round',
                newRoundTitle: ''
            }
        },

        props: [
            'rounds'
        ],

        methods: {
            addRound() {
                if (this.newRound !== '') {
                    let self = this;
                    this.addRoundCaption = 'Adding...';
                    axios.post('rounds', {
                        title: this.newRoundTitle
                    }).then(response => {
                        if (response.status == 200) {
                            this.addRoundCaption = 'Added!';
                            setTimeout(function() {
                                self.addRoundCaption = 'Add round';
                            }, 5000);
                            this.newRoundTitle = '';
                            this.$emit('add-round', response.data);
                        } else {
                            this.addRoundCaption = 'Failed!';
                            setTimeout(function() {
                                self.addRoundCaption = 'Add round';
                            }, 5000);
                        }
                    });
                }
            }
        }
    }

</script>
