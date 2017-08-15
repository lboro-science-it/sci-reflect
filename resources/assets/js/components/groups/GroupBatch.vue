<template>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Batch add groups</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="groupPrefix">Prefix:</label>
                        <input v-model="groupPrefix"
                               type="text"
                               name="groupPrefix"
                               class="form-control"
                               placeholder="Enter group prefix">
                    </div>
                    <div class="form-group">
                        <label for="numberToCreate">Number to create:</label>
                        <input v-model="numberToCreate"
                               type="number" 
                               name="numberToCreate"
                               class="form-control"
                               style="width: 100px;">
                    </div>
                    <button v-on:click="saveGroups"
                            class="btn btn-primary btn-lg pull-right">
                        {{ saveText }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                groupPrefix: '',
                numberToCreate: 0,
                saveText: 'Save',
                saving: false
            }
        },

        props: [
            'postUrl'
        ],

        methods: {
            saveGroups () {      // update the current name and send the request
                this.saving = true;
                this.saveText = 'Saving...';
                if (this.numberToCreate > 0) {
                    axios.post(this.postUrl, {
                        groupPrefix: this.groupPrefix,
                        numberToCreate: this.numberToCreate
                    }).then(response => {
                        this.$parent.$emit('groups-added', response.data);
                        this.saving = false;
                        this.saveText = 'Save';
                        this.groupPrefix = '';
                        this.numberToCreate = 0;
                    });
                }
            }
        }
    }
</script>
