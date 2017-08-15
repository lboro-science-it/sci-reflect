<template>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Add groups</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <textarea name="groups"
                                  v-model="groups"
                                  style="width: 100%;"
                                  rows="5"
                                  placeholder="Add a bunch of groups to the activity by typing here, on a new line for each."></textarea>
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
                groups: '',
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
                axios.post(this.postUrl, {
                    groups: this.groups
                }).then(response => {
                    this.$parent.$emit('groups-added', response.data);
                    this.saving = false;
                    this.saveText = 'Save';
                    this.groups = '';
                });
            }
        }
    }
</script>
