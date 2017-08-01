<form id="user-upload" action="{{ url('a/' . $activity->id . '/add-users') }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('POST') }}

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Add users</h3>
                </div>
                <div class="panel-body">
                    <h4>Add students</h4>
                    <p>
                        Any student who clicks the link to this activity in Learn will have an account created.
                        You can identify who has accessed the activity by the existence of this account.
                        Create student users manually if you:
                            <li>Want to assign students to groups</li>
                            <li>Want to be able to easily see who hasn't accessed the activity</li>
                        No damage will be done if the student already has an account, so just paste the emails from Learn.
                    </p>
                    <div class="form-group">
                        <textarea name="students" style="width: 100%;" rows="10" placeholder="Paste emails of students in this module, on a new line for each."></textarea>
                    </div>
                    <h4>Add staff</h4>
                    <p>
                        Staff user accounts will be created whenever a staff member clicks the link to this activity in Learn.
                        Create them manually if you need create groups of students and assign them to staff.
                    </p>
                    <div class="form-group">
                        <textarea name="staff" style="width: 100%;" rows="10" placeholder="Paste emails of staff in this module, on a new line for each."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg pull-right">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
