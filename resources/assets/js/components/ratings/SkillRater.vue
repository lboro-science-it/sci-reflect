<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                Rate {{ studentName }}
            </h3>

        </div>

        <div class="panel-body text-center">
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
                    <button class="btn btn-lg btn-success"
                            v-on:click="updateRating(choice.value)"
                            :key="skill.id">
                        {{ choice.label }}
                    </button>
                </transition>
            </div>
        </div>

        <div class="panel-footer text-center">
            <button class="btn btn-lg btn-success"
                    v-on:click="storeRatings">
                Save
            </button>
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
        'saveBtn',
        'skill',
        'studentName'
    ],

    methods: {
        // emit an event to store ratings to the database
        storeRatings() {
            this.$emit('store-ratings');
        },

        // update the skill in the parent array
        updateRating(value) {
            this.$emit('update-rating', value);
        }
    }

}

</script>