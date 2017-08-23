<template>
    <div class="list-group-item">
         <span class="glyphicon glyphicon-move"></span>
         
         <span v-show="!editTitle">
             {{ editPage.page_number }}: {{ editPage.title }}
         </span>
         <input type="text" :id="editPage.id"
                            v-model="editPage.title" 
                            v-show="editTitle"
                            v-on:keyup.enter="saveTitle(index)">

         <div class="pull-right">
             <button v-on:click="activateEditing(index)" v-show="!editTitle">edit</button>
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
                this.editTitle = true;
                let editInput = document.getElementById(this.editPage.id);
                Vue.nextTick(function() { 
                    editInput.focus();
                });
            },

            // reset title to original page title and turn off editing
            cancelEditTitle(index) {
                this.editTitle = false;
                this.editPage.title = JSON.parse(JSON.stringify(this.originalPage.title));
            },

            deletePage(index) {
                if (confirm("Are you sure?")) {
                    console.log('here we would delete the page at index: ' + index);
                }
            },

            saveTitle(index) {
                // todo: post new title to server and update original page title from that
                this.originalPage.title = JSON.parse(JSON.stringify(this.editPage.title));
                this.editTitle = false;
                console.log('here we would save the page title');
            }            
        }
    }
</script>
