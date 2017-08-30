<template>
    <div>
        <h4>{{ title }}</h4>

        <div class="list-group">
            <draggable :list="items" class="dragArea" :options="{ handle: '.glyphicon' }">
                <div role="button" class="list-group-item"
                     :class="{ active: index == activeIndex }"
                     v-for="(item, index) in items"
                     v-on:click="activateItem(index)"
                     >
                    <span class="glyphicon glyphicon-move" v-show="orderItems"></span>
                    {{ item[numberProp] }}: {{ item[titleProp] }}
                </div>
            </draggable>
        </div>

        <div class="text-center form-group">
            <button class="btn btn-lg" v-on:click="reorder" v-show="!orderItems"
                    :class="{ disabled: !canReorder }">
                Re-order
            </button>

            <button class="btn btn-lg" v-on:click="saveItemsOrder" v-show="orderItems">
                Save
            </button>
            
            <button class="btn btn-lg" v-on:click="cancelItemsOrder" v-show="orderItems">
                Cancel
            </button>
        </div>

        <div class="text-center form-group">
            <button class="btn btn-lg" v-on:click="toggleAddItem" v-show="!addItem">
                {{ addCaption }}
            </button>

            <div v-show="addItem">
                <input id="addItemInput" type="text" class="form-control input-lg" :placeholder="addCaption"
                       v-model="addItemName"
                       v-on:keyup.enter="storeItem(addItemName)">

                <button class="btn btn-lg" v-on:click="toggleAddItem">
                    Cancel
                </button>

                <button class="btn btn-lg" v-on:click="storeItem(addItemName)">
                    Save
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                activeIndex: null,
                addItem: false,
                addItemName: '',
                orderItems: false
            }
        },

        props: [
            'addCaption',
            'items',
            'numberProp',
            'titleProp',
            'title'
        ],

        computed: {
            canReorder() {
                return this.items.length > 1;
            }
        },

        methods: {
            // set active index on click of item, emit event to other components
            activateItem(index) {
                if (!this.orderItems) {
                    this.activeIndex = index;
                    this.$emit('activate-item', this.items[index]);
                }
            },

            // returns items to order based on numberProp i.e. cancels reordering
            cancelItemsOrder() {
                let self = this;
                this.items.sort(function(a, b) {
                    return a[self.numberProp] - b[self.numberProp];
                });
                this.orderItems = false;
            },

            // turns reordering mode on - makes it impossible to edit the 'active' item
            reorder() {
                if (this.canReorder) {
                    this.activateItem(null);
                    this.orderItems = true;
                }
            },

            // send the new round_id => round_numbers to the server
            saveItemsOrder() {
                let self = this;
               
                let itemsLength = this.items.length;
                for (let i = 0; i < itemsLength; i++) {
                    let item = this.items[i];
                    item[this.numberProp] = i + 1;
                }

                this.orderItems = false;
                this.$emit('reorder-list');
            },

            storeItem(itemName) {
                this.$emit('add-item', itemName);
                this.addItemName = '';
                this.addItem = false;
            },

            toggleAddItem() {
                this.addItem = !this.addItem;
                if (this.addItem) {
                    Vue.nextTick(function() { 
                        document.getElementById('addItemInput').focus();
                    });
                }
            }
        }
    }
</script>
