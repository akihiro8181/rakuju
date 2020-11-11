<template>
    <app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <h2 class="p-6 sm:px-20 bg-white border-b border-gray-200 font-semibold text-xl text-gray-800 leading-tight">
                        classwork.name: {{$page.in_charge.classwork.name}}
                    </h2>
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div class="text-2xl">
                            classwork: {{$page.in_charge.classwork}}
                        </div>
                        <div class="mt-6 text-gray-500">
                            classwork_tasks: {{$page.in_charge.classwork_tasks}}

                            <p>見出し(name)</p>
                            <p>説明(json.text, json.sortnum)</p>
                            <p>リンク(json.text, json.sortnum)(とりあえず)</p>
                            <p>リンク(json.text, json.sortnum)(とりあえず)</p>
                            <p>説明(json.text, json.sortnum)</p>
                        </div>
                    </div>
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
