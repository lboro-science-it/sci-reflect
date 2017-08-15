<template>
    <div class="text-center">
        <transition name="fade" mode="out-in">
            <h3 :key="skill.id">{{ skill.title }}</h3>
        </transition>
        <transition name="fade" mode="out-in">
            <p style="padding-bottom: 20px;"
               :key="skill.id">
               {{ skill.description }}
           </p>
       </transition>
        <div v-for="choice in choices" :style="buttonSpacer">
            <transition name="fade" mode="out-in">
                <button class="btn btn-lg choice-btn"
                        :class="getBtnClass(choice.value)"
                        v-on:click="updateRating(choice.value)"
                        :key="skill.id">
                    {{ choice.label }}
                </button>
            </transition>
        </div>
    </div>
</template>

<script>

import 'axios';

export default {
    data() {
        return {
            buttonSpacer: {
                float: 'left',
                paddingLeft: '10px',
                paddingRight: '10px',
                paddingBottom: '20px',
                position: 'relative',
                textAlign: 'center',
                width: (100 / this.choices.length) + "%"
            }
        }
    },

    props: [
        'choices',
        'skill'
    ],

    methods: {
        getBtnClass(value) {
            if (this.skill.rating == value) {
                return 'btn-success';
            } else {
                return 'btn-info';
            }
        },

        // update the skill in the parent array
        updateRating(value) {
            this.$emit('update-rating', value);
        }
    }

}

</script>