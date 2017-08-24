<template>
    <div>
        <h4 v-show="round">Edit {{ title }}'s pages</h4>

        <!-- Selectable list of pages -->
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

        <!-- Reordering buttons -->
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

        <!-- Button for adding page -->
        <div class="text-center form-group">
            <page-add :pages="pages" :round="round"
                      v-on:add-page-pivots="addPagePivots"></page-add>
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
                    let pageId = (index !== null) ? this.editPages[index].id : null;
                    this.$emit('activate-page', pageId);
                }
            },

            // Adds new pivots to this.round (i.e. if we have added a page with relationship)
            addPagePivots(pagePivots) {
                this.round.page_pivots.push(pagePivots);
                this.updateEditPages();
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
            savePageOrder() {
                let newPages = {};
                let pagesLength = this.editPages.length;
                for (let i = 0; i < pagesLength; i++) {
                    let page = this.editPages[i];
                    page.page_number = i + 1;
                    // set page number to match array order, store in assoc array of page_id => page_number
                    newPages[page.id] = page.page_number;
                }

                axios.put('rounds/' + this.round.id + '/pages/numbers', {
                    pages: newPages
                }).then(response => {
                    if (response.status == 200) {
                        // response.data contains the updated page_pivots for the round
                        this.round.page_pivots = response.data;
                        this.updateEditPages();
                        this.orderPages = false;
                    }
                });
            },

            // creates an object of pages based on the round's page_pivots + global pages object
            updateEditPages() {
                if (this.round && this.round.page_pivots) {
                    this.editPages = JSON.parse(JSON.stringify(this.round.page_pivots));

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

        watch: {
            // update the temporary editPages array when round changes
            round() {
                this.updateEditPages();
            }
        },

        computed: {
            title() {
                return (this.round) ? this.round.title : '<No Round Set>';
            }
        }
    }

</script>