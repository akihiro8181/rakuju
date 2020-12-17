<template>
    <app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div>
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <h1 class="text-4xl">{{$page.classwork_name}}</h1>
                            <h2 class="text-xl">{{$page.classwork_task.name}}</h2>
                            <div class="p-2 px-5 bg-gray-200 border border-black">
                                評価：{{ "一部提出済み" }}
                            </div>
                            <div class="p-2 px-5 bg-gray-200 border border-black">
                                提出状況：<span v-for="file_name in $page.files" :key="file_name+'0'">{{file_name}}, </span>
                            </div>
                            <div class="p-2 px-5 bg-gray-200 border border-black">
                                最終提出日：{{"YYYY/MM/DD HH:mm"}}
                            </div>
                            <div class="mt-6 p-2 px-5 bg-gray-200 border border-black">
                                該当課題：<span v-for="file_name in parseFileNames($page.file_name[0])" :key="file_name+'1'">{{file_name}}, </span>
                            </div>
                            <div class="p-2 px-5 bg-gray-200 border border-black">
                                課題提出期限日：{{"YYYY/MM/DD HH:mm"}}
                            </div>
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

