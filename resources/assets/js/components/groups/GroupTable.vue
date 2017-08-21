<template>
    <div class="row" v-show="editGroups.length > 0">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Groups</h3>
                </div>
                <div class="panel-body">

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Group</th>
                                <th>Users in group</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <group-row v-for="(group, index) in editGroups"
                                       :key="group.id"
                                       :name="group.name"
                                       :users="group.user_count"
                                       :id="group.id"
                                       :delete-url="deleteUrl"
                                       :put-url="putUrl"
                                       v-on:delete-group="deleteGroup(index)">
                            </group-row>
                        </tbody>
                    </table>

                    <p v-show="!groups">
                        Loading groups.
                    </p>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                editGroups: this.groups
            }
        },

        props: [
            'deleteUrl',
            'groups',
            'putUrl'
        ],

        methods: {
            deleteGroup (index) {
                this.editGroups.splice(index, 1);
            }
        },

        created () {
            let self = this;
            this.$parent.$on('groups-added', function(groups) {
                self.editGroups = groups;
                self.editGroups.splice(0, 0);
            });
        }
    }
</script>
