<template>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-inline">
                        <div class="form-group">
                            <button :class="overviewClass" v-on:click="setMode('overview')">Overview</button>
                            <button :class="rateClass" v-on:click="setMode('rate')">Rate</button>
                        </div>
                        <div class="form-group">
                            <select class="form-control input-lg" v-model="filterGroup">
                                <option value="all">All Groups</option>
                                <option value="null">No Group</option>
                                <option v-for="group in groups" :value="group.id">{{ group.name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text"
                                   class="form-control input-lg"
                                   v-model="filterText"
                                   placeholder="Name search...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Students</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th v-show="mode == 'overview'">Group</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th v-show="mode == 'overview'">Current round</th>
                                <th v-show="mode == 'overview'" v-for="round in rounds">{{ round.title }}</th>
                                <th v-show="mode == 'overview'">Accessed?</th>
                                <th v-show="mode == 'overview'">Completed?</th>
                                <th v-show="mode == 'overview'">Last access</th>
                                <th v-show="mode == 'rate'">Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <student-row v-for="(student, index) in students"
                                         :key="student.id"
                                         :student="student"
                                         :groups="groups"
                                         :filterText="filterText"
                                         :filterGroup="filterGroup"
                                         :mode="mode"
                                         v-on:checked="checked(index)"
                                         v-on:unchecked="unchecked(index)">
                            </student-row>
                        </tbody>
                    </table>
                    <div v-show="mode == 'overview'">
                        <label for="group-bulk-select">Bulk add to group</label>
                        <select id="group-bulk-select" v-model="bulkGroupId">
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
    import 'axios';

    export default {
        data () {
            return {
                activeBtnClass: 'btn btn-lg btn-success',
                bulkGroupId: null,
                checkedStudents: [],
                filterText: '',
                filterGroup: 'all',
                inactiveBtnClass: 'btn btn-lg btn-info',
                mode: 'overview'
            }
        },

        computed: {
            overviewClass () {
                return (this.mode == 'overview') ? this.activeBtnClass : this.inactiveBtnClass;
            },
            rateClass () {
                return (this.mode == 'rate') ? this.activeBtnClass : this.inactiveBtnClass;
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

            setMode(mode) {
                this.mode = mode;
            },

            unchecked(index) {
                this.checkedStudents.splice(this.checkedStudents.indexOf(this.students[index].id), 1);
            }
        }
    }
</script>
