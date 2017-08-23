<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label for="title" class="col-xs-2 control-label">Title</label>
                <div class="col-xs-10">
                    <input id="title" class="form-control" type="text" v-model="editRound.title" placeholder="Title">
                </div>
            </div>

            <div class="form-group">
                <label for="format" class="col-xs-2 control-label">Format</label>
                <div class="col-xs-10">
                    <select id="format" class="form-control" v-model="editRound.format">
                        <option value="linear">Linear</option>
                        <option value="nonlinear">NonLinear</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="openDate" class="col-xs-2 control-label">Open Date (include time!)</label>
                <div class="col-xs-4">
                    <input id="openDate" class="form-control" type="datetime-local" v-model="editOpenDate">
                </div>

                <label for="closeDate" class="col-xs-2 contrlo-label">Close Date (include time!)</label>
                <div class="col-xs-4">
                    <input id="closeDate" class="form-control" type="datetime-local" v-model="editCloseDate">
                </div>
            </div>


            <div class="form-group">
                <label for="blockContent" class="col-xs-2 control-label">Content<br>(html is allowed - use wisely)</label>
                <div class="col-xs-10">
                    <textarea id="blockContent" class="form-control" rows="5" type="textarea" v-model="editBlockContent"></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-offset-2 col-xs-3">
                    <div class="checkbox">
                        <label>
                            <input id="keepVisible" type="checkbox" v-model="editRound.keep_visible">Keep visible?
                        </label>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="checkbox">
                        <label>
                            <input id="staffRate" type="checkbox" v-model="editRound.staff_rate">Staff rate?
                        </label>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="checkbox">
                        <label>
                            <input id="studentRate" type="checkbox" v-model="editRound.student_rate">Students rate?
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-offset-2 col-xs-10">
                    <button class="btn btn-lg" v-on:click="saveRound" :class="{ disabled: activeRoundIndex === null }">Save</button>
                    <button class="btn" v-on:click="deleteRound" :class="{ disabled: activeRoundIndex === null || rounds.length == 1 }">Delete</button>
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
                editBlockContent: '',
                editOpenDate: '',
                editCloseDate: '',
                editRound: {}
            }
        },

        props: [
            'activeRoundIndex',
            'blocks',
            'rounds'
        ],

        watch: {
            // when activeRoundIndex changes, populate the editRound object as required
            activeRoundIndex: function(index) {
                if (index !== null) {
                    this.editRound = JSON.parse(JSON.stringify(this.rounds[index]));

                    // get dates in correct format for HTML5 form
                    this.editOpenDate = this.editRound.open_date ? this.editRound.open_date.replace(" ", "T") : '';
                    this.editCloseDate = this.editRound.close_date ? this.editRound.close_date.replace(" ", "T") : '';

                    // get the block content so it can be edited
                    let blockId = this.editRound.block_id;
                    this.editBlockContent = blockId ? JSON.parse(JSON.stringify(this.blocks[blockId].content)) : '';
                } else {
                    this.resetEditRound();
                }
            }
        },

        methods: {
            // send a delete request to the server and if it works, update the local variable
            deleteRound() {
                if (this.rounds.length > 1 && confirm("Are you sure?")) {
                    axios.delete('rounds/' + this.editRound.id)
                    .then(response => {
                        if (response.status == 204) {
                            this.rounds.splice(this.activeRoundIndex, 1);
                            this.$emit('renumber-rounds');
                            this.resetEditRound();
                        }
                    });
                }
            },

            // clear all of the variables used to store the edit state of a round
            resetEditRound() {
                this.editBlockContent = '';
                this.editOpenDate = '';
                this.editCloseDate = '';
                this.editRound = {};
            },

            // post the updated round stuff to the server and update the local rounds / blocks objects
            saveRound() {
                // put the dates back in the right format
                this.editRound.open_date = this.editOpenDate == '' ? null : this.editOpenDate.replace("T", " ");
                this.editRound.close_date = this.editCloseDate == '' ? null : this.editCloseDate.replace("T", " ");

                axios.put('rounds/' + this.editRound.id, {
                    round: this.editRound,
                    blockContent: this.editBlockContent
                }).then(response => {
                    if (response.status == 200) {
                        // update the local objects to match what we've stored in the database
                        // update all of the round's properties
                        for (var property in response.data) {
                            this.rounds[this.activeRoundIndex][property] = response.data[property];
                        }

                        // if the round responded with includes a block we need to update the local block object
                        if (typeof response.data.block !== 'undefined') {
                            // create a local block with the saem id if needed
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