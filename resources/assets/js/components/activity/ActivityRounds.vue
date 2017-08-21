<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                An activity can have one or more rounds. Rounds are a single iteration of the student being required to reflect on their skills.
                Rounds contain pages, which can display skills and content to the student. It doesn't matter if a skill is present in all rounds, a single round, or selected rounds.
                When the student completes a round, they can see a chart of their reflections against the skills present in that round.
            </div>
            <div class="panel-body">
                <div class="col-xs-3">
                    <div class="list-group" role="group">
                        <div class="list-group-item">
                            <input type="text">
                            <button type="submit">
                                save
                            </button>
                        </div>

                        <div class="list-group-item"
                             role="button" 
                             v-for="(round, index) in rounds"
                             :class="{ active: index == activeIndex }"
                             v-on:click.prevent="activeIndex = index"
                        >
                            {{ round.title }}
                        </div>
                    </div>
                </div>
                <div class="col-xs-9">
                    content about editing the round
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data () {
            return {
                activeIndex: 0
            }
        },

        props: [
            'rounds'
        ],

        mounted () {

        }
    }

</script>
