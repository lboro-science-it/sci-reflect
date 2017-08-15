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
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>
                        Rate {{ studentName }}
                    </h3>
                </div>
                <div class="panel-body">
                    <skill-rater :skill="skills[activeSkillIndex]"
                                 :choices="choices"
                                 v-on:update-rating="updateRating">
                    </skill-rater>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-xs-6 text-left">
                            <a class="btn btn-lg btn-success" :href="homeUrl">
                                Back to Dashboard
                            </a>
                        </div>
                        <div class="col-xs-6 text-right">
                            <button class="btn btn-lg"
                                    :class="saveBtnClass"
                                    v-on:click="storeRatings">
                                {{ saveText }}
                            </button>
                        </div>
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
                activeSkillIndex: 0,
                saveText: 'Save',
                unratedSkillIds: []
            }
        },

        props: [
            'choices',
            'homeUrl',
            'postUrl',
            'roundNumber',
            'skills',
            'studentId',
            'studentName'
        ],

        computed: {
            saveBtnClass() {
                if (this.unratedSkillIds.length > 0) {
                    return 'btn-info';
                } else {
                    return 'btn-success';
                }
            }
        },

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
                this.saveText = 'Saving...';
                let postData = {};

                // create object of skill_id => rating for insert to db
                let skillsLength = this.skills.length;
                for (let i = 0; i < skillsLength; i++) {
                    let skill = this.skills[i];
                    postData[skill.id] = skill.rating;
                }
                
                axios.post(this.postUrl, {
                    'skills': postData
                })
                .then(response => {
                    this.saveText = (response.data == 'success') ? 'Saved!' : 'Error!';

                    let self = this;
                    setTimeout(function() {
                        self.saveText = 'Save';
                    }, 5000);
                });
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