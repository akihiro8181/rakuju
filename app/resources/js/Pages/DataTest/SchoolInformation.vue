<template>
    <app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div>
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <div class="text-2xl">
                                学校情報
                            </div>
                            <div v-if="$page.user.roll_flag == 'ad'" class="flex space-x-4">
                                <jet-button @click.native="showUpdateForm">
                                    ⚙
                                </jet-button>
                            </div>

                            <div v-if="show_update_form == true">
                                <update-information-form :school="$page.user.school" />
                                
                            </div>

                            <div v-else>
                                <div>
                                    学校名:{{$page.user.school.name}}
                                </div>

                                <div>
                                ワークスペースURL:{{$page.user.school.workspace_url}}
                                </div>
                            </div>

                        </div>

                        <div>
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                {{$page.school}}<br>
                                ---<br>
                                {{$page.user}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    import JetButton from './../../Jetstream/Button'
    import UpdateInformationForm from '../../rakuju/SchoolInformation/UpdateInformationForm'

    export default {
        components: {
            AppLayout,
            JetButton,
            UpdateInformationForm,
        },
        
        data(){
            return{
                show_update_form: false,
            }
        },
        
        methods: {
            showUpdateForm() {
                this.show_update_form = this.show_update_form == true ? false : true;
            },
        } 
    }
</script>
