<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <h4>Edit round</h4>
            
            <!-- Round Title -->
            <div class="form-group">
                <label for="title" class="col-xs-2 control-label">Title</label>
                <div class="col-xs-10">
                    <input id="title" class="form-control" type="text" v-model="editRound.title" placeholder="Title">
                </div>
            </div>

            <!-- Format -->
            <div class="form-group">
                <label for="format" class="col-xs-2 control-label">Format</label>
                <div class="col-xs-10">
                    <select id="format" class="form-control" v-model="editRound.format">
                        <option value="linear">Linear</option>
                        <option value="nonlinear">NonLinear</option>
                    </select>
                </div>
            </div>

            <!-- Open and Close dates -->
            <div class="form-group">
                <label for="openDate" class="col-xs-2 control-label">Open Date (include time!)</label>
                <div class="col-xs-4">
                    <input id="openDate" class="form-control" type="datetime-local" v-model="editRound.open_date">
                </div>

                <label for="closeDate" class="col-xs-2 contrlo-label">Close Date (include time!)</label>
                <div class="col-xs-4">
                    <input id="closeDate" class="form-control" type="datetime-local" v-model="editRound.close_date">
                </div>
            </div>

            <!-- Content -->
            <div class="form-group">
                <label for="blockContent" class="col-xs-2 control-label">Content<br>(html is allowed - use wisely)</label>
                <div class="col-xs-10">
                    <textarea id="blockContent" class="form-control" rows="5" type="textarea" v-model="editBlockContent"></textarea>
                </div>
            </div>

            <!-- Visibility and Rating permissions -->
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

            <!-- Save / Delete buttons -->
            <div class="form-group">
                <div class="col-xs-offset-2 col-xs-10">
                    <button class="btn btn-lg" v-on:click="updateRound" :class="{ disabled: !canSave }">Save</button>
                    <button class="btn" v-on:click="deleteRound" :class="{ disabled: !canDelete }">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import 'axios';

    const defaultRound = {
        title: '',
        open_date: '',
        close_date: '',
        keep_visible: false,
        staff_rate: false,
        student_rate: false,
        format: 'linear'
    };

    export default {
        data () {
            return {
                editBlockContent: '',
                editRound: {}
            }
        },

        props: [
            'block',
            'canDelete',
            'canSave',
            'round'
        ],

        watch: {
            // clone block into an editable object when it changes
            block (block) {
                this.editBlockContent = (block) ? JSON.parse(JSON.stringify(block.content)) : '';
            },

            // clone round into an editable object when it changes
            round (round) {
                this.editRound = (round) ? JSON.parse(JSON.stringify(round)) : defaultRound;
                if (round) {
                    // format dates appropriately for HTML5 datetime-local input
                    this.editRound.open_date = this.editRound.open_date ? this.editRound.open_date.replace(" ", "T") : '';
                    this.editRound.close_date = this.editRound.close_date ? this.editRound.close_date.replace(" ", "T") : '';
                }
            }
        },

        methods: {
            // emit an event to delete the round
            deleteRound() {
                this.$emit('delete-round', this.editRound);
            },

            // emit an event containing the updated round & block content
            updateRound() {
                // put the dates back in the right format
                this.editRound.open_date = this.editRound.open_date == '' ? null : this.editRound.open_date.replace("T", " ");
                this.editRound.close_date = this.editRound.close_date == '' ? null : this.editRound.close_date.replace("T", " ");

                this.$emit('update-round', this.editRound, this.editBlockContent);
            }
        }
    }

</script>