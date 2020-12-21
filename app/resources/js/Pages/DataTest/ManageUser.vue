<template>
    <app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div>
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                            <div class="text-2xl">
                                ユーザ管理
                                <!-- {{users}} -->
                            </div>

                            <div>
                                <show-user-list :users='users'/>
                            </div>
                            
                        </div>

                        <div>
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                {{$page.user.school}}<br>
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
    import ShowUserList from '../../rakuju/ManageUser/ShowUserList'

    export default {
        components: {
            AppLayout,
            JetButton,
            ShowUserList,
        },

        props:{
            users: Array,
        },

        data(){
            return{
                // users: this.users,
                
            }
        },
    }
</script>
