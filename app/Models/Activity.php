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

    // Data gathering methods

    /**
     * Create clones of $activity's rounds, blocks, categories, choices,
     * indicators, skills, pages, page_rounds, page_skills, all related to this
     * activity.
     * Starts with the round object and cascades down, with an array
     * to keep track of old IDs => new IDs.
     *
     */
    public function cloneFrom($activity)
    {
        // lazy load necessary data on the source activity
        $activity->load([
            'blocks',
            'categories',
            'choices',
            'pages',
            'rounds',
            'skills.indicators',
            'rounds.pagePivots',
            'pages.skillPivots',
            'pages.blockPivots'
        ]);

        $idMaps = [];       // object to store maps of old to new IDs

        // clone objects that just need the new activity ID, but where the new
        // ids will be used elsewhere
        $idMaps['blocks'] = $this->cloneFromCollection($activity->blocks);
        $idMaps['categories'] = $this->cloneFromCollection($activity->categories);
        $idMaps['pages'] = $this->cloneFromCollection($activity->pages);

        // we don't need to use choice_id anywhere else so just clone it
        $this->cloneFromCollection($activity->choices);

        // clone objects that also need to update relationships' ids using $idMaps
        $idMaps['rounds'] = $this->cloneRounds($activity->rounds, $idMaps);
        $idMaps['skills'] = $this->cloneSkillsAndIndicators($activity->skills, $idMaps);

        $skillPivotsToClone = $activity->pages->pluck('skillPivots')->collapse()->unique();
        $blockPivotsToClone = $activity->pages->pluck('blockPivots')->collapse()->unique();
        $pagePivotsToClone = $activity->rounds->pluck('pagePivots')->collapse()->unique();

        $this->clonePivots($skillPivotsToClone, $idMaps);
        $this->clonePivots($blockPivotsToClone, $idMaps);
        $this->clonePivots($pagePivotsToClone, $idMaps);
    }

    /**
     * Creates a replicated version of each model in $collection, updating its
     * activity_id to be $this->id, returning an array of the old ids => new ids.
     *
     */
    private function cloneFromCollection($collection)
    {
        $idMaps = [];
        foreach ($collection as $item) {
            $newItem = $item->replicate();
            $newItem->activity_id = $this->id;
            $newItem->save();
            $idMaps[$item->id] = $newItem->id;
        }

        return $idMaps;
    }

    /**
     * This is an ugly old method! It accepts a collection of pivot models,
     * which it searches for columns block_id, page_id, round_id, skill_id.
     * If it finds them, it transposes to the new values in $idMaps.
     * On the plus side, it then inserts all the new records in a single insert.s
     *
     */ 
    private function clonePivots($pivotsToClone, $idMaps)
    {
        if (!$pivotsToClone->count()) {
            return;
        }

        $className = get_class($pivotsToClone[0]);

        $pivotsToCreate = [];
        foreach ($pivotsToClone as $pivot) {
            $pivotArr = [];
            foreach ($pivot->getAttributes() as $key => $value) {
                if ($key != 'id') {
                    switch ($key) {
                        case 'block_id':
                            $pivotArray[$key] = $idMaps['blocks'][$value];
                            break;
                        case 'page_id':
                            $pivotArray[$key] = $idMaps['pages'][$value];
                            break;
                        case 'round_id':
                            $pivotArray[$key] = $idMaps['rounds'][$value];
                            break;
                        case 'skill_id':
                            $pivotArray[$key] = $idMaps['skills'][$value];
                            break;
                        case 'created_at':
                            $pivotArray[$key] = date('Y-m-d H:i:s');
                            break;
                        case 'updated_at':
                            $pivotArray[$key] = date('Y-m-d H:i:s');
                            break;
                        default:
                            $pivotArray[$key] = $value;
                    }
                }
            }

            array_push($pivotsToCreate, $pivotArray);
        }

        $className::insert($pivotsToCreate);
    }

    /** 
     * Clones the passed collection of $rounds into $this->activity, also using
     * $idMaps array to update block_id to new values
     *
     */
    private function cloneRounds($rounds, $idMaps)
    {
        $roundIdMaps = [];
        foreach ($rounds as $round) {
            $newRound = $round->replicate();
            $newRound->activity_id = $this->id;
            if (isset($newRound->block_id)) {
                $newRound->block_id = $idMaps['blocks'][$newRound->block_id];
            }

            $newRound->save();

            $roundIdMaps[$round->id] = $newRound->id;
        }

        // iterate them again now we can update the inherit_from_round_id column
        foreach ($rounds as $round) {
            $newRound = \App\Round::find($roundIdMaps[$round->id]);
            if (isset($newRound->inherit_from_round_id)) {
                $newRound->inherit_from_round_id = $roundIdMaps[$newRound->inherit_from_round_id];
                $newRound->save();
            }
        }

        return $roundIdMaps;
    }

    /** 
     * Clones the passed collection of $skills into $this->activity, also using
     * $idMaps array to update category_id and block_id to new values.
     * A
     *
     */
    private function cloneSkillsAndIndicators($skills, $idMaps)
    {
        $skillIdMaps = [];
        $indicatorsToCreate = [];
        foreach ($skills as $skill) {
            $newSkill = $skill->replicate();
            $newSkill->activity_id = $this->id;
            $newSkill->category_id = $idMaps['categories'][$newSkill->category_id];
            $newSkill->block_id = $idMaps['blocks'][$newSkill->block_id];
            $newSkill->save();

            $skillIdMaps[$skill->id] = $newSkill->id;

            // build an array of cloned indicators to create, too. Since we don't
            // need their IDs for any other tables, we can do in a single statement.
            // That's lucky because there are likely to be four per skill.
            foreach ($skill->indicators as $indicator) {
                array_push($indicatorsToCreate, [
                    'skill_id' => $newSkill->id,
                    'text' => $indicator->text,
                    'number' => $indicator->number,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s')
                ]);
            }
        }

        \App\Indicator::insert($indicatorsToCreate);

        return $skillIdMaps;
    }

    /**
     * Takes a json object containing rounds, pages, skills, categories,
     * blocks, choices, and turns them into database objects.
     *
     */
    public function createFromJSON($json)
    {
        $jsonArray = json_decode($json, true);

        // object to track how the array indexes in jsonArray map onto the
        // created database items for blocks, categories, choices, etc
        $idMaps = [];

        // create blocks and categories, storing their ids in case
        // they are referred to in later created pages, rounds, or skills
        if (isset($jsonArray['blocks'])) {
            $idMaps['blocks'] = $this->createBlocksFromArray($jsonArray['blocks']);
        }
        if (isset($jsonArray['categories'])) {
            $idMaps['categories'] = $this->createCategoriesFromArray($jsonArray['categories']);
        }

        // create choices, don't store their ids as just related to activity
        if (isset($jsonArray['choices'])) {
            $this->createChoicesFromArray($jsonArray['choices']);
        }

        // now $idMaps contains the actual ids of the blocks, categories from jsonArray

        // now create skills - pass the $idMaps object to it as it will need to
        // know the actual block and category ids for relationships
        if (isset($jsonArray['skills'])) {
            $idMaps['skills'] = $this->createSkillsAndIndicatorsFromArray($jsonArray['skills'], $idMaps);
        }

        // now pages - pass the $idMaps object as it will need to know actual
        // block and skill ids for relationships
        if (isset($jsonArray['pages'])) {
            $idMaps['pages'] = $this->createPagesFromArray($jsonArray['pages'], $idMaps);
        }

        // finalmente rounds - needs to know ids of pages.
        if (isset($jsonArray['rounds'])) {
            $this->createRoundsFromArray($jsonArray['rounds'], $idMaps);
        }
    }

    /**
     * Quick and dirty function to create a bunch of blocks related to this activity
     * based on a blocksArray (passed by JSON data).
     *
     */
    private function createBlocksFromArray($blocksArray)
    {
        $idMaps = [];
        foreach ($blocksArray as $index => $blockItem) {
            $block = new \App\Block();
            $block->fill($blockItem);
            $block->activity_id = $this->id;
            $block->save();
            $idMaps[$index] = $block->id;
        }

        return $idMaps;
    }

    /**
     * Q&D function to create a bunch of categories related to this activity
     *
     */
    private function createCategoriesFromArray($categoriesArray)
    {
        $idMaps = [];
        foreach ($categoriesArray as $index => $categoryItem) {
            $category = new \App\Category();
            $category->fill($categoryItem);
            $category->activity_id = $this->id;
            $category->save();
            $idMaps[$index] = $category->id;
        }

        return $idMaps;
    }

    /**
     * Q&D function to create a bunch of choices related to this activity
     */
    private function createChoicesFromArray($choicesArray)
    {
        foreach ($choicesArray as $choiceItem) {
            $choice = new \App\Choice();
            $choice->fill($choiceItem);
            $choice->activity_id = $this->id;
            $choice->save();
        }
    }

    /**
     * Takes an associative array (from JSON) of skills, plus separate array
     * of how to transpose ids. Creates the siklls and indicators
     *
     */
    private function createSkillsAndIndicatorsFromArray($skillsArray, $idMaps)
    {
        $skillIdMaps = [];
        foreach ($skillsArray as $index => $skillItem) {
            $skill = new \App\Skill();
            $skill->fill(array_diff_key($skillItem, ['indicators' => '']));
            $skill->block_id = $idMaps['blocks'][$skill->block_id];
            $skill->activity_id = $this->id;
            $skill->save();

            $indicatorItems = $skillItem['indicators'] ?? [];

            foreach ($indicatorItems as $indicatorItem) {
                $indicator = new \App\Indicator();
                $indicator->fill($indicatorItem);
                $indicator->skill_id = $skill->id;
                $indicator->save();
            }

            $skillIdMaps[$index] = $skill->id;
        }

        return $skillIdMaps;
    }

    private function createPagesFromArray($pagesArray, $idMaps)
    {
        $pageIdMaps = [];
        foreach ($pagesArray as $index => $pageItem) {
            $page = new \App\Page();
            $page->fill(array_diff_key($pageItem, ['skills' => '']));
            $page->activity_id = $this->id;
            $page->save();

            // create any blockPivots
            $blockPivotsToCreate = [];
            $blockItems = $pageItem['blocks'] ?? [];

            foreach ($blockItems as $blockItem) {
                array_push($blockPivotsToCreate, [
                    'block_id' => $idMaps['blocks'][$blockItem['id']],
                    'page_id' => $page->id,
                    'position' => $blockItem['position'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            \App\BlockPage::insert($blockPivotsToCreate);

            $skillPivotsToCreate = [];
            $skillItems = $pageItem['skills'] ?? [];

            foreach ($skillItems as $skillItem) {
                array_push($skillPivotsToCreate, [
                    'skill_id' => $idMaps['skills'][$skillItem['id']],
                    'page_id' => $page->id,
                    'position' => $skillItem['position'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            \App\PageSkill::insert($skillPivotsToCreate);

            $pageIdMaps[$index] = $page->id;
        }

        return $pageIdMaps;
    }

    private function createRoundsFromArray($roundsArray, $idMaps)
    {
        foreach ($roundsArray as $roundItem) {
            $round = new \App\Round();
            $round->fill(array_diff_key($roundItem, ['pages' => '']));
            $round->activity_id = $this->id;
            $round->block_id = $idMaps['blocks'][$round->block_id];
            $round->save();

            $pagePivotsToCreate = [];
            $pageItems = $roundItem['pages'] ?? [];

            foreach ($pageItems as $index => $pageItem) {
                array_push($pagePivotsToCreate, [
                    'round_id' => $round->id,
                    'page_id' => $idMaps['pages'][$pageItem],
                    'page_number' => $index + 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            \App\PageRound::insert($pagePivotsToCreate);
        }
        // ok we can just create a round object for every item in the array,
        // excepting of course pages
        // we can then create the PageRound pivots.
        // pretty simple
    }

    /**
     * Returns the categories within the activity, ordered for rendering.
     *
     */
    public function getCategories()
    {
        return $this->categories->sortBy('name')->sortBy('number');
    }

    /** 
     * Returns an object of arrays which can be used to render a chart, based
     * on $this's skills and the ratings provided.
     *
     */
    public function getChartDataFromRatings($ratings = null)
    {
        $ratings = $ratings ?? collect();

        // get the skills / categories in render order
        $skills = $this->getSkills();
        $categories = $this->getCategories();

        $chartData = new stdClass();
        $chartData->values = [];
        $chartData->backgrounds = [];
        $chartData->borders = [];
        $chartData->labels = [];
        $chartData->enabled = [];
        $chartData->max = $this->choices->max('value');

        foreach ($skills as $skill) {
            $rating = $ratings->where('skill_id', $skill->id)->first();

            $category = $categories->where('id', $skill->category_id)->first();

            array_push($chartData->labels, $skill->title);
            if (isset($rating)) {       // insert user rating data
                array_push($chartData->values, $rating->rating);
                array_push($chartData->backgrounds, $category->color);
                array_push($chartData->borders, $category->color);
                array_push($chartData->enabled, true);
            } else {                    // insert placeholder data
                array_push($chartData->values, 1);
                array_push($chartData->backgrounds, '#e5e5e5');
                array_push($chartData->borders, '#e5e5e5');
                array_push($chartData->enabled, false);
            }
        }
        
        return $chartData;
    }

    /**
     * Returns an array of the groups within the activity for rendering.
     *
     */
    public function getGroupListArray()
    {
        $groups = $this->groups->sortBy('name')->values();
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

        $rounds->load('ratings');

        $roundsData = new stdClass();
        $roundsData->completed = collect();
        $roundsData->future = collect();
        $roundsData->current = null;

        foreach($rounds as $round) {
            $round->viewable = $round->isViewable(Auth::user());

            // get the first unique staff rating, so we can display a link to it
            $staffRating = $round->ratings->filter(function ($item) {
                return $item['rater_id'] != Auth::user()->id;
            })->unique('rater_id')->first();

            // store the id of the staff member so we can view their chart
            if (isset($staffRating)) {
                $round->staffRaterId = $staffRating->rater_id;
            }

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
    public function getRoundListArray()
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
        $skills = collect();

        foreach($this->rounds as $round) {
            $skills = $skills->merge($round->getSkills());
        }

        $skills = $skills->unique('id');
        $categories = $this->getCategories();
        $sortedSkills = collect();

        foreach ($categories as $category) {
            $sortedSkills = $sortedSkills->merge($skills->where('category_id', $category->id)->sortBy('title')->sortBy('number'));
        }

        return $sortedSkills;
    }

    /**
     * Get the activity's skills with the user's ratings in a format such that
     * they can be rendered in a view.
     *
     */
    public function getSkillsFromRatings($ratings)
    {
        $skills = $this->getSkills();
        $max = $this->choices->max('value');

        foreach($skills as $skill) {
            $rating = $ratings->where('skill_id', $skill->id)->first();
            $skill->max = $max;

            if (isset($rating)) {       // insert user rating data
                $skill->rating = $rating->rating;
                $skill->percent = $skill->rating / $max * 100;
                $skill->background = app('Reflect')->getBackgroundColor($skill->percent);
            } else {                    // insert placeholder data
                $skill->rating = 0;
                $skill->percent = 0;
                $skill->background = '#e5e5e5';
            }
        }

        return $skills;
    }

    /**
     * Returns an array of the students within the activity for rendering
     */
    public function getStaffListArray()
    {
        // load users' groups as related model directly (no pivot stuff)
        foreach ($this->users as $user) {
            $user->setRelation('group', $this->groups->where('id', $user->pivot->group_id)->first());
        }

        $staff = $this->users->where('pivot.role', 'staff')->sortBy('email');

        $staffArray = [];
        foreach($staff as $staffMember) {
            // add required staffMember data for view to array
            array_push($staffArray, [
                'id' => $staffMember->id,
                'name' => $staffMember->name ?? '',
                'email' => $staffMember->email,
                'groupId' => $staffMember->group->id ?? null,
                'hasAccessed' => isset($staffMember->pivot->lti_user_id),
                'lastAccess' => $staffMember->pivot->updated_at,
            ]);
        }

        return $staffArray;
    }

    /**
     * Returns an array of the students within the activity for rendering.
     * todo: use Eloquent attributes methods to generate these instead, if possible.
     */
    public function getStudentListArray()
    {
        // load the user's group as a related model on the user
        foreach ($this->users as $user) {
            $user->setRelation('group', $this->groups->where('id', $user->pivot->group_id)->first());
        }

        $students = $this->users->where('pivot.role', 'student')->sortBy('email');
        $students->load('selections');

        // load each round's content details, required for calculating each student's completion of rounds
        $this->rounds->load([
            'pages.skills.indicators',
            'ratings'
        ]);
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

    public function isNew()
    {
        return $this->status == 'new';
    }

    public function isDesign()
    {
        return $this->status == 'design';
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

    // Relationship methods

    public function blocks()
    {
        return $this->hasMany('App\Block');
    }

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

    public function skills()
    {
        return $this->hasMany('App\Skill');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot([
            'id', 'complete', 'current_page', 'current_round', 'group_id', 'lti_user_id', 'role'
        ])->withTimestamps();
    }

}
