<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <h4>Edit Page</h4>
            
            <!-- Page Title -->
            <div class="form-group">
                <label for="title" class="col-xs-2 control-label">Title</label>
                <div class="col-xs-10">
                    <input id="title" class="form-control" type="text" v-model="editPage.title" placeholder="Title">
                </div>
            </div>

            <!-- Save / Delete buttons -->
            <div class="form-group">
                <div class="col-xs-offset-2 col-xs-10">
                    <button class="btn btn-lg" v-on:click="savePage" :class="{ disabled: !canSave }">Save</button>
                    <button class="btn" v-on:click="deletePage" :class="{ disabled: !canDelete }">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import 'axios';

    export default {
        data() {
            return {
                editPage: {}
            }
        },

        props: [
            'canDelete',
            'canSave',
            'page'
        ],

        methods: {
            deletePage() {
                this.$emit('delete-page', this.editPage);
            },

            // sends a put of editPage's title to the server (for now)
            // todo: also send the pivots of the blocks / skills
            savePage() {
                axios.put('pages/' + this.editPage.id, {
                    title: this.editPage.title
                }).then(response => {
                    if (response.status == 200) {
                        this.page.title = response.data.title;
                        console.log(this.page.title);
                    }
                });
            }
        },

        watch: {
            page() {
                if (typeof this.page !== 'undefined') {
                    this.editPage = JSON.parse(JSON.stringify(this.page));
                } else {
                    this.editPage = {
                        title: '<No Page Set>' 
                    };
                }
            }
        }
    }

</script>