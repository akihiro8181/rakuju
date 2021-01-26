<template>

    <rak-modal-form :formName="updateModalFormName" :show="ModalFlag" @close="closeUpdateUserModal()" @submitted="updateUser">
        <template #title>
            ユーザー情報の編集
        </template>

        <template #content>
            
        </template>
        
        <template #form id="modalForm">

            <!-- userName -->
            <div class="col-span-5">
                <jet-label for="name" value="ユーザ名" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                <jet-input-error :message="form.error('name')" class="mt-2" />
            </div>

            <!-- rollFlag -->
            <div class="col-span-5">
                <jet-label for="roll_flag" value="役職" />
                <select v-model="form.roll_flag" id="roll_flag">
                    <option value="ad"> 管理者 </option>
                    <option value="te"> 教員 </option>
                    <option value="st"> 生徒 </option>
                </select>
                <!-- <jet-input id="roll_flag" type="text" class="mt-1 block w-full" v-model="form.roll_flag" /> -->
                <!-- <jet-input-error :message="form.error('roll_flag')" class="mt-2" /> -->
            </div>


            <!-- loginNumber-->
            <div class="col-span-5">
                <jet-label for="login_number" value="ログインナンバー" />
                <jet-input id="login_number" type="text" class="mt-1 block w-full" v-model="form.login_number" />
                <jet-input-error :message="form.error('login_number')" class="mt-2" />
            </div>

            <!-- loginNumber-->
            <div class="col-span-5">
                <jet-label for="password" value="パスワード" />
                <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" />
                <jet-input-error :message="form.error('password')" class="mt-2" />
            </div>
            
        </template>


        <template #footer>
            <jet-secondary-button @click.native="closeUpdateUserModal()">
                閉じる
            </jet-secondary-button>

            <jet-button class="ml-2" form="updateModalForm">
                登録
            </jet-button>
        </template>

    </rak-modal-form>
</template>

<script>
    import JetButton from './../../Jetstream/Button'
    import JetDangerButton from './../../Jetstream/DangerButton'
    import JetSecondaryButton from './../../Jetstream/SecondaryButton'
    import JetInput from './../../Jetstream/Input'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'
    import JetActionMessage from './../../Jetstream/ActionMessage'
    import RakModalForm from './../../rakuju/BaseComponent/ModalForm'


    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetDangerButton,
            JetSecondaryButton,
            JetInput,
            JetInputError,
            JetLabel,
            RakModalForm,
        },

        computed: {
            ModalFlag: function () {
                if(this.update_users === undefined){
                    // form 初期化
                    this.form.name = '';
                    this.form.roll_flag = '';
                    this.form.login_number = '';
                    this.form.password = '';
                }else{
                    this.form.name = this.update_users.name;
                    this.form.roll_flag = this.update_users.roll_flag;
                    this.form.login_number = this.update_users.login_number;
                    // console.log(this.form.login_number);
                }
                console.log(this.form.name);
                // `this` は vm インスタンスを指します
               return this.update_modal_show_flag = this.show
            },

        },

        props:['show','update_users'],

        data() {
            return {
                updateModalFormName:"updateModalForm",
                update_modal_show_flag: false,
                delete_modal_show_flag: false,


                form: this.$inertia.form({
                    '_method': 'PUT',
                    name:'',
                    roll_flag:'',
                    login_number:'',
                    password:'',
                    // name:this.UpdateUsers.name,
                    // roll_flag:this.UpdateUsers.roll_flag,
                    // login_number:this.UpdateUsers.login_number,
                    // password:this.UpdateUsers.password,
                }, {
                    bag: 'updateUser',
                    resetOnSuccess: false,
                }),

            }
        },

        methods: {

            showUpdateUserModal() {
                // this.#emit('add')
                return this.update_modal_show_flag = true;
            },
            
            closeUpdateUserModal(){
                this.$emit('close');
                // return this.add_modal_show_flag = false;
            },
            updateUser() {
                console.log("updateUser Start");
                console.log(this.form.name);
                this.form.put('/api/update-user/' + this.update_users.id, {
                    preserveScroll: true
                });
                this.closeUpdateUserModal();
                console.log("updateUser End");
            }
        },

        created() {
            console.log(this.update_users);
        },

    }
</script>

                