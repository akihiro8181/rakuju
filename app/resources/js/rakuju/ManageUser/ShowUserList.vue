<template>
    <rak-base-table>
        <template #header>
            <div class="col-span-5">
                検索
            </div>
            
        </template>

        <template #actions>
            <jet-button @click.native="showAddUserModal">
                ユーザ追加
            </jet-button>
            <jet-danger-button @click.native="showDeleteUserModal">
                ユーザー削除
            </jet-danger-button>
        </template>

        <template #table>
            <div class="col-span-5">
                <thead>
                <tr>
                    <th class="py-4 px-6 bg-grey-lightest font-bold text-sm text-grey-dark border-b border-grey-light" v-for="(value,key) in columns" :key="key">
                        {{value}}
                    </th>
                </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-lighter" v-for="(user,key) in originalUserList" :key="key">
                        <td class="py-4 px-6 border-b border-grey-light " v-for="(value,key) in columns" :key="key">
                            <!-- {{ user[key] }} -->
                            {{ user[key] }}
                            
                        </td>
                    </tr>
                </tbody>
            </div>
        </template>

        <template #add_modal>
            <add-user-form :show="add_modal_show_flag" @close='showAddUserModal()' />
        </template>


        <template #delete_modal>
            <rak-modal-form :show="delete_modal_show_flag" @close="delete_modal_show_flag = false">
                <template #title>
                    Delete User
                </template>


                <template #footer>

                    <jet-secondary-button @click.native="delete_modal_show_flag = false">
                        いいえ
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2" @click.native="delete_modal_show_flag = false">
                        はい
                    </jet-danger-button>
                </template>
            </rak-modal-form>
        </template>
        

    </rak-base-table>
    

</template>

<script>
    import JetButton from './../../Jetstream/Button'
    import JetDangerButton from './../../Jetstream/DangerButton'
    import JetSecondaryButton from './../../Jetstream/SecondaryButton'
    import JetInput from './../../Jetstream/Input'
    import RakBaseTable from './../../rakuju/BaseComponent/BaseTable'
    import RakModalForm from './../../rakuju/BaseComponent/ModalForm'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'
    import JetActionMessage from './../../Jetstream/ActionMessage'
    import JetDialogModal from './../../Jetstream/DialogModal'
    import AddUserForm from '../../rakuju/ManageUser/AddUserForm'


    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetDangerButton,
            JetSecondaryButton,
            RakBaseTable,
            RakModalForm,
            JetInput,
            JetInputError,
            JetLabel,
            JetDialogModal,
            AddUserForm,
        },

        computed:{
            originalUserList:function(){
                // this.user_list = this.users
                return this.users;
            }
        },

        props:{
            users:Array,
        },

        mounted(){
            // this.user_list = this.users;
        },

        data() {
            
            var columns = {
                name:'名前',
                roll_flag:'役職',
                login_number:'ログインナンバー',
            };

            return {
                columns:columns,

                user_list:this.users,

                add_modal_show_flag: false,
                delete_modal_show_flag: false,

            }
        },

        methods: {

            showAddUserModal() {
                this.add_modal_show_flag = this.add_modal_show_flag == true ? false : true
            },

            showDeleteUserModal() {
                this.delete_modal_show_flag = true
            },


        },

    }
</script>
