<template>
    <tr>
        <td style="width: 80%; height: 50px;">
            <p style="display: inline-block;"
               v-show="!edit">
                {{ name }}
            </p>
            <button class="pull-right"
                    v-show="!edit"
                    v-on:click="editGroup">
                Edit
            </button>

            <input style="width: 60%;"
                   v-show="edit"
                   :id="id"
                   type="text" 
                   :name="id" 
                   :value="name">
            <button class="pull-right"
                    v-show="edit"
                    v-on:click="saveGroup">
                Save
            </button>
            <button class="pull-right"
                    v-show="edit"
                    v-on:click="cancelEdit">
                Cancel
            </button>
        </td>
        <td>{{ users }}</td>
        <td><button v-on:click="deleteGroup">Delete</button></td>
    </tr>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                'editName': this.name,
                'edit': false,
                'editButtonStyle': {
                    display: 'inline-block'
                },
                'groupNameStyle': {
                    width: '80%'
                }
            }
        },

        props: [
            'display',
            'id',
            'name',
            'users'
        ],

        methods: {
            cancelEdit() {
                this.edit = false;
            },
            deleteGroup () {
                console.log('delete group');
            },
            editGroup () {
                this.edit = true;
                let editInput = document.getElementById(this.id);
                Vue.nextTick(function() { 
                    editInput.focus();
                });
            },
            saveGroup () {
                // check this.name has changed (so we need to store initial name somewhere...)
                // so set a data from the prop on mount
                // if it has changed then submit it plus id to the server.
                // on server just check that the user submitting has a session, a relationship to this activity,
                // and then update it.
                // not to self - given that once a user has authed they have a session, can we not store in the session the fact that they have access to the activity...
            }
        },

        mounted() {

        }
    }
</script>
