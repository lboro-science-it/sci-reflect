<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="text-center">
                <round-add :rounds="rounds"></round-add>
            </div>

            <div class="row">
                <div class="col-xs-3">
                    <rounds-list :rounds="rounds"
                                 v-on:activate-round="activateRound"></rounds-list>
                </div>
                <div class="col-xs-9">
                    <h4>Edit...</h4>
                    <label for="title">Title</label>
                    <input id="title" type="text" v-model="editRound.title" placeholder="Title"><br><br>

                    <label for="format">Format</label>
                    <select id="format">
                        <option value="linear">Linear</option>
                        <option value="nonlinear">NonLinear</option>
                    </select>

                    <label for="openDate">Open Date</label>
                    <input id="openDate" type="datetime-local">

                    <label for="closeDate">Close Date</label>
                    <input id="closeDate" type="datetime-local">


                    Round Number: {{ editRound.round_number }}<br>
                    Format: {{ editRound.format }}
                </div>
            </div>

            list rounds -> with an edit button or an 'active' which causes another div to be visible<br>
            edit round<br>
            reorder round<br>
        </div>
    </div>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                activeRoundIndex: null,
                editRound: {}
            }
        },

        props: [
            'blocks',
            'pages',
            'rounds',
            'skills'
        ],

        methods: {
            // clone the activated round into the editRound object
            activateRound(index) {
                this.activeRoundIndex = index;
                this.editRound = JSON.parse(JSON.stringify(this.rounds[index]));
            }
        }
    }

</script>