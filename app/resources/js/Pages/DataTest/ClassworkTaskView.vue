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
                        <div v-if="$page.user.roll_flag == 'te'">
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
                            <div v-if="$page.user.roll_flag == 'te'">
                                <jet-button @click.native="showUpdateForm(classwork_task.id)">
                                    ⚙
                                </jet-button>
                            </div>
                            <div v-if="show_update_form == classwork_task.id">
                                <update-task-form :task_id="classwork_task.id" :in_charge_id="$page.in_charge.id"/>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <h2 class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        $page.incharge: {{$page.in_charge}}
                    </h2>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    import JetNavLink from './../../Jetstream/NavLink'
    import JetButton from './../../Jetstream/Button'
    import CreateTaskForm from '../../rakuju/ClassworkTask/CreateTaskForm'
    import UpdateTaskForm from '../../rakuju/ClassworkTask/UpdateTaskForm'

    export default {
        components: {
            AppLayout,
            JetButton,
            JetNavLink,
            CreateTaskForm,
            UpdateTaskForm,
        },

        data() {
            return {
                show_create_form: false,
                show_update_form: 0,
            }
        },

        methods: {
            showUpdateForm(index) {
                this.show_update_form = this.show_update_form != index ? index : 0;
            }, 

            showCreateForm() {
                this.show_create_form = !this.show_create_form;
            }, 
        },
    }
</script>
