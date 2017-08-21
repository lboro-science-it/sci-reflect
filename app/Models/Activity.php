<?php

namespace App;

use Auth;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class Activity extends Model
{
    protected $fillable = [
        'close_date', 'format', 'name', 'open_date', 'resource_link_record_id', 'consumer_pk',
    ];

    protected $hidden = [
        'consumer_pk', 'resource_link_record_id', 'status'
    ];

    // Relationship methods

    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    public function choices()
    {
        return $this->hasMany('App\Choice');
    }

    public function groups()
    {
        return $this->hasMany('App\Group');
    }

    public function pages()
    {
        return $this->hasMany('App\Page');
    }

    public function rounds()
    {
        return $this->hasMany('App\Round');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot([
            'id', 'complete', 'current_page', 'current_round', 'group_id', 'lti_user_id', 'role'
        ])->withTimestamps();
    }


    // Data gathering methods

    /**
     * Returns the categories within the activity, ordered for rendering.
     *
     */
    public function getCategories()
    {
        if (!$this->relationLoaded('categories')) {
            $categories = $this->categories->sortBy('name')->sortBy('number');
            $this->setRelation('categories', $categories);
        }

        return $this->categories;
    }

    /**
     * Returns an array of the groups within the activity for rendering.
     *
     */
    public function getGroupListArray()
    {
        $groups = $this->groups->sortBy('name');
        $groups->load('activityUsers.user');

        return $groups->toArray();
    }

    /**
     * Returns an object of data about the state of user's progress in rounds
     */
    public function getRoundsData()
    {
        $rounds = $this->rounds->where(Auth::user()->role . '_rate', true)->sortBy('round_number');
        $currentRoundNumber = Auth::user()->currentRound;

        $roundsData = new stdClass();

        $roundsData->completed = collect(array());
        $roundsData->future = collect(array());
        $roundsData->current = null;

        foreach($rounds as $round) {
            $round->viewable = $round->isViewable(Auth::user());
            if (is_null($currentRoundNumber) || $round->round_number < $currentRoundNumber) {
                $round->completion = '100%';
                $round->completionDecimal = 1.0;
                $roundsData->completed->push($round);
            } elseif ($round->round_number == $currentRoundNumber) {
                $round->completion = Auth::user()->getCompletion($round);
                $round->completionDecimal = Auth::user()->getCompletionDecimal($round);
                $roundsData->current = $round;
            } else {
                $round->completion = null;
                $round->completionDecimal = null;
                $roundsData->future->push($round);
            }
        }

        return $roundsData;        
    }

    /**
     * Returns an array of rounds for use in the activity dashboard
     */
    public function getRoundsListArray()
    {
        $rounds = $this->rounds->sortBy('round_number');

        $roundsArray = [];
        foreach ($rounds as $round) {
            array_push($roundsArray, [
                'roundNumber' => $round->round_number,
                'title' => $round->title
            ]);
        }

        return $roundsArray;
    }

    /**
     * Returns a collection of activity's skills in render order
     * @return Collection
     */
    public function getSkills()
    {
        if (!isset($this->skills)) {
            $skills = collect(array());

            foreach($this->rounds as $round) {
                $skills = $skills->merge($round->getSkills());
            }

            $skills = $skills->unique('id');
            $categories = $this->getCategories();
            $sortedSkills = collect(array());

            foreach ($categories as $category) {
                $sortedSkills = $sortedSkills->merge($skills->where('category_id', $category->id)->sortBy('title')->sortBy('number'));
            }
            $this->skills = $sortedSkills;

        }

        return $this->skills;
    }

    /**
     * Returns a collection of categories containing skills for rendering
     * @return Collection
     */
    public function getSkillsInCategories()
    {
        $skills = $this->getSkills();
        $categories = $this->getCategories()->whereIn('id', $skills->pluck('category_id')->unique());

        foreach($categories as $category) {
            $category->setRelation('skills', $skills->where('category_id', $category->id));
        }

        return $categories;
    }

    /**
     * Returns an array of the students within the activity for rendering
     */
    public function getStaffListArray()
    {
        $this->loadUserGroups();

        $staff = $this->users->where('pivot.role', 'staff')->sortBy('email');

        $staffArray = [];
        foreach($staff as $staffMember) {
            // add required staffMember data for view to array
            array_push($staffArray, [
                'id' => $staffMember->id,
                'name' => $staffMember->name ?? '',
                'email' => $staffMember->email,
                'groupName' => $staffMember->group->name ?? false,
                'hasAccessed' => isset($staffMember->pivot->lti_user_id),
                'lastAccess' => $staffMember->pivot->updated_at,
            ]);
        }

        return $staffArray;
    }

    /**
     * Returns an array of the students within the activity for rendering
     */
    public function getStudentListArray()
    {
        $this->loadUserGroups();

        $students = $this->users->where('pivot.role', 'student')->sortBy('email');
        $students->load('selections');

        // load each round's content details, required for calculating each student's completion of rounds
        $this->rounds->load('pages.skills.indicators');
        $rounds = $this->rounds->sortBy('round_number');

        $studentsArray = [];
        foreach($students as $student) {
            // get student's completion of each round
            $roundsArray = [];
            foreach($rounds as $round) {
                array_push($roundsArray, [
                    'completion' => $student->getCompletion($round),
                    'roundNumber' => $round->round_number,
                    'staffCanRate' => $round->staff_rate ? true : false,
                    'staffHasRated' => $student->staffHasRated($round)
                ]);
            }

            // add required student data for view to array
            array_push($studentsArray, [
                'id' => $student->id,
                'name' => $student->name ?? '',
                'email' => $student->email,
                'groupId' => $student->group->id ?? null,
                'currentRoundNumber' => $student->pivot->current_round ?? false,
                'hasAccessed' => isset($student->pivot->lti_user_id),
                'complete' => $student->pivot->complete,
                'rounds' => $roundsArray,
                'lastAccess' => $student->pivot->updated_at,
            ]);
        }

        return $studentsArray;
    }

    protected $userGroupsLoaded = false;
    /**
     * Gets the activity's groups and saves them as relations to the activity's
     * users directly (rather than activity->user pivots)
     *
     */
    public function loadUserGroups()
    {
        if (!$this->userGroupsLoaded) {
            // manually relate group to each user based on pivot to activity
            $groups = $this->groups;
            foreach ($this->users as $user) {
                $user->setRelation('group', $groups->where('id', $user->pivot->group_id)->first());
            }
            $this->userGroupsLoaded = true;
        }
    }

    /**
     * Returns true if the current date falls within the activity's open_date
     * and close_date, if they are set, or true if they are not set.
     * @return bool
     */
    public function isOpen()
    {
        if ($this->status == 'open') {
            $now = new DateTime();
            $from = new DateTime($this->open_date);
            $to = new DateTime($this->close_date);
            if ($now >= $from && $now <= $to) {
                return true;
            }
        }

        return false;
    }

}
