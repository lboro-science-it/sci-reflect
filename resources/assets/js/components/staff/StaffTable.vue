<template>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Staff</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Group</th>
                                <th>Email</th>
                                <th>Accessed?</th>
                                <th>Last access</th>
                            </tr>
                        </thead>
                        <tbody>
                            <staff-row v-for="(staffMember, index) in staff"
                                       key="staffMember.id"
                                       :staffMember="staffMember"
                                       :groups="groups"
                                       v-on:checked="checked(index)"
                                       v-on:unchecked="unchecked(index)"
                                       :checked="staffMember.checked">
                            </staff-row>
                        </tbody>
                    </table>
                    <div>
                        <label for="staff-group-bulk-select">Bulk add to group</label>
                        <select id="staff-group-bulk-select" v-model="bulkGroupId">
                            <option v-for="group in groups" :value="group.id">{{ group.name }}</option>
                        </select>
                        <button v-on:click="bulkGroup">Add checked to group</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                bulkGroupId: null
            }
        },

        props: [
            'groups',
            'staff'
        ],

        methods: {
            bulkGroup() {
                if (this.bulkGroupId != null) {
                    // determine which staff are checked at the moment
                    let checkStaff = [];
                    let staffLength = this.staff.length;
                    for (let i = 0; i < staffLength; i++) {
                        let staffMember = this.staff[i];
                        if (staffMember.checked) {
                            checkStaff.push(staffMember.id);
                        }
                    }

                    if (checkStaff.length > 0) {
                        axios.post('groups/' + this.bulkGroupId + '/users', {
                            users: checkStaff
                        })
                        .then(response => {
                            if (response.data == 'success') {
                                // reset all staff' checked status
                                let staffLength = this.staff.length;
                                for (let i = 0; i < staffLength; i++) {
                                    let staffMember = this.staff[i];
                                    staffMember.checked = false;
                                    staffMember.groupId = this.bulkGroupId;
                                }

                                // todo: update each row's group
                                // reset the select
                                this.bulkGroupId = null
                            }
                        });
                    }
                }
            },

            checked(index) {
                this.staff[index].checked = true;
            },

            unchecked(index) {
                this.staff[index].checked = false;
            }
        }
    }
</script>
