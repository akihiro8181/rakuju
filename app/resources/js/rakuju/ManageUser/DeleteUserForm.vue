<template>

    <rak-modal-form :formName="deleteModalFormName" :show="ModalFlag" @close="closeDeleteUserModal()" @submitted="deleteUser">
        <template #title>
            ユーザーの削除
        </template>

        <template #content>
            <div>
                以下のユーザーを削除しますか？
                <div class="flex flex-col py-3">
                    <div class="flex-auto py-0.5">
                        ユーザ名：{{delete_user.name}}
                    </div>
                    <div class="flex-auto py-0.5">
                        ログインナンバー：{{delete_user.login_number}}
                    </div>
                </div>
            </div>
        </template>
        
        <template #form id="modalForm">

            <!-- userName -->
            <!-- <div class="col-span-5">
                <jet-label for="name" value="ユーザ名" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                <jet-input-error :message="form.error('name')" class="mt-2" />
            </div> -->

            <!-- rollFlag -->
            <!-- <div class="col-span-5">
                <jet-label for="roll_flag" value="役職" />
                <select v-model="form.roll_flag" id="roll_flag">
                    <option value="ad"> 管理者 </option>
                    <option value="te"> 教員 </option>
                    <option value="st"> 生徒 </option>
                </select> -->
                <!-- <jet-input id="roll_flag" type="text" class="mt-1 block w-full" v-model="form.roll_flag" /> -->
                <!-- <jet-input-error :message="form.error('roll_flag')" class="mt-2" /> -->
            <!-- </div> -->


            <!-- loginNumber-->
            <!-- <div class="col-span-5">
                <jet-label for="login_number" value="ログインナンバー" />
                <jet-input id="login_number" type="text" class="mt-1 block w-full" v-model="form.login_number" />
                <jet-input-error :message="form.error('login_number')" class="mt-2" />
            </div> -->

            <!-- loginNumber-->
            <!-- <div class="col-span-5">
                <jet-label for="password" value="パスワード" />
                <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" />
                <jet-input-error :message="form.error('password')" class="mt-2" />
            </div> -->
            
        </template>


        <template #footer>
            <jet-secondary-button @click.native="closeDeleteUserModal()">
                閉じる
            </jet-secondary-button>

            <jet-danger-button class="ml-2" @click.native="deleteUser()" form="deleteModalForm">
                削除
            </jet-danger-button>

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
                if(this.delete_users !== undefined){
                    this.delete_user = this.delete_users;
                }
                // console.log(this.form.name);
                // `this` は vm インスタンスを指します
               return this.delete_modal_show_flag = this.show
            },

        },

        props:['show','delete_users'],

        data() {
            return {
                deleteModalFormName:"deleteModalForm",
                update_modal_show_flag: false,
                delete_modal_show_flag: false,

                delete_user:'',

                form: this.$inertia.form({
                    '_method': 'DELETE',
                }, {
                    bag: 'deleteUser',
                    resetOnSuccess: false,
                }),

            }
        },

        methods: {

            showDeleteUserModal() {
                // this.#emit('add')
                return this.delete_modal_show_flag = true;
            },
            
            closeDeleteUserModal(){

                // form 初期化
                this.delete_user = '';
                
                this.$emit('close');
                // return this.add_modal_show_flag = false;
            },
            deleteUser() {
                console.log("deleteUser Start");
                // console.log(this.form.name);
                
                this.form.delete('/api/delete-user/' + this.delete_user.id, {
                    preserveScroll: true
                });
                this.closeDeleteUserModal();
                console.log("deteleUser End");
            }
        },

        created() {
            console.log(this.delete_users);
        },

    }
</script>
