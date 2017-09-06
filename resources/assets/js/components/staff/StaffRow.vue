<template>
    <tr>
        <td>
            <input type="checkbox"
                   :checked="checked"
                   v-model="checkedStatus"
                   v-on:change="checkboxChanged"
                   v-bind:true-value="true"
                   v-bind:false-value="false">
        </td>
        <td>{{ staffMember.name }}</td>
        <td>
            <select v-model="editGroupId" v-on:change="changedGroup">
                <option value="null">No group</option>
                <option v-for="group in groups" :value="group.id">{{ group.name }}</option>
            </select>
        </td>        
        <td>{{ staffMember.email }}</td>
        <td class="todo"><p v-if="staffMember.hasAccessed">Yes</p><p v-else>No</p></td>
        <td class="todo"><p v-if="staffMember.hasAccessed">{{ staffMember.lastAccessed }}</p></td>
    </tr>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                checkedStatus: false,
                currentGroupId: this.staffMember.groupId,
                editGroupId: this.staffMember.groupId
            }
        },

        props: [
            'checked',
            'groups',
            'staffMember'
        ],

        // todo: review below. not sure we need the checkedStatus thing at all.
        watch: {
            checked () {
                this.checkedStatus = this.checked;
            }
        },

        methods: {
            // when group dropdown is changed, persist to database
            changedGroup() {
                console.log(this.staffMember);
                if (this.editGroupId != this.currentGroupId) {
                    axios.put('users/' + this.staffMember.id + '/group', {
                        groupId: this.editGroupId
                    }).then(response => {
                        this.currentGroupId = response.data;
                        this.editGroupId = response.data;
                    });
                }
            },

            // when checkbox is checked, store in parent array
            checkboxChanged() {
                if (this.checkedStatus) {
                    this.$emit('checked');
                } else {
                    this.$emit('unchecked');
                }
            }
        }
    }
</script>
