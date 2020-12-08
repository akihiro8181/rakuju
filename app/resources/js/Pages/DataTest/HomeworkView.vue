<template>
    <app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div>
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            {{$page.files}}<br>
                            ---<br>
                            <div>ファイル名：<span v-for="file_name in parseFileNames($page.file_name[0])" :key="file_name">{{file_name}}, </span></div>
                                                
                        </div>
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <rak-upload-homework-form :classwork_task_id="$page.classwork_task.id"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    import RakUploadHomeworkForm from './../../rakuju/Homework/UploadHomeworkForm'

    export default {
        components: {
            AppLayout,
            RakUploadHomeworkForm,
        },

        methods: {
            // ファイル名を配列に直す
            parseFileNames(file_name) {
                return file_name.split('|');
            },
        }
    }
</script>

