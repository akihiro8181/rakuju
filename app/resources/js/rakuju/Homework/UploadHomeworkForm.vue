<template>
    <rak-base-form @submitted="uploadHomework">
        <template #form>
            <!-- Input Homework -->
            <div class="col-span-6 sm:col-span-4">
                <input type="file"
                            ref="homework"
                            @change="uploadHomeworkPreview"
                            multiple>
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="sending" class="mr-3">
                Uploading…
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': sending || !homeworkPreview }" :disabled="sending || !homeworkPreview">
                Upload
            </jet-button>
        </template>
    </rak-base-form>
</template>

<script>
    import JetButton from './../../Jetstream/Button'
    import JetFormSection from './../../Jetstream/FormSection'
    import JetInput from './../../Jetstream/Input'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'
    import JetActionMessage from './../../Jetstream/ActionMessage'
    import JetSecondaryButton from './../../Jetstream/SecondaryButton'
    import RakBaseForm from './../../rakuju/BaseComponent/BaseForm'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
            RakBaseForm,
        },

        props: ['classwork_task_id'],

        data() {
            return {
                homeworkPreview: null,
                sending: false,
            }
        },

        methods: {
            // アップロードボタン
            uploadHomework() {
                if (this.$refs.homework) {
                    this.sending = true
                    var data = new FormData()
                    for( var i = 0; i < this.$refs.homework.files.length; i++ ){
                        let file = this.$refs.homework.files[i]
                        console.log(file)
                        data.append('files[' + i + ']', file)
                    }

                    this.$inertia.post('/api/homework/' + this.classwork_task_id, data)
                    .then(() => {
                        this.sending = false
                    })
                }
            },

            uploadHomeworkPreview() {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.homeworkPreview = e.target.result;
                };

                reader.readAsDataURL(this.$refs.homework.files[0]);
            },
        },
    }
</script>
