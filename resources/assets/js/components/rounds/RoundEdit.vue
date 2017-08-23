<template>
    <div>
        <h4>Edit...</h4>
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
                <button class="btn btn-lg pull-right" v-on:click="saveRound">Save</button>
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
                    this.editBlockContent = '';
                    this.editOpenDate = '';
                    this.editCloseDate = '';
                    this.editRound = {};
                }
            }
        },

        methods: {
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
                        for (var property in response.data) {
                            this.rounds[this.activeRoundIndex][property] = response.data[property];
                            if (typeof this.blocks[response.data.block_id] === 'undefined') {
                                this.blocks[response.data.block_id] = {
                                    id: response.data.block_id
                                };
                            }
                            this.blocks[response.data.block_id].content = this.editBlockContent;
                        }
                    }
                });
            }
        }
    }

</script>