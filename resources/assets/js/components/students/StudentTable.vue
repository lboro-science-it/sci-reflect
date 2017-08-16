<template>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-inline">
                        <div class="form-group">
                            <button :class="overviewClass" v-on:click="setMode('overview')">Overview</button>
                            <button :class="detailsClass" v-on:click="setMode('details')">Details</button>
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
                                <th>Name</th>
                                <th v-show="mode == 'overview'">Group</th>
                                <th v-show="mode == 'overview'" colspan="2" v-for="round in rounds">{{ round.title }}</th>
                                <th v-show="mode == 'details'">Accessed?</th>
                                <th v-show="mode == 'details'">Completed?</th>
                                <th v-show="mode == 'details'">Last access</th>

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
                                         v-on:unchecked="unchecked(index)"
                                         :checked="student.checked">
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
                filterText: '',
                filterGroup: 'all',
                inactiveBtnClass: 'btn btn-lg btn-info',
                mode: 'overview'
            }
        },

        computed: {
            detailsClass() {
                return (this.mode == 'details') ? this.activeBtnClass : this.inactiveBtnClass;
            },
            overviewClass () {
                return (this.mode == 'overview') ? this.activeBtnClass : this.inactiveBtnClass;
            }
        },

        props: [
            'groups',
            'putUrl',
            'rounds',
            'students'
        ],

        methods: {
            bulkGroup() {
                if (this.bulkGroupId != null) {
                    // determine which students are checked at the moment
                    let checkedStudents = [];
                    let studentsLength = this.students.length;
                    for (let i = 0; i < studentsLength; i++) {
                        let student = this.students[i];
                        if (student.checked) {
                            checkedStudents.push(student.id);
                        }
                    }

                    // todo: below seems to be posting but not console logging. why?
                    // todo: after posting, need to be able to update the groups displayed in the table, uncheck the checkboxes,
                    // ... without triggering the onchanged function causing a bunch more queries.
                    if (checkedStudents.length > 0) {
                        console.log(checkedStudents); 
                        axios.put(this.putUrl + '/' + this.bulkGroupId, {
                            students: checkedStudents
                        })
                        .then(response => {
                            if (response.data == 'success') {
                                // reset all students' checked status
                                let studentsLength = this.students.length;
                                for (let i = 0; i < studentsLength; i++) {
                                    let student = this.students[i];
                                    student.checked = false;
                                    student.groupId = this.bulkGroupId;
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
                this.students[index].checked = true;
            },

            setMode(mode) {
                this.mode = mode;
            },

            unchecked(index) {
                this.students[index].checked = false;
            }
        }
    }
</script>
