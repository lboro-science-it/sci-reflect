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
    import 'axios';

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
            // activates a page based on its index
            activatePage(index) {
                if (!this.orderPages) {
                    this.activeIndex = index;
                    // get the page's id and emit that event so it can be edited
                    if (index !== null) {
                        this.$emit('activate-page', this.editPages[index].id);
                    } else {
                        this.$emit('activate-page', null);
                    }
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

            // update the round's pivots to reflect new order of pages
            savePageOrder(index) {
                let newPages = {};

                // update editPages' page numbers to match the array index order
                // populate newPages object with page_id => page_number
                let pagesLength = this.editPages.length;
                for (let i = 0; i < pagesLength; i++) {
                    let page = this.editPages[i];
                    page.page_number = i + 1;

                    newPages[page.id] = page.page_number;
                }

                axios.put('rounds/' + this.round.id + '/pages/numbers', {
                    pages: newPages
                }).then(response => {
                    console.log(response.data);
                });
                // saving page order = update the round's page_pivots both in memory and in the database
                // so we are gonna send a put request to the server on the round_id
                // containing page_ids and page_numbers
                // in the order of the current editPages array
                // and then we are gonna update the local round.page_pivots to match what comes back
                console.log('we would save page order here');
            }

        },

        watch: {
            // whenever round changes, update the editPages array so pages can be
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