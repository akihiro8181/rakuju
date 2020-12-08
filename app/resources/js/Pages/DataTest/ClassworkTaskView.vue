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
                        <div v-if="$page.user.roll_flag == 'te' || $page.user.roll_flag == 'ad'">
                            <jet-button @click.native="showCreateForm()" class="text-lg">
                                コンテンツ<br>追加
                            </jet-button>
                        </div>
                    </div>
                    <div v-if="show_create_form" class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <create-task-form :in_charge_id="$page.in_charge.id" />
                    </div>
                    <div v-if="$page.in_charge.classwork_tasks == []">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            $page.in_charge.classwork_tasksにデータが存在しません
                        </div>
                    </div>
                    <div v-else v-for="classwork_task in $page.in_charge.classwork_tasks" :key="classwork_task.sort_num">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <div v-if="$page.user.roll_flag == 'te' || $page.user.roll_flag == 'ad'" class="flex space-x-4">
                                <jet-button @click.native="showUpdateForm(classwork_task.id)">
                                    ⚙
                                </jet-button>
                                <jet-danger-button @click.native="confirmClassworkTaskDeletion(classwork_task.id)">
                                    削除
                                </jet-danger-button>
                            </div>
                            <div v-if="show_update_form == classwork_task.id">
                                <update-task-form :task="classwork_task" />
                            </div>
                            <div v-else>
                                <h3 class="bg-white font-semibold text-xl text-gray-800 leading-tight">
                                    content.name:{{classwork_task.name}}
                                </h3>
                                <div class="mt-6 text-gray-500">
                                    <div v-for="content in classwork_task.contents" :key="content.sort_num">
                                        <div v-if="content.type == 'text'">
                                            {{content.text}}
                                        </div>
                                        <div v-else-if="content.type == 'link'">
                                            <a :href="content.url" class="underline text-blue-600 visited:text-purple-600">
                                                {{content.text}}
                                            </a>
                                        </div>
                                        <div v-else-if="content.type == 'homework'">
                                            <!-- <div>ファイル名：<span v-for="file_name in parseFileNames(contents.file_name)" :key="file_name">{{file_name}}, </span></div> -->
                                            <inertia-link :href="'/homework/' + classwork_task.id">提出ページへ</inertia-link>
                                            <!-- <a :href="route('homework') + '/' + classwork_task.id" class="underline text-blue-600 visited:text-purple-600"> -->
                                                <!-- 提出ページへ -->
                                            <!-- </a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-800">
                        $page.incharge: {{$page.in_charge}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Account Confirmation Modal -->
        <jet-dialog-modal :show="confirmingClassworkTaskDeletion" @close="confirmingClassworkTaskDeletion = false">
            <template #title>
                Delete Account
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
    </app-layout>
</template>

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
                show_create_form: false,
                show_update_form: 0,

                confirmingClassworkTaskDeletion: false,
                deleting: false,

                form: this.$inertia.form({
                    '_method': 'DELETE',
                    classwork_task_id: -1,
                }, {
                    bag: 'deleteClassworkTask'
                })
            }
        },

        methods: {
            showUpdateForm(index) {
                this.show_update_form = this.show_update_form != index ? index : 0;
            }, 

            showCreateForm() {
                this.show_create_form = !this.show_create_form;
            }, 

            confirmClassworkTaskDeletion(index) {
                this.form.classwork_task_id = index;

                this.confirmingClassworkTaskDeletion = true;
            },

            deleteClassworkTask() {
                this.form.delete('/api/classwork-task/' + this.form.classwork_task_id, {
                    preserveScroll: true
                }).then(response => {
                    if (! this.form.hasErrors()) {
                        this.confirmingUserDeletion = false;
                    }
                })
            },

            // ファイル名を配列に直す
            parseFileNames(file_name) {
                return file_name.split('|');
            },
        },
    }
</script>
