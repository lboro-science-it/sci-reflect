<template>
    <tr>
        <td><p v-if="staffMember.groupName">{{ staffMember.groupName }}</p><p v-else>select</p></td>
        <td>{{ staffMember.name }}</td>
        <td>{{ staffMember.email }}</td>
        <td class="todo"><p v-if="staffMember.hasAccessed">Yes</p><p v-else>No</p></td>
        <td class="todo"><p v-if="staffMember.hasAccessed">{{ staffMember.lastAccessed }}</p></td>
    </tr>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {

            }
        },

        props: [
            'staffMember'
        ],

        methods: {

        },

        mounted () {

        }
    }
</script>
