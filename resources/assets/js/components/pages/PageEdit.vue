<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <h4>Edit Page</h4>
            
            <!-- Page Title -->
            <div class="form-group">
                <label for="title" class="col-xs-3 control-label">Title</label>
                <div class="col-xs-9">
                    <input id="title" class="form-control" type="text" v-model="editPage.title" placeholder="Title">
                </div>
            </div>

            <!-- Page content -->
            <!-- Each item in the page.content array can be either a block or
                 a skill - so when we v-for them we are dynamically including
                 the necessary child component. -->
            <draggable :list="editContent" class="dragArea" :options="{ handle: '.glyphicon' }">
                <component v-for="(contentItem, index) in editContent"
                           :is="'content-' + contentItem.type"
                           :key="contentItem.type + contentItem.id"
                           :item="contentItem"
                           v-on:unrelate-item="unrelateItem">
                </component>
            </draggable>

            <transition name="fade" mode="out-in">
                <add-skill :skills="skills" v-show="canSave"></add-skill>
            </transition>
            <transition name="fade" mode="out-in">
                <add-block :blocks="blocks"
                           v-on:add-block="addBlock"
                           v-show="canSave">
                </add-block>
            </transition>

            <!-- Save / Delete buttons -->
            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-9">
                    <button class="btn btn-lg" v-on:click="savePage" :class="{ disabled: !canSave }">Save</button>
                    <button class="btn" v-on:click="deletePage" :class="{ disabled: !canDelete }">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    const defaultPage = {
        id: null,
        title: '<No Page Selected>',
        block_pivots: [],
        content: [],
        skill_pivots: []
    };

    import 'axios';

    export default {
        data() {
            return {
                editContent: [],
                editPage: defaultPage
            }
        },

        props: [
            'blocks',
            'canDelete',
            'canSave',
            'page',
            'skills'
        ],

        methods: {
            // creates a relationship between the current page and the block,
            // adding the block after the final position to the page
            addBlock(blockId) {
                axios.put('pages/' + this.editPage.id + '/blocks', {
                    blockId: blockId
                }).then(response => {
                    if (response.status == 200) {
                        // the block was related, response.data.position tells us what position at
                        // update the page's block_pivots then refresh the editPage object
                        this.page.block_pivots.push({
                            block_id: blockId,
                            position: response.data.position
                        });

                        this.refreshEditPage();
                    }
                });
            },

            deletePage() {
                if (this.canDelete) {
                    this.$emit('delete', this.editPage);
                }
            },

            // clone page prop into local editPage object and populate with its content
            refreshEditPage() {
                // if this.page.id === null, it means no page is selected so we don't have any content to get
                if (this.page.id !== null) {
                    // clone the page into a local object that can be edited
                    this.editPage = JSON.parse(JSON.stringify(this.page));

                    this.editContent = this.editPage.block_pivots.concat(this.editPage.skill_pivots);
                    this.editContent.sort(function(a, b) {
                        return a.position - b.position;
                    });

                    // get the content of the blocks / skills from the global objects
                    let contentLength = this.editContent.length;
                    for (let i = 0; i < contentLength; i++) {
                        let contentItem = this.editContent[i];
        
                        if (typeof contentItem.block_id !== 'undefined') {
                            this.editContent[i] = JSON.parse(JSON.stringify(this.blocks[contentItem.block_id]));
                            this.editContent[i].type = "block";
                            this.editContent[i].id = contentItem.block_id;
                        } else if (typeof contentItem.skill_id !== 'undefined') {
                            this.editContent[i] = JSON.parse(JSON.stringify(this.skills[contentItem.skill_id]));
                            this.editContent[i].type = "skill";
                            this.editContent[i].id = contentItem.skill_id;
                        }

                        this.editContent[i].position = contentItem.position;
                    }

                } else {
                    // create an empty editPage with empty content so the view can render
                    this.editPage = JSON.parse(JSON.stringify(this.page));
                    this.editContent = [];
                }
            },

            // sends a put of editPage's title to the server (for now)
            // todo: also send the pivots of the blocks / skills
            savePage() {
                if (this.canSave) {
                    let blockPositions = [];
                    let skillPositions = [];
                    let contentLength = this.editContent.length;

                    for (let i = 0; i < contentLength; i++) {
                        let contentItem = this.editContent[i];
                        contentItem.position = i + 1;
                        if (contentItem.type == 'block') {
                            blockPositions.push({
                                id: contentItem.id,
                                position: contentItem.position
                            });
                        } else if (contentItem.type == 'skill') {
                            skillPositions.push({
                                id: contentItem.id,
                                position: contentItem.position
                            });
                        }
                    }

                    axios.put('pages/' + this.editPage.id, {
                        title: this.editPage.title,
                        blocks: blockPositions,
                        skills: skillPositions
                    }).then(response => {
                        console.log(response.data);
                        if (response.status == 200) {
                            this.page.title = response.data.title;
                        }
                    });
                }
            },

            // update this.page's block_pivots to remove the one with ID at position
            spliceBlockByIdAndPosition(blockId, position) {
                for (let index in this.page.block_pivots) {
                    let blockPivot = this.page.block_pivots[index];

                    if (blockPivot.block_id == blockId && blockPivot.position == position) {
                        this.page.block_pivots.splice(index, 1);
                        break;
                    }
                }

                this.refreshEditPage();
            },

            // update this.page's skill_pivots to remove the one at ID and Position
            spliceSkillByIdAndPosition(skillId, position) {
                for (let index in this.page.skill_pivots) {
                    let skillPivot = this.page.skill_pivots[index];

                    if (skillPivot.skill_id == skillId && skillPivot.position == position) {
                        this.page.skill_pivots.splice(index, 1);
                        break;
                    }
                }

                this.refreshEditPage();
            },

            // remove pivot entry between item and current page
            // also need to refresh all positions afterwards
            unrelateItem(item) {
                axios.put('pages/' + this.page.id + '/unrelate', {
                    type: item.type,
                    id: item.id
                }).then(response => {
                    console.log(response.data);
                    if (item.type == 'block') {
                        this.spliceBlockByIdAndPosition(item.id, item.position);
                    } else if (item.type == 'skill') {
                        this.spliceSkillByIdAndPosition(item.id, item.position);
                    }
                });
            }
        },

        watch: {
            // when page changes, put together its content and clone it all into editPage
            page() {
                this.refreshEditPage();
            }
        }
    }

</script>