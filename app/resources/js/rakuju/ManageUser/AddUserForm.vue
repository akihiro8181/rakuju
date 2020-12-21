<template>

    <rak-modal-form :formName="addModalFormName" :show="ModalFlag" @close="closeAddUserModal()" @submitted="addUser">
        <template #title>
            ユーザーの追加
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
            <jet-secondary-button @click.native="closeAddUserModal()">
                閉じる
            </jet-secondary-button>

            <jet-button class="ml-2" form="addModalForm">
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
                // `this` は vm インスタンスを指します
               return this.add_modal_show_flag = this.show
            }

        },

        props:['show'],

        data() {
            return {
                addModalFormName:"addModalForm",
                add_modal_show_flag: false,
                delete_modal_show_flag: false,


                form: this.$inertia.form({
                    '_method': 'POST',
                    name: this.name,
                    roll_flag: this.roll_flag,
                    login_number: this.login_number,
                    password:this.password,
                }, {
                    bag: 'addUser',
                    resetOnSuccess: false,
                }),

            }
        },

        methods: {

            showAddUserModal() {
                // this.#emit('add')

                return this.add_modal_show_flag = true;
            },
            
            closeAddUserModal(){
                this.$emit('close');
                // return this.add_modal_show_flag = false;
            },
            addUser() {
                console.log("addUser Start");
                // console.log(this.form);
                this.form.post('/api/add-user/', {
                    preserveScroll: true
                });
                this.closeAddUserModal();
                console.log("addUser End");
            }
        },

    }
</script>

                