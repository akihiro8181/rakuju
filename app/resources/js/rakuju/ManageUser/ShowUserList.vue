<template>
    <rak-base-table>



        <template #actions>
            <jet-button @click.native="showAddUserModal">
                ユーザ追加
            </jet-button>
            <jet-danger-button @click.native="showDeleteUserModal">
                ユーザー削除
            </jet-danger-button>
        </template>

        <template #header>
            <div class="col-span-5">
                <select v-model="search_column" id="search_column">
                    <option value="name" selected> 名前 </option>
                    <option value="roll_flag"> 役職 </option>
                    <option value="login_number"> ログインナンバー</option>
                </select>
                <jet-input id="search_word" type="text" class="mt-1 block w-full" v-model="search_word" autocomplete="search_word" placeholder="検索" />
            </div>
            
        </template>

        <template #table>
            <div class="col-span-5">
                <thead>
                <tr>
                    
                    <th class="py-4 px-6 bg-grey-lightest font-bold text-sm text-grey-dark border-b border-grey-light " v-for="(value,key) in columns" :key="key">
                        {{value}}
                    </th>
                </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-lighter hover:bg-blue-300" v-for="(user,key) in getList" :key="key">
                        <td class="py-4 px-6 border-b border-grey-light " v-for="(value,key) in columns" :key="key">
                            <!-- {{ user[key] }} -->
                            {{ user[key] }}
                            
                        </td>
                    </tr>
                </tbody>
            </div>
 
        </template>

        <template #paginate>
            <div class="col-span-5">
                <paginate
                    :page-count="getPageCount"
                    :page-range="5"
                    :margin-pages="2"
                    :click-handler="clickCallback"
                    :prev-text="'＜'"
                    :next-text="'＞'"
                    :container-class="'relative z-0 inline-flex shadow-sm -space-x-px px-6'"
                    :page-class="'page-item'"
                    
                    :page-link-class="'relative inline-flex items-center px-4 py-2 border border-gray-300  text-sm font-medium text-gray-700 hover:bg-blue-300'"
                    :prev-link-class="'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-blue-300'"
                    :next-link-class="'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-blue-300'"
                    :break-view-link-class="'relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700'"
                    :active-class="'relative inline-flex items-center md:inline-flex bg-gray-300 text-sm font-medium text-gray-700'"
                    :hide-prev-next="true">
                </paginate>
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
    import Paginate from 'vuejs-paginate';


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
            Paginate,
        },

        computed:{

            searchUserList:function(){
                this.user_list = this.users;
                if(this.search_column === "name"){
                    return this.user_list.filter(user => {
                        return user.name.includes(this.search_word)
                　  });
                }else if(this.search_column === "roll_flag"){
                    return this.user_list.filter(user => {
                        return user.roll_flag.includes(this.search_word)
                　  });
                }else{
                    console.log(this.search_column);
                    return this.user_list.filter(user => {
                        console.log(user);
                        console.log(user.login_number);
                        let login_number = String(user.login_number);
                        return login_number.includes(this.search_word)
                　  });
                }
            },

            getList:function(){
                let current = this.current_page * this.par_page;
                let start = current - this.par_page;
                return this.searchUserList.slice(start, current);
            },

            getPageCount: function() {
                return Math.ceil(this.searchUserList.length / this.par_page);
            }
        },

        watch:{
            search_word: function(){
                 this.current_page = 1;
            },
            search_column:function(){
                this.search_word = '';
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

                search_column:'name',
                search_word:'',

                // ******** ペジネイト関連 ********
                // last_page:'', //最終ページ
                current_page:1, // 現在のページ
                par_page:5, // 表示ページ数

                // ******** ペジネイト関連 ********

                columns:columns,

                user_list:[],

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
            
            clickCallback: function (pageNum) {
                this.current_page = Number(pageNum);
            },
            

        },

        created() {

        },

    }
</script>
