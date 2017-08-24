<template>
    <div>
        <h4 v-show="round">Edit {{ title }}'s pages</h4>

        <div class="list-group">
            <draggable :list="editPages" class="dragArea" :options="{ handle: '.glyphicon' }">
                <div role="button" class="list-group-item"
                     :class="{ active: index == activeIndex }"
                     v-for="(page, index) in editPages"
                     v-on:click="activatePage(index)">

                    <span class="glyphicon glyphicon-move" v-show="orderPages"></span>
                    {{ page.page_number }}: {{ page.title }}

                </div>
            </draggable>
        </div>

        <div class="text-center form-group">
            <button class="btn btn-lg" v-on:click="reorder" v-show="!orderPages">
                Re-order
            </button>

            <button class="btn btn-lg" v-on:click="savePageOrder" v-show="orderPages">
                {{ saveCaption }}
            </button>
            
            <button class="btn btn-lg" v-on:click="cancelPageOrder" v-show="orderPages">
                Cancel
            </button>
        </div>

    </div>
</template>

<script>

    export default {
        data() {
            return {
                activeIndex: null,
                editPages: [],
                orderPages: false,
                saveCaption: 'Save'
            }
        },

        props: [
            'blocks',
            'pages',
            'round',
            'skills'
        ],

        methods: {
            activatePage(index) {
                if (!this.orderPages) {
                    this.activeIndex = index;
                    this.$emit('activate-page', index);
                }
            },

            // returns rounds to order based on round_number i.e. cancels reordering
            cancelPageOrder() {
                this.editPages.sort(function(a, b) {
                    return a.page_number - b.page_number;
                });
                this.orderPages = false;
            },

            // turns reordering mode on - makes it impossible to edit the 'active' round
            reorder() {
                this.activatePage(null);
                this.orderPages = true;
            },

            savePageOrder(index) {
                console.log('we would save page order here');
            }

        },

        watch: {
            // whenever round changes, update the editingPages array so pages can be
            // edited independently of the persisted status of them, and undone if needed
            round() {
                if (this.round) {
                    // clone pivots (page_id and page_number) into editPages
                    this.editPages = JSON.parse(JSON.stringify(this.round.page_pivots));

                    // copy the page objects into the temporary editPages object
                    let pagesLength = this.editPages.length;
                    for (let i = 0; i < pagesLength; i++) {
                        let pageNumber = this.editPages[i].page_number;
                        let pageObject = this.pages[this.editPages[i].page_id];
                        this.editPages[i] = JSON.parse(JSON.stringify(pageObject));
                        this.editPages[i].page_number = pageNumber;
                    }

                } else {
                    this.editPages = [];
                }
            }
        },

        computed: {
            title() {
                if (this.round) {
                    return this.round.title;
                }

                return '<No Round Set>';
            }
        }
    }

</script>