<template>
    <app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white flex items-center space-x-4 justify-between">
                        <div>
                            <h1 class="font-semibold text-2xl text-gray-800 leading-tight">
                                授業名：{{$page.in_charge.classwork.name}}
                            </h1>
                            <div class="mt-2 leading-tight">
                                授業担当者： {{$page.in_charge.teacher.name}}<span v-for="tutor in $page.in_charge.tutors" :key="tutor.id">, {{tutor.name}}</span>
                            </div>
                        </div>
                        <!-- 編集権限のあるユーザーの場合はボタンを表示 -->
                        <div v-if="$page.user.roll_flag == 'te' || $page.user.roll_flag == 'ad'">
                            <jet-button @click.native="showCreateForm()" class="text-lg">
                                コンテンツ<br>追加
                            </jet-button>
                        </div>
                    </div>
                    <!-- 新規作成フォーム -->
                    <div v-if="show_create_form" class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <create-task-form :in_charge_id="$page.in_charge.id" :max_sort_num="maxSortNum" />
                    </div>
                    <!-- コンテンツが１つも追加されていない場合 -->
                    <div v-if="!$page.in_charge.classwork_tasks.length">
                        <div class="work_item">    
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                コンテンツがまだありません
                            </div>
                        </div>
                    </div>
                    <!-- コンテンツが１つ以上ある場合 -->
                    <div v-else>
                        <div v-for="classwork_task in $page.in_charge.classwork_tasks" :key="classwork_task.sort_num">
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                <!-- 編集権限のあるユーザーの場合はボタンを表示 -->
                                <div v-if="$page.user.roll_flag == 'te' || $page.user.roll_flag == 'ad'" class="flex space-x-4">
                                    <jet-button @click.native="showUpdateForm(classwork_task.id)" class="fas fa-cog" />
                                    <jet-danger-button @click.native="confirmClassworkTaskDeletion(classwork_task.id)">
                                        削除
                                    </jet-danger-button>
                                </div>
                                <!-- 編集ボタンを押すとその部分の表示が編集フォームに置き換わる -->
                                <!-- 編集フォーム -->
                                <div v-if="show_update_form == classwork_task.id">
                                    <update-task-form :task="classwork_task" />
                                </div>
                                <!-- コンテンツ情報の表示 -->
                                <div v-else>
                                    <h3 class="bg-white font-semibold text-xl text-gray-800 leading-tight">
                                        {{classwork_task.name}}
                                    </h3>
                                    <ul class="content_list">
                                        <div v-for="content_item in classwork_task.contents" :key="content_item.sort_num">
                                            <!-- typeに合わせて表示する内容を変更 -->
                                            <div v-if="content_item.type == 'title'">
                                                <li class="content_item_title type_title">
                                                    <p>{{content_item.text}}</p>
                                                </li>
                                            </div>
                                            <div v-else-if="content_item.type == 'text'">
                                                <li class="content_item_child type_text">
                                                    <p>{{content_item.text}}</p>
                                                </li>
                                            </div>
                                            <div v-else-if="content_item.type == 'link'">
                                                <a :href="content_item.url" class="content_item_child type_link underline text-blue-600 visited:text-purple-600">
                                                    {{content_item.text}}
                                                </a>
                                            </div>
                                            <div v-else-if="content_item.type == 'homework'">
                                                <!-- 登録されているファイル名の表示 -->
                                                <li class="content_item_child type_link">
                                                    <p>ファイル名：{{content_item.file_name | replaceComma}}</p>
                                                    <inertia-link :href="'/homework/' + classwork_task.id" class=" underline text-blue-600 visited:text-purple-600">提出ページへ</inertia-link>
                                                </li>
                                                <!-- 課題提出ページへのリンク -->
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Account Confirmation Modal -->
        <jet-dialog-modal :show="confirmingClassworkTaskDeletion" @close="confirmingClassworkTaskDeletion = false">
            <template #title>
                contents削除
            </template>

            <template #content>
                この操作は元に戻せません。このタスクを削除してもよろしいですか？
            </template>

            <template #footer>
                <jet-secondary-button @click.native="confirmingClassworkTaskDeletion = false">
                    いいえ
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="deleteClassworkTask" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    はい
                </jet-danger-button>
            </template>
        </jet-dialog-modal>

        <!-- 追加 -->
        <!-- <div id="dialog" class="dialog">
            <div class="dialogBackground"></div>
            <div class="dialogContent">
                    <div class="dialogTitle">
                        contents削除
                    </div>
                    <hr class="dialog_hr">
                    <div>
                        <fieldset>
                            <div class="dialogMsg"></div>
                                <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 dialog_btn" onclick="dialogHide('dialog');">いいえ</button>
                                <button class="dialog_yes inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 dialog_btn" onclick="Delete('dialog')">はい</button>
                        </fieldset>
                    </div>
            </div>
        </div> -->
    </app-layout>
