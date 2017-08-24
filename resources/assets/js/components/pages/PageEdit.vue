<template>
    <div>This is the page edit component. Page we would be editing is {{ title }}</div>
</template>

<script>

    export default {
        data() {
            return {

            }
        },

        computed: {
            title() {
                if (typeof this.page !== 'undefined') {
                    return this.page.title;
                }

                return '<No Page Set>';
            }
        },

        props: [
            'page'
        ]
    }

</script>