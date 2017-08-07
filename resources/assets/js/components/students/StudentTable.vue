<template>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Students 
                        <input type="text"
                               class="pull-right"
                               v-model="filterText"
                               placeholder="Name search...">
                        <select class="pull-right" v-model="filterGroup">
                            <option value="all">All Groups</option>
                            <option value="null">No Group</option>
                            <option v-for="group in groups" :value="group.id">{{ group.name }}</option>
                        </select>
                    </h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Group</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Current round</th>
                                <th v-for="round in rounds">{{ round.title }}</th>
                                <th>Accessed?</th>
                                <th>Completed?</th>
                                <th>Last access</th>
                            </tr>
                        </thead>
                        <tbody>
                            <student-row v-for="(student, index) in students"
                                         :key="student.id"
                                         :student="student"
                                         :groups="groups"
                                         :filterText="filterText"
                                         :filterGroup="filterGroup"
                                         v-on:checked="checked(index)"
                                         v-on:unchecked="unchecked(index)">
                            </student-row>
                        </tbody>
                    </table>
                    <label for="group-bulk-select">Bulk add to group</label>
                    <select id="group-bulk-select" v-model="bulkGroupId">
                        <option v-for="group in groups" :value="group.id">{{ group.name }}</option>
                    </select>
                    <button v-on:click="bulkGroup">Add checked to group</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                bulkGroupId: null,
                checkedStudents: [],
                filterText: '',
                filterGroup: 'all'
            }
        },

        props: [
            'groups',
            'rounds',
            'students'
        ],

        methods: {
            bulkGroup() {
                if (this.bulkGroupId != null && this.checkedStudents.length > 0) {
                    axios.put('group/' + this.bulkGroupId, {
                        students: this.checkedStudents
                    })
                    .then(response => {
                        if (response.data == 'success') {
                            // todo: update each row's group
                            // uncheck all checkboxes
                            this.checkedStudents = [];
                            // reset the select
                            this.bulkGroupId = null
                        }
                    });

                }
            },

            checked(index) {
                this.checkedStudents.push(this.students[index].id);
            },

            unchecked(index) {
                this.checkedStudents.splice(this.checkedStudents.indexOf(this.students[index].id), 1);
            }
        }
    }
</script>
