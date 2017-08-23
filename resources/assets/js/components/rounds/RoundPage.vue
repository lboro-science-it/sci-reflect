<template>
    <div class="list-group-item">
         <span class="glyphicon glyphicon-move"></span>
         
         <span v-show="!editTitle">
             {{ editPage.page_number }}: {{ editPage.title }}
         </span>
         <input type="text" v-model="editPage.title" v-show="editTitle">

         <div class="pull-right">
             <button v-on:click="editTitle = true" v-show="!editTitle">edit</button>
             <button v-on:click="saveTitle(index)" v-show="editTitle">save</button>
             <button v-on:click="cancelEditTitle(index)" v-show="editTitle">cancel</button>
             <button v-on:click="deletePage(index)" :class="{ disabled: canDelete }" v-show="!editTitle">delete</button>
         </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                editTitle: false
            }
        },

        props: [
            'canDelete',
            'editPage',
            'index',
            'originalPage'
        ],

        methods: {
            activateEditing(index) {
                console.log('activateEditing for ' + index);
                this.editPages[index].editing = true;
                console.log(this.editPages[index]);
            },

            cancelEditTitle(index) {
                // todo: reset to original title... even though we don't have it here.
                // but if we get pages we can get it
                this.editTitle = false;
                this.editPage.title = JSON.parse(JSON.stringify(this.originalPage.title));
            },

            deletePage(index) {
                if (confirm("Are you sure?")) {
                    console.log('here we would delete the page at index: ' + index);
                }
            },


            saveTitle(index) {
                console.log('here we would save the page title');
                console.log('so we need to know the page being saved here');
            }            
        }
    }
</script>
