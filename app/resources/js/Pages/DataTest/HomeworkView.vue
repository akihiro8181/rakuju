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
                                評価：{{ evaluation_str }}
                            </div>
                            <div class="p-2 px-5 bg-gray-200 border border-black">
                                提出状況：<span v-for="file_name in $page.files" :key="file_name+'0'">{{file_name}}, </span>
                            </div>
                            <div class="p-2 px-5 bg-gray-200 border border-black">
                                最終提出日：{{ $page.last_upload_date }}
                            </div>
                            <div class="mt-6 p-2 px-5 bg-gray-200 border border-black">
                                該当課題：<span v-for="file_name in parseFileNames($page.file_name[0])" :key="file_name+'1'">{{file_name}}, </span>
                            </div>
                            <div class="p-2 px-5 bg-gray-200 border border-black">
                                課題提出期限日：{{$page.deadline != null ? $page.deadline : "設定なし"}}
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
        },

        computed: {
            evaluation_str() {
                const str = ["未提出", "一部提出済み", "提出済み"];
                const upload_files = this.$page.files; // 提出ファイルの名前一覧
                const homework_files = this.$page.file_name[0].split('|');  // 該当課題の名前一覧

                let count = 0;  // 提出済みのファイル数

                // 該当課題と提出ファイルの名前が一致していればカウントを増やす
                homework_files.forEach(homework_name => {
                    upload_files.forEach(upload_name => {
                        if (homework_name == upload_name) {
                            count++;
                        }
                    });
                });
                
                if (count == homework_files.length) {
                    // 全て提出済み
                    return str[2];
                } else if (count > 0) {
                    // 一部提出済み
                    return str[1];
                } else {
                    // 未提出
                    return str[0];
                }
            },
        }
    }
</script>

