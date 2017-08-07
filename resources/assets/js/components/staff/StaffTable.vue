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
                                <th>Group</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Accessed?</th>
                                <th>Last access</th>
                            </tr>
                        </thead>
                        <tbody>
                            <staff-row v-for="staffMember in staff" key="staffMember.id" :staffMember="staffMember">
                            </staff-row>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'staff'
        ]
    }
</script>
