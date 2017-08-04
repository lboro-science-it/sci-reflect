<template>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Groups</h3>
                </div>
                <div class="panel-body">

                    <table class="table" v-show="groups">
                        <thead>
                            <tr>
                                <th>Group</th>
                                <th>Users in group</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <group-row v-for="group in groups"
                                       :key="group.id"
                                       :name="group.name"
                                       :users="group.userCount"
                                       :id="group.id">
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
    import 'axios';

    export default {


        props: [
            'groups'
        ]

    }
</script>
