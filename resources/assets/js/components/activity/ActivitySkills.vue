<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-xs-3">
                    <ul class="nav nav-pills nav-stacked">
                        <li v-for="(skill, index) in editSkills"
                            role="presentation"
                            :class="{ active: index == activeIndex }"
                            v-on:click.prevent="activeIndex = index">
                            <a href="#"
                               :style="getStyle(index)"
                            >
                                {{ skill.title }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-xs-9">
                    {{ editSkills[activeIndex].title }}<br>
                    {{ editSkills[activeIndex].description }}<br>
                    {{ editSkills[activeIndex].block_id }}<br>
                    {{ editSkills[activeIndex].category_id }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data () {
            return {
                activeIndex: 0,
                editSkills: this.skills
            }
        },

        props: [
            'blocks',
            'categories',
            'skills'
        ],

        methods: {
            getStyle (index) {
                return {
                    'border-color': this.categories[this.editSkills[index].category_id].color
                }
            }
        },

        mounted () {

        }
    }

</script>