</template>

<style>
    .content_list{
        margin-left: -3px;
        z-index: 0;
    }

    .content_item_child{
        margin-left: 15px;
    }

    .type_title>p{
        border-bottom: solid 2px;
        border-image: linear-gradient(to right, theme('colors.sunrise-glow') 0%, rgb(235, 97, 56,0) 100%);
        border-image-slice: 1;
        /* margin-left: -25px; */
        padding-left: 25px;
    }

    .type_title li{
        border-bottom: solid 2px;
        border-image: linear-gradient(to right, theme('colors.ridge-gray') 0%, rgb(235, 97, 56,0) 100%);
        border-image-slice: 1;
        margin-left: 0px;
        padding-left: 40px;
    }

    .type_text>p{
        padding-left: 25px;
    }

    .type_link>p{
        padding-left: 25px;
    }

    .type_link>a{
        padding-left: 25px;
    }
</style>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    import JetNavLink from './../../Jetstream/NavLink'
    import JetButton from './../../Jetstream/Button'
    import JetDangerButton from './../../Jetstream/DangerButton'
    import JetSecondaryButton from './../../Jetstream/SecondaryButton'
    import JetDialogModal from './../../Jetstream/DialogModal'
    import CreateTaskForm from '../../rakuju/ClassworkTask/CreateTaskForm'
    import UpdateTaskForm from '../../rakuju/ClassworkTask/UpdateTaskForm'

    export default {
        components: {
            AppLayout,
            JetButton,
            JetDangerButton,
            JetSecondaryButton,
            JetDialogModal,
            JetNavLink,
            CreateTaskForm,
            UpdateTaskForm,
        },

        data() {
            return {
                show_create_form: false,    // 新規作成フォームの表示/非表示
                show_update_form: 0,        // 編集フォームが表示されているコンテンツのid（classwork_task.idを格納）

                confirmingClassworkTaskDeletion: false, // 削除モーダルの表示/非表示

                form: this.$inertia.form({
                    '_method': 'DELETE',
                    classwork_task_id: -1,
                }, {
                    bag: 'deleteClassworkTask'
                })
            }
        },

        methods: {
            // 編集フォームの表示切替
            showUpdateForm(index) {
                this.show_update_form = this.show_update_form != index ? index : 0;
            }, 

            // 新規作成フォームの表示切替
            showCreateForm() {
                this.show_create_form = !this.show_create_form;
            }, 

            // 削除モーダルの表示切替
            confirmClassworkTaskDeletion(index) {
                this.form.classwork_task_id = index;
                this.confirmingClassworkTaskDeletion = true;
            },

            // コンテンツ削除
            deleteClassworkTask() {
                this.form.delete('/api/classwork-task/' + this.form.classwork_task_id, {
                    preserveScroll: true
                }).then(response => {
                    // 正常に削除できていれば削除モーダルを閉じる
                    if (! this.form.hasErrors()) {
                        this.confirmingUserDeletion = false;
                    }
                })
            },
        },

        filters:{
            // ファイル名の分割記号をカンマに置き換える
            replaceComma(val) {
                return val.replace('|', ', ');   
            }
        },

        computed:{
            // 現在のコンテンツの中で最大のsort_numを取得
            maxSortNum() {
                let max_sort_num = -1;
                // console.log(this.$inertia.page.props.in_charge.classwork_tasks);
                this.$inertia.page.props.in_charge.classwork_tasks.forEach(task => {
                    if (task.sort_num > max_sort_num) {
                        max_sort_num = task.sort_num;
                    }
                });
                return max_sort_num;
            }
        },
    }
</script>
