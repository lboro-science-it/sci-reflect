<template>
    <div class="form-group">
        <div class="col-xs-2">
            <h4>
                <span class="glyphicon glyphicon-move"></span>
                <span class="pull-right">Block</span>
            </h4>
            <button class="btn btn-lg" v-on:click="edit = !edit">Edit (todo)</button>
            <button class="btn btn-lg" v-on:click="unrelateBlock">Delete (todo)</button>
        </div>
        <div class="col-xs-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    <!-- Preview of content block as rendered HTML -->
                    <div v-show="!edit" v-html="item.content"></div>

                    <!-- Content block HTML in a textarea -->
                    <div v-show="edit">
                        <textarea v-model="item.content" style="width: 100%;" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                edit: false
            }
        },

        props: [ 'item' ],

        methods: {
            // emit an event that the block should be unrelated from the page
            unrelateBlock() {
                this.$emit('unrelate-item', this.item);
            }
        }
    }

</script>