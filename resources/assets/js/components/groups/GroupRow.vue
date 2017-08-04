<template>
    <tr>
        <td style="width: 80%; height: 50px;">
            <p style="display: inline-block;"
               v-show="!edit">
                {{ editName }} 
            </p>
            <button class="pull-right"
                    v-show="!edit && !saving"
                    v-on:click="editGroup">
                Edit
            </button>

            <input style="width: 60%;"
                   v-show="edit"
                   :id="id"
                   type="text" 
                   :name="id" 
                   v-on:keyup.enter="saveGroup"
                   v-model="editName">
            <button class="pull-right"
                    v-show="edit || saving"
                    v-on:click="saveGroup">
                {{ saveText }}
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
                currentName: this.name,
                editName: this.name,
                edit: false,
                editButtonStyle: {
                    display: 'inline-block'
                },
                groupNameStyle: {
                    width: '80%'
                },
                saveText: 'Save',
                saving: false
            }
        },

        props: [
            'display',
            'id',
            'name',
            'users'
        ],

        methods: {
            cancelEdit() {      // revert edit name and turn off edit mode
                this.editName = this.currentName;
                this.edit = false;
            },
            deleteGroup () {    // send a delete request
                 axios.delete('groups/' + this.id).then(response => {
                    this.$emit('delete-group', this.id);
                });
            },
            editGroup () {      // set edit mode and focus the input
                this.edit = true;
                let editInput = document.getElementById(this.id);
                Vue.nextTick(function() { 
                    editInput.focus();
                });
            },
            saveGroup () {      // update the current name and send the put request
                if (this.editName !== this.currentName) {
                    this.edit = false;
                    this.saving = true;
                    this.saveText = 'Saving...';
                    axios.put('groups/' + this.id, {
                        groupName: this.editName
                    }).then(response => {
                        this.currentName = response.data;
                        this.saving = false;
                        this.saveText = 'Save';
                    });
                }
            }
        }
    }
</script>
