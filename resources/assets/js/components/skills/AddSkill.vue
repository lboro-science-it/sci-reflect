<template>
    <!-- Add / Create Skills -->
    <div class="form-group">
        <div class="col-xs-offset-2 col-xs-4">
            <select v-model="addSkillId" class="form-control input-lg">
                <option value="null">Select skill to add</option>
                <option v-for="skill in skills" :value="skill.id">{{ skill.title }}</option>
            </select>
        </div>
        <div class="col-xs-6">
            <button class="btn btn-lg" :class="{ disabled: addSkillId == 'null' }">Add (todo)</button>
            <button class="btn btn-lg">New (todo)</button>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                addSkillId: "null"
            }
        },

        props: [ 'skills' ]
    }

</script>