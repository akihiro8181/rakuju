<template>
    <div>
        <form @submit.prevent="uploadHomework">
            <div class="shadow overflow-hidden">
                <div @dragenter="dragEnter"
                            @dragleave="dragLeave"
                            @drop.prevent="dropFile" 
                            @dragover.prevent>
                    <!-- <div class="px-4 py-5 bg-white pointer-events-none" :class="[{'border-blue-300 border-4': isFileEnter}]"> -->
                    <div class="px-4 py-5 bg-white" :class="[{'border-blue-300 border-4': isFileEnter}]">
                            <!-- Input Homework -->
                            <input type="file"
                                        ref="homework"
                                        @change="uploadHomeworkPreview"
                                        multiple>

                            <jet-input-error :message="form.error('homework')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <jet-action-message :on="sending" class="mr-3">
                        Uploading…
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': sending || !homeworkPreview }" :disabled="sending || !homeworkPreview">
                        Upload
                    </jet-button>
                </div>
            </div>
        </form>
    </div>
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
                isFileEnter: false,
                dragFiles: [],
            }
        },

        methods: {
            // アップロードボタン
            uploadHomework() {
                var data = new FormData()

                if (this.$refs.homework) {
                    this.sending = true
                    for( var i = 0; i < this.$refs.homework.files.length; i++ ){
                        let file = this.$refs.homework.files[i]
                        console.log(file)
                        data.append('files[' + i + ']', file)
                    }

                }

                if (this.dragFiles.length > 0) {
                    this.sending = true
                    this.dragFiles.forEach(file => {
                        console.log(file)
                        data.append('files[' + i + ']', file)
                    });
                }

                this.$inertia.post('/api/homework/' + this.classwork_task_id, data)
                .then(() => {
                    this.sending = false
                })
            },

            uploadHomeworkPreview() {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.homeworkPreview = e.target.result;
                    
                };

                reader.readAsDataURL(this.$refs.homework.files[0]);
            },

            dragEnter() {
                this.isFileEnter = true;
            },

            dragLeave() {
                this.isFileEnter = false;
            },
            
            dropFile() {
                this.dragFiles = [...event.dataTransfer.files];
                this.homeworkPreview = this.dragFiles;
                this.isFileEnter = false;
            },
        },
    }
</script>
