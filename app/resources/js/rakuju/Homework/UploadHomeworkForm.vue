<template>
    <div>
        <form @submit.prevent="uploadHomework" enctype="multipart/form-data">
            <div class="shadow overflow-hidden">
                <div @dragenter="dragEnter"
                            @dragleave="dragLeave"
                            @drop.prevent="dropFile" 
                            @dragover.prevent>
                    <!-- <div class="px-4 py-5 bg-white pointer-events-none" :class="[{'border-blue-300 border-4': isFileEnter}]"> -->
                    <div class="px-4 py-10 bg-white box-border text-gray-400 text-center" :class="[{'border-blue-300 border-4': isFileEnter}]">
                        提出するファイルをここにドラッグアンドドロップしてください
                    </div>
                </div>

                <div class="flex items-center justify-between space-x-4 px-2 py-3 bg-gray-50 text-right">
                    <!-- Input Homework -->
                    <input type="file"
                                ref="homework"
                                @change="uploadHomeworkPreview"
                                hidden
                                multiple>
                    <div>
                        提出課題名：<span v-for="file in uploadFiles" :key="'uploadFiles_'+file.name">{{file.name}}, </span>
                    </div>
                    <jet-button @click.native.prevent="selectFileDialog">
                        参照
                    </jet-button>
                </div>
            </div>
            <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
                <jet-action-message :on="sending" class="mr-3">
                    Uploading…
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': sending || !isSetUploadFiles }" :disabled="sending || !isSetUploadFiles">
                    Upload
                </jet-button>
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

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
        },

        props: ['classwork_task_id'],

        data() {
            return {
                sending: false,
                isFileEnter: false,
                uploadFiles: [],
            }
        },

        methods: {
            // アップロードボタン
            uploadHomework() {
                var data = new FormData()
                var data_count = 0

                if (this.uploadFiles.length > 0) {
                    this.sending = true
                    this.uploadFiles.forEach(file => {
                        data.append('files[' + data_count + ']', file)
                        data_count++
                    });

                    this.$inertia.post('/api/homework/' + this.classwork_task_id, data)
                    .then(() => {
                        this.uploadFiles = []
                        this.sending = false
                    })
                }
            },

            uploadHomeworkPreview() {
                if (this.$refs.homework) {
                    for( var i = 0; i < this.$refs.homework.files.length; i++ ){
                        let file = this.$refs.homework.files[i]
                        this.uploadFiles.push(file)
                    }
                }
            },

            dragEnter() {
                this.isFileEnter = true;
            },

            dragLeave() {
                this.isFileEnter = false;
            },
            
            dropFile() {
                let dragFiles = [...event.dataTransfer.files];
                dragFiles.forEach(file => {
                    this.uploadFiles.push(file);
                });
                this.isFileEnter = false;
            },

            selectFileDialog(){
                this.$refs.homework.click();
            },
        },

        computed: {
            isSetUploadFiles() {
                return this.uploadFiles[0] != null;
            },
        }
    }
</script>
