<template>
    <div class="form-group">
        <label class="col-xs-2 control-label">
            <span class="glyphicon glyphicon-move"></span>
            Skill
        </label>
        <div class="col-xs-10">
            {{ item.title }}: {{ item.description }}
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {

            }
        },

        props: [
            'item'
        ]
    }

</script>