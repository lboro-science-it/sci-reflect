<template>
    <div class="row">
        <div class="col-md-3 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Skills</h3>
                </div>
                <ul class="list-group">
                    <skill-list-item v-for="(skill, index) in skills"
                                     :key="skill.id"
                                     :skill="skill"
                                     :active="index == activeSkillIndex"
                                     v-on:activate="activate(index)">
                    </skill-list-item>
                </ul>
            </div>
        </div>

        <div class="col-md-7">
            <skill-rater :skill="skills[activeSkillIndex]"
                         :choices="choices"
                         :studentName="studentName"
                         v-on:update-rating="updateRating"
                         v-on:store-ratings="storeRatings"
                         :saveBtn="!unratedSkillIds.length">
            </skill-rater>
        </div>
    </div>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                activeSkillIndex: 0,
                unratedSkillIds: []
            }
        },

        props: [
            'skills',
            'choices',
            'roundNumber',
            'studentId',
            'studentName'
        ],

        mounted () {
            // create unratedSkillIds array to track when to show done button
            let skillsLength = this.skills.length;
            for (let i = 0; i < skillsLength; i++) {
                let skill = this.skills[i];
                if (skill.rating === null) {
                    this.unratedSkillIds.push(skill.id);
                }
            }
        },

        methods: {
            // display the skill in the SkillRater panel
            activate(index) {
                this.activeSkillIndex = index;
            },

            storeRatings() {
                let postData = {};

                // create object of skill_id => rating for insert to db
                let skillsLength = this.skills.length;
                for (let i = 0; i < skillsLength; i++) {
                    let skill = this.skills[i];
                    postData[skill.id] = skill.rating;
                }
                
                let postUrl = window.sciReflect.baseUrl + '/r/' + this.roundNumber + '/rate/' + this.studentId;

                axios.post(postUrl, {
                    'skills': postData
                })
                .then(response => {
                    console.log(response.data);
                });

                // ok so we need to post...
                // rated id (student id)
                // round id
                // skills array (just the skill id and rating for each)
                // to a post of whatever the route is to get this page
            },

            // set the rating on the skills array object
            updateRating(value) {
                let activeSkill = this.skills[this.activeSkillIndex];
                activeSkill.rating = value;
                // update the array of unratedSkillIds
                let unratedSkillIndex = this.unratedSkillIds.indexOf(activeSkill.id);
                // delete the skill from unratedSkills so we can count if there are still unratedSkills
                if (unratedSkillIndex >= 0) {
                    this.unratedSkillIds.splice(unratedSkillIndex, 1);
                }
                // advance to the next skill
                // todo: checking whether next skill is unrated, if not, advance to next unrated skill instead
                if (this.skills.length > (this.activeSkillIndex + 1)) {
                    this.activeSkillIndex++;
                }
            }
        }

    }
</script>