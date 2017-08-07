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
                            <student-row v-for="student in students"
                                         key="student.id"
                                         :student="student"
                                         :groups="groups"
                                         :filterText="filterText"
                                         :filterGroup="filterGroup">
                            </student-row>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                filterText: '',
                filterGroup: 'all'
            }
        },

        props: [
            'groups',
            'rounds',
            'students'
        ]
    }
</script>
