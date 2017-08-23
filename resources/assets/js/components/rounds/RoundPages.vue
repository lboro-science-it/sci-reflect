<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <h4 v-show="this.round">Edit {{ title }}'s pages</h4>

            <div class="list-group">
                <draggable :list="editPages" class="dragArea" :options="{ handle: '.glyphicon' }">
                    <round-page v-for="(page, index) in editPages"
                                :edit-page="page"
                                :original-page="pages[page.id]"
                                :index="index"
                                :key="page.id"
                                :can-delete="editPages.length > 1"></round-page>
                </draggable>
            </div>

            <button v-show="this.round" v-on:click="savePageOrder">
                Save Page Order
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                title: '',
                editPages: [],
                editing: []
            }
        },

        props: [
            'blocks',
            'pages',
            'round',
            'skills'
        ],

        methods: {

            savePageOrder() {
                console.log('here we would save the page order');
            },

        },

        // fill dummy fields whenever round is updated
        watch: {
            round () {
                console.log("round has changed, round is ");
                console.log(this.round);
                if (this.round) {
                    this.title = this.round.title
                    // clones the round's page pivots into a temporary editable object
                    this.editPages = JSON.parse(JSON.stringify(this.round.page_pivots));

                    // copy the actual pages into the temporary editPages object
                    let pagesLength = this.editPages.length;
                    for (let i = 0; i < pagesLength; i++) {
                        let pageNumber = this.editPages[i].page_number;
                        let pageId = this.editPages[i].page_id;
                        this.editPages[i] = JSON.parse(JSON.stringify(this.pages[pageId]));
                        this.editPages[i].page_number = pageNumber;
                    }
                } else {
                    this.title = '';
                    this.editPages = [];
                    this.editing = [];
                }
            }
        }
    }
</script>