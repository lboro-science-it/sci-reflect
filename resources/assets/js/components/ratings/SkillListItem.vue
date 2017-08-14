<template>
    <div class="list-group-item" role="button" v-on:click="activate">
        <span class="glyphicon glyphicon-chevron-right" 
              aria-hidden="true"
              v-show="active">
        </span>
        
        {{ skill.title }}
        <span v-show="skill.rating !== null">({{ skill.rating }})</span>
        
        <span class="glyphicon glyphicon-ok pull-right" 
              aria-hidden="true"
              v-show="skill.rating !== null">
        </span>
    </div>
</template>

<script>

export default {
    props: [
        'active',
        'skill'
    ],

    methods: {
        activate() {
            this.$emit('activate');
        }
    }
}

</script>