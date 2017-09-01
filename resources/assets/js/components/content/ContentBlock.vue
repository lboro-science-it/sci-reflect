<template>
    <div class="form-group">
        <div class="col-xs-3">
            <h4>
                <span class="glyphicon glyphicon-move"></span>
                <span class="pull-right">Block</span>
            </h4>
            <button class="btn btn-lg" v-show="!edit" v-on:click="edit = true">Edit</button>
            <button class="btn btn-lg" v-show="edit" v-on:click="save">Save</button>
            <button class="btn btn-lg" v-show="edit" v-on:click="cancelEdit">Cancel</button>
            <button class="btn btn-lg" v-show="edit" v-on:click="unrelateBlock">Delete</button>
        </div>
        <div class="col-xs-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <!-- Preview of content block as rendered HTML -->
                    <div v-show="!edit" v-html="editContent"></div>

                    <!-- Content block HTML in a textarea -->
                    <div v-show="edit">
                        <textarea v-model="editContent" style="width: 100%;" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import 'axios';

    export default {
        data() {
            return {
                edit: false,
                editContent: JSON.parse(JSON.stringify(this.item.content))
            }
        },

        props: [ 'item' ],

        methods: {
            // set edit mode to false and re-clone the item to get rid of changes
            cancelEdit() {
                this.refreshEditContent();
                this.edit = false;
            },

            refreshEditContent() {
                this.editContent = JSON.parse(JSON.stringify(this.item.content));
            },

            // emit a save event with the new item content... or just do it here
            save() {
                this.item.content = this.editContent;
                this.refreshEditContent();
                this.edit = false;
            },

            // emit an event that the block should be unrelated from the page
            unrelateBlock() {
                if (confirm("Are you sure?")) {
                    this.$emit('unrelate-item', this.item);
                }
            }
        }
    }

</script>