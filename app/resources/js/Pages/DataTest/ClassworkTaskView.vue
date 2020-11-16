<template>
    <app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <h2 class="p-6 sm:px-20 bg-white font-semibold text-xl text-gray-800 leading-tight">
                        classwork.name: {{$page.in_charge.classwork.name}}
                    </h2>
                    <h2 class="p-6 sm:px-20 bg-white font-semibold text-xl text-gray-800 leading-tight">
                        classwork.teacher.name: {{$page.in_charge.teacher.name}}
                    </h2>
                    <h2 class="p-6 sm:px-20 bg-white border-b border-gray-200 font-semibold text-xl text-gray-800 leading-tight">
                        classwork.tutors.name: {{$page.in_charge.teacher.name}}<span v-for="tutor in $page.in_charge.tutors" :key="tutor.id">, {{tutor.name}}</span>
                    </h2>
                    <div v-if="$page.in_charge.classwork_tasks == []">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            $page.in_charge.classwork_tasksにデータが存在しません
                        </div>
                    </div>
                    <div v-else v-for="classwork_task in $page.in_charge.classwork_tasks" :key="classwork_task.sort_num">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
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

    export default {
        components: {
            AppLayout,
        },
    }
</script>
