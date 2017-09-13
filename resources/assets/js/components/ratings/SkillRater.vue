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
                    {{ choice.label !== '' ? choice.label : '(value: ' + choice.value + ')' }}
                </button>
            </transition>
            <p class="scireflect-tooltip" v-if="getDescriptorText(choice.id)">
                {{ getDescriptorText(choice.id) }}
            </p>
        </div>
    </div>
</template>

<script>
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
        'descriptors',
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

        getDescriptorText(choiceId) {
            // filter this.descriptors, look for the one with choice_id match
            let choiceDescriptor = this.descriptors.filter(descriptor => {
                return descriptor.choice_id == choiceId;
            });

            if (typeof choiceDescriptor[0] === 'undefined') {
                return false;
            }

            return choiceDescriptor[0].text;
        },

        // update the skill in the parent array
        updateRating(value) {
            this.$emit('update-rating', value);
        }
    },

    mounted() {
        console.log(this.descriptors);
    }

}

</script>