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
                               :blocks="blocks"
                               :skills="skills"
                               :can-save="activePage.id !== null"
                               :can-delete="activePage.id !== null && pages.length > 1"
                               v-on:delete="deletePage"
                               v-on:refresh="refreshPage">
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
                activePage: defaultPage
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
                // combine page's blocks and skills into iterable content array sorted by position
                activePage.content = activePage.block_pivots.concat(activePage.skill_pivots);
                activePage.content.sort(function(a, b) {
                    return a.position - b.position;
                });

                // get the content of the blocks / skills from the global objects
                let contentLength = activePage.content.length;
                for (let i = 0; i < contentLength; i++) {
                    let contentItem = activePage.content[i];
    
                    if (typeof contentItem.block_id !== 'undefined') {
                        activePage.content[i] = JSON.parse(JSON.stringify(this.blocks[contentItem.block_id]));
                        activePage.content[i].type = "block";
                        activePage.content[i].id = contentItem.block_id;
                    } else if (typeof contentItem.skill_id !== 'undefined') {
                        activePage.content[i] = JSON.parse(JSON.stringify(this.skills[contentItem.skill_id]));
                        activePage.content[i].type = "skill";
                        activePage.content[i].id = contentItem.skill_id;
                    }

                    activePage.content[i].position = contentItem.position;
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
                        let page = response.data.page;
                        page.page_number = response.data.page_pivot.page_number;
                        // todo: create page_pivot on active round if specified
                        this.pages.push(response.data.page);
                        this.round.page_pivots.push(response.data.page_pivot);
                    }
                });
            },

            // deletes the page, updates the local pages array
            deletePage(page) {
                if (this.pages.length > 1 && confirm("Are you sure?")) {

                    axios.delete('pages/' + page.id)
                        .then(response => {
                            if (response.status == 204) {
                                // page has been deleted on server - update local pages array
                                this.splicePageById(page.id);
                                this.renumberPages();
                                this.activatePage(null);
                                // tell parent component (rounds-setup) a page has been deleted
                                this.$emit('delete-page', page);
                            }
                        });

                }
            },

            // function to update the content of this.activePage
            refreshPage() {
                // todo
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

            splicePageById(pageId) {
                let pagesLength = this.pages.length;
                for (let i = 0; i < pagesLength; i++) {
                    let currentPage = this.pages[i];
                    if (currentPage.id == pageId) {
                        this.pages.splice(i, 1);
                        break;
                    }
                }
            }

        }
    }

</script>