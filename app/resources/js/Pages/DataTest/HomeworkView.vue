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
                                <form v-if="$page.files.length > 0" class="flex flex-wrap items-center" enctype="multipart/form-data">
                                    提出状況：
                                    <div v-for="file_name in $page.files" :key="file_name+'0'">
                                        <div class="ml-1">
                                            <a class="fas fa-times-circle delete_ic" @click.prevent="deleteFile(file_name)"></a>
                                            <a :href="'/api/storage/' + $page.classwork_task.id + '/' + file_name + '/'" class="cursor-pointer underline text-blue-600 visited:text-purple-600 ml-4">{{file_name}}</a>, 
                                        </div>
                                    </div>
                                </form>
                                <div v-else>
                                    提出状況：提出ファイルなし
                                </div>
                            </div>
                            <div class="p-2 px-5 bg-gray-200 border border-black">
                                最終提出日：{{ $page.last_upload_date != null ? $page.last_upload_date : "提出ファイルなし" }}
                            </div>
                            <div class="mt-6 p-2 px-5 bg-gray-200 border border-black">
                                <form class="flex flex-wrap items-center" enctype="multipart/form-data">
                                    該当課題：
                                    <div v-for="(file_name, index) in homework_file_name_array" :key="index">
                                        <div class="ml-1">
                                            {{file_name}},
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="p-2 px-5 bg-gray-200 border border-black">
                                <div v-if="$page.deadline">
                                    課題提出期限日：{{$page.deadline | replaceDatetime}}
                                </div>
                                <div v-else>
                                    課題提出期限日：設定なし
                                </div>
                            </div>
                            <rak-upload-homework-form :classwork_task_id="$page.classwork_task.id"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<style>
    .delete_ic{
        color: var(--blue-shadow);
        height: 24px;
        padding: 4px 0;
        position: absolute;
    }
</style>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    import RakUploadHomeworkForm from './../../rakuju/Homework/UploadHomeworkForm'

    export default {
        components: {
            AppLayout,
            RakUploadHomeworkForm,
        },

        data() {
            return {
                downloadForm: this.$inertia.form({
                    '_method': 'POST',
                }, {
                    bag: 'downloadFile',
                }),

                deleteForm: this.$inertia.form({
                    '_method': 'DELETE',
                }, {
                    bag: 'deleteFile',
                }),
            }
        },

        methods: {
            // ファイル名を配列に直す
            parseFileNames(file_name) {
                return file_name.split('|');
            },

            deleteFile(delete_file_name) {
                // 末尾が「~.php」だとページ遷移と誤認識して動かなくなるので「/」を追加する
                this.deleteForm.delete('/api/storage/' + this.$page.classwork_task.id + '/' + delete_file_name + '/');
            }
        },

        filters:{
            // 時刻の文字列のフォーマット
            replaceDatetime(val) {
                return val.replace('T', ' ');   
            }
        },

        computed: {
            homework_file_name_array() {
                const homework_file_name_array = this.$page.file_name;  // 該当課題の名前の配列

                let homework_files = [];    // 該当課題の名前一覧
                homework_file_name_array.forEach(file_name_str => {
                    file_name_str.split('|').forEach(file_name => {
                        homework_files.push(file_name);
                    });
                });
                return homework_files;
            },

            evaluation_str() {
                const str = ["未提出", "一部提出済み", "提出済み"];
                const upload_files = this.$page.files; // 提出ファイルの名前一覧

                const homework_file_name_array = this.$page.file_name;  // 該当課題の名前の配列

                let homework_files = [];    // 該当課題の名前一覧
                homework_file_name_array.forEach(file_name_str => {
                    file_name_str.split('|').forEach(file_name => {
                        homework_files.push(file_name);
                    });
                });

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

