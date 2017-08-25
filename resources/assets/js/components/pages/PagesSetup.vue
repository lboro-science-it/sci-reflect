<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-3">
                    <!-- Pages list -->
                    <sortable-list :items="pages"
                                   number-prop="page_number"
                                   title-prop="title"
                                   :title="round.title + '\'s Pages'"
                                   add-caption="Add Page"
                                   v-on:activate-item="activatePage"
                                   v-on:add-item="addPage"
                                   v-on:reorder-list="reorderPages">
                    </sortable-list>
                </div>

                <!-- Page edit panel -->
                <div class="col-xs-9 form-horizontal">
                    <page-edit :page="activePage"
                               :blocks="activePageBlocks"
                               :skills="activePageSkills"
                               :can-save="activePage.id !== null"
                               :can-delete="activePage.id !== null && pages.length > 1"
                               v-on:update-page="updatePage"
                               v-on:delete-page="deletePage">
                    </page-edit>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import 'axios';

    const defaultPage = {
        id: null,
        title: '',
        block_pivots: [],
        skill_pivots: []
    };

    export default {
        data () {
            return {
                activePage: defaultPage,
                activePageBlocks: [],
                activePageSkills: []
            }
        },

        props: [
            'round',
            'pages',
            'blocks',
            'skills'
        ],

        watch: {
            // update activePageBlocks/Skills whenever activePage changes
            activePage(activePage) {
                // get the page's blocks from the global blocks object
                this.activePageBlocks = {};
                let blockPivotsLength = activePage.block_pivots.length;
                for (let i = 0; i < blockPivotsLength; i++) {
                    let blockPivot = activePage.block_pivots[i];
                    this.activePageBlocks[i] = JSON.parse(JSON.stringify(this.blocks[blockPivot.block_id]));
                    this.activePageBlocks[i].position = blockPivot.position;
                }

                // get the page's skills from the global skills object
                this.activePageSkills = {};
                let skillPivotsLength = activePage.skill_pivots.length;
                for (let i = 0; i < skillPivotsLength; i++) {
                    let skillPivot = activePage.skill_pivots[i];
                    this.activePageSkills[i] = JSON.parse(JSON.stringify(this.skills[skillPivot.skill_id]));
                    this.activePageSkills[i].position = skillPivot.position;
                }
            }
        },

        methods: {
            // sets activePage to either an actual page or a placeholder
            activatePage(page) {
                this.activePage = (page) ? page : defaultPage;
            },

            // post the pageName to the server, update the local pages array
            addPage(pageName) {
                axios.post('pages', {
                    page: {
                        title: pageName
                    },
                    roundId: this.round.id
                }).then(response => {
                    if (response.status == 200) {
                        // todo: create page_pivot on active round if specified
                        this.pages.push(response.data.page);
                        this.round.page_pivots.push(response.data.page_pivots);
                    }
                });
            },

            // deletes the page, updates the local pages array
            deletePage(page) {
                if (this.pages.length > 1 && confirm("Are you sure?")) {
                    axios.delete('pages/' + page.id)
                    .then(response => {
                        if (response.status == 204) {
                            let pagesLength = this.pages.length;
                            for (let i = 0; i < pagesLength; i++) {
                                let currentPage = this.pages[i];
                                if (currentPage.id == page.id) {
                                    this.pages.splice(i, 1);
                                    break;
                                }
                            }
                            this.renumberPages();      // makes sure local page numbers are ok
                            this.activatePage(null);   // set nothing to activePage
                        }
                    });
                }
            },

            // updates pages page_number properties to match the array order
            renumberPages() {
                let pagesLength = this.pages.length;
                for (let i = 0; i < pagesLength; i++) {
                    let page = this.pages[i];
                    page.page_number = i + 1;
                }
            },

            // send an array of page_id => page_number to server
            reorderPages() {
                let pageNumbers = {};
                let pagesLength = this.pages.length;
                for (let i = 0; i < pagesLength; i++) {
                    let page = this.pages[i];
                    pageNumbers[page.id] = page.page_number;
                }

                axios.put('rounds/' + this.round.id + '/pages/numbers', {
                    pages: pageNumbers
                }).then(response => {
                    if (response.status == 200) {
                        // response.data contains updated page_pivots for the round
                        this.round.page_pivots = response.data;
                    }
                });
            },

            // save page to server
            updatePage(page) {
                let activePage = this.activePage;

                axios.put('pages/' + page.id, {
                    page: page
                }).then(response => {
                    // if response was ok, update the local page object to match the database response
                    if (response.status == 200) {
                        for (var property in response.data) {
                            activePage[property] = response.data[property];
                        }
                    }
                });
            }
        }
    }

</script>