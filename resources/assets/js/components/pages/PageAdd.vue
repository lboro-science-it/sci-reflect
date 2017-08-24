<template>
    <div class="form-inline">
        <button class="btn btn-lg" v-on:click="toggleAddPage" v-show="!addPage">
            Add page
        </button>

        <div class="form-group" v-show="addPage">
            <input id="addPageInput" type="text" class="form-control input-lg" placeholder="New page title..."
                   v-model="addPageName"
                   v-on:keyup.enter="storePage(addPageName)">
        </div>

        <button class="btn btn-lg" v-on:click="toggleAddPage" v-show="addPage">
            Cancel
        </button>

        <button class="btn btn-lg" v-show="addPage" v-on:click="storePage(addPageName)">
            {{ saveCaption }}
        </button>

    </div>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                addPage: false,
                addPageName: '',
                saveCaption: 'Save'
            }
        },

        props: [
            'pages',
            'round'
        ],

        methods: {
            // posts the page name to the server to create a new page
            storePage(page) {
                let self = this;
                this.saveCaption = 'Saving...';

                axios.post('pages', {
                    page: { 
                        title: this.addPageName 
                    },
                    roundId: this.round.id
                }).then(response => {
                    // response.data contains created 'page' object
                    // + a 'pagePivots' which need to be inserted into this.rounds
                    if (response.status == 200) {
                        this.saveCaption = 'Saved!';
                        // put the page in the local pages object
                        this.pages[response.data.page.id] = response.data.page;
                        // persist the page_pivots to the round
                        this.$emit('add-page-pivots', response.data.page_pivots);
                    } else {
                        this.saveCaption = 'Failed!';
                    }

                    this.addPageName = '';
                    setTimeout(function() {
                        self.saveCaption = 'Save';
                        self.addPage = false;
                    }, 500);
                });
            },

            // toggles whether the add page form is being shown
            toggleAddPage() {
                this.addPage = !this.addPage;
                if (this.addPage) {
                    Vue.nextTick(function() { 
                        document.getElementById('addPageInput').focus();
                    });
                }
            }

        }
    }

</script>