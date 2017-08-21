<template>
    <div class="row">
        <activity-tabs></activity-tabs>
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
                'editRounds': this.rounds
            }
        },

        props: [
            'rounds'
        ],

        methods: {

        },

        mounted () {
            console.log(this.editRounds);
        }
    }
</script>
