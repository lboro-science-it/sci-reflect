<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                Rate {{ studentName }}
            </h3>

        </div>

        <div class="panel-body text-center">
            <h3>{{ skill.title }}</h3>
            <p style="padding-bottom: 20px;">{{ skill.description }}</p>
            <div v-for="choice in choices" :style="buttonSpacer">
                <button class="btn btn-lg btn-success"
                        v-on:click="updateRating(choice.value)">
                    {{ choice.label }}
                </button>
            </div>
        </div>

        <div class="panel-footer text-center" v-show="saveBtn">
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