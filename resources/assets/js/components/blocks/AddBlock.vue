<template>
    <!-- Add / Create Blocks -->
    <div class="form-group">
        <div class="col-xs-offset-2 col-xs-4">
            <select v-model="addBlockId" class="form-control input-lg">
                <option value="null">Select block to add</option>
                <option v-for="block in blocks" :value="block.id">ID: {{ block.id }} ("{{ block.content.substring(0, 40) }}...")</option>
            </select>
        </div>
        <div class="col-xs-6">
            <button class="btn btn-lg"
                    :class="{ disabled: !canAdd }"
                    v-on:click="addBlock">
                Add (todo)
            </button>
            <button class="btn btn-lg">New (todo)</button>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                addBlockId: "null"
            }
        },

        computed: {
            canAdd() {
                return this.addBlockId != "null";
            }
        },

        props: [ 'blocks' ],

        methods: {
            // emit an event to add the block to whatever the active page is
            addBlock() {
                if (this.canAdd) {
                    this.$emit('add-block', this.addBlockId);
                    this.addBlockId = "null";
                }
            }
        }
    }

</script>