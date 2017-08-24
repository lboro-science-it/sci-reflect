<template>
    <div class="list-group-item" role="button">
         <span class="glyphicon glyphicon-move"></span>
         
         <span v-show="!editTitle">
             {{ page.page_number }}: {{ page.title }}
         </span>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                editTitle: false
            }
        },

        props: [
            'page',
            'index'
        ]
    }

</script>
