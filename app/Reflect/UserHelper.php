<?php

namespace App\Reflect;

use App\User;
use DB;
use Illuminate\Http\Request;
use Validator;

class UserHelper
{
    /**
     * Parses textarea input and returns a collection of email addresses
     * within. Splits by newline, space, comma, semicolon.
     * @return Collection
     */
    private function getEmailsFromText($text)
    {
        $emails = preg_split('/\r\n|[\r\n]|[,; ]/', strtolower($text));

        $validEmails = collect();
        foreach($emails as $email) {
            $email = $email;
            $validator = Validator::make(['email' => $email], [
                'email' => 'email|required'
            ]);
            if (!$validator->fails()) {
                if (!$validEmails->contains($email)) {
                    $validEmails->push($email);
                }
            }
        }

        return $validEmails;
    }

    /**
     * Creates user records for email addresses provided in a single
     * insert statement, returns the resulting collection or an empty one.
     * @return collection
     */
    private function insertUsers($userEmails)
    {
        if ($userEmails->count() > 0) {
            $usersToInsert = [];
            foreach($userEmails as $email) {
                array_push($usersToInsert, [
                    'email' => $email,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            DB::table('users')->insert($usersToInsert);
            return User::whereIn('email', $userEmails)->get();
        }

        return collect();
    }

    /**
     * Creates user records & relationships to activity as appropriate for 
     * students and staff passed as $request input.
     * @return string $message
     */
    public function processRequest(Request $request)
    {
        $studentEmails = $this->getEmailsFromText($request->input('students'));
        $staffEmails = $this->getEmailsFromText($request->input('staff'));
        
        // collection of all emails to check whether we need to create
        $combinedEmails = $studentEmails->merge($staffEmails);

        // get users who already exist in the database
        $existingUsers = User::whereIn('email', $combinedEmails)->get();

        // determine which of the existing users need to be related to the activity
        $activity = $request->route('activity');
        $existingActivityUsers = $activity->users()
                                          ->whereIn('user_id', $existingUsers->pluck('id'))
                                          ->get();
        $existingUsersToRelate = $existingUsers->diff($existingActivityUsers);

        // create users that need creating, store them for relating
        $userEmailsToCreate = $combinedEmails->diff($existingUsers->pluck('email'));
        $createdUsersToRelate = $this->insertUsers($userEmailsToCreate);

        $usersToRelate = $existingUsersToRelate->merge($createdUsersToRelate);

        $this->relateUsers($usersToRelate, $activity, $staffEmails->toArray());

        $message = $userEmailsToCreate->count() . ' users created.';
        $message .= ' ' . $existingUsers->count() . ' users already existed.';
        $message .= ' ' . $usersToRelate->count() . ' users linked.';

        return $message;
    }

    /**
     * Creates relationships between $users and current $activity, with role
     * based on whether their email is in $staff.
     * @return void
     */
    private function relateUsers($users, $activity, $staff = [])
    {
        if ($users->count() > 0) {

            $relationshipsToInsert = [];
            foreach($users as $user) {
                $role = in_array($user->email, $staff) ? 'staff' : 'student';

                array_push($relationshipsToInsert, [
                    'activity_id' => $activity->id,
                    'user_id' => $user->id,
                    'role' => $role,
                    'current_page' => 1,
                    'current_round' => 1,
                    'complete' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            DB::table('activity_user')->insert($relationshipsToInsert);
        }
    }

}