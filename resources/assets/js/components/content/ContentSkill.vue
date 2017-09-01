<template>
    <div class="form-group">
        <label class="col-xs-3 control-label">
            <span class="glyphicon glyphicon-move"></span>
            Skill
        </label>
        <div class="col-xs-9">
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