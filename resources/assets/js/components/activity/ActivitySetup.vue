<template>
    <div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            <li role="presentation" 
                                :class="{ active: activeTab == 'activity-rounds'}"
                                v-on:click.prevent="activeTab = 'activity-rounds'"
                            >
                                <a href="#">
                                    Rounds ({{ rounds.length }})
                                </a>
                            </li>
                            <li role="presentation"
                                :class="{ active: activeTab == 'activity-pages'}"
                                v-on:click.prevent="activeTab = 'activity-pages'"
                            >
                                <a href="#">
                                    Pages ({{ pages.length }})
                                </a>
                            </li>
                            <li role="presentation"
                                :class="{ active: activeTab == 'activity-skills'}"
                                v-on:click.prevent="activeTab = 'activity-skills'"
                            >
                                <a href="#">
                                    Skills ({{ skills.length }})
                                </a>
                            </li>
                            <li role="presentation"
                                :class="{ active: activeTab == 'activity-content'}"
                                v-on:click.prevent="activeTab = 'activity-content'"
                            >
                                <a href="#">
                                    Content
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <keep-alive>
                    <activity-rounds v-show="activeTab == 'activity-rounds'"
                                     :rounds="editRounds"
                                     v-on:add-round="addRound">
                    </activity-rounds>

                    <component :is="activeTab"
                               :blocks="editBlocks"
                               :categories="editCategories"
                               :choices="editChoices"
                               :pages="editPages"
                               :rounds="editRounds"
                               :skills="editSkills">
                    </component>
                </keep-alive>
            </div>
        </div>

        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>
                        todo
                    </h3>
                    <li>
                        list rounds (selectable, show how many pages, skills inside)
                    </li>
                    <li>
                        add round
                    </li>
                    <li>
                        clone a round
                    </li>
                    <li>
                        add content to round (intro block)
                    </li>
                    <li>
                        setup activity stuff: type of activity, open date, close date
                    </li>
                    <li>
                        round setup stuff: format, keep visible, open/close date, number, title, staff can rate, student can rate
                    </li>
                    <li>
                        list pages (global) - pages tab i guess
                    </li>
                    <li>
                        list pages in round
                    </li>
                    <li>
                        add page to round (from list or new)
                    </li>
                    <li>
                        edit page
                    </li>
                    <li>
                        probably overriding tabs - rounds, pages, skills, categories, choices, indicators (part of skills), content blocks (part of pages ?)
                    </li>
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
                'activeTab': 'activity-rounds',
                'editBlocks': this.blocks,
                'editCategories': this.categories,
                'editChoices': this.choices,
                'editPages': this.pages,
                'editRounds': this.rounds,
                'editSkills': this.skills
            }
        },

        props: [
            'blocks',
            'categories',
            'choices',
            'pages',
            'rounds',
            'skills'
        ],

        methods: {
            addRound(round) {
                this.editRounds.push(round);
            }
        },

        mounted () {
            this.editRounds.sort(function(a, b) {
                return a.round_number - b.round_number;
            });
        }
    }
</script>
