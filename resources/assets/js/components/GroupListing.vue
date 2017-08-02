<template>
    <tr>
        <td>
            <p v-show="!edit">{{ name }}</p>
            <input v-show="edit"
                   :id="id"
                   type="text" 
                   :name="id" 
                   :value="name">
        </td>
        <td>{{ users }}</td>
        <td><button v-on:click="editGroup">Edit</button></td>
        <td><button v-on:click="deleteGroup">Delete</button></td>
    </tr>
</template>

<script>
    export default {
        data () {
            return {
                'edit': false
            }
        },

        props: [
            'name',
            'users',
            'id'
        ],

        methods: {
            deleteGroup () {
                console.log('delete group');
            },
            editGroup () {
                this.edit = true;
                let editInput = document.getElementById(this.id);
                Vue.nextTick(function() { 
                    editInput.focus();
                });
            }
        },

        mounted() {

        }
    }
</script>
