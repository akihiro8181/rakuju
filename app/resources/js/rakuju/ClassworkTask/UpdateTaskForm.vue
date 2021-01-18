<template>
    <rak-base-form @submitted="updateClassworkTask">
        <template #form>
            <!-- Name -->
            <div>
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" placeholder="見出し：例)第1週　ゼミ"/>
                <jet-input-error :message="form.error('name')" class="mt-2" />
            </div>

            <!-- Contents -->
            <div>
                <jet-label for="contents" value="Contents" />
            </div>

            <!-- 変更追加 -->
            <div>
                <ul class="content_list">
                    <draggable v-model="contents" @start="drag=true" @end="drag=false">
                        <div v-for="(content_item, index) in contents" :key="content_item.sort_num">
                            <a class="fas fa-times-circle delete_ic" @click.prevent="deleteContentsItem(index)"></a>
                            <!-- typeに合わせて表示する内容を変更 -->
                            <div v-if="content_item.type == 'title'">
                                <li class="content_item_title type_title">
                                    <p>{{content_item.text}}</p>
                                </li>
                            </div>
                            <div v-else-if="content_item.type == 'text'">
                                <li class="content_item_child type_text">
                                    <p>{{content_item.text}}</p>
                                </li>
                            </div>
                            <div v-else-if="content_item.type == 'link'">
                                <li class="content_item_child type_link">
                                    <a :href="content_item.url" class="underline text-blue-600 visited:text-purple-600">
                                        {{content_item.text}}
                                    </a>
                                </li>
                            </div>
                            <div v-else-if="content_item.type == 'homework'">
                                <!-- 登録されているファイル名の表示 -->
                                <li class="content_item_child type_link">
                                    <p>課題ファイル名：{{content_item.file_name | replaceComma}}</p>
                                </li>
                            </div>
                        </div>
                    </draggable>
                </ul>
            </div>

            <!-- 追加 -->
            <div class="content_inputs">
                <input class="form-input rounded-md shadow-sm mt-1 mb-2.5 block w-full" id="content_inputs" type="text" autocomplete="" v-model="content_inputs">
                <input v-if="isActiveURL" class="form-input rounded-md shadow-sm mt-1 mb-2.5 block w-full" type="text" autocomplete="" placeholder="URL" v-model="content_url_inputs">
                <input v-if="isActiveHomework" class="form-input rounded-md shadow-sm mt-1 mb-2.5 block w-full" type="text" autocomplete="" placeholder="ファイル名" v-model="content_filename_inputs">
                <div class="flex items-center justify-between space-x-4">
                    <div class="selectbox color">
                        <select name="種類" v-model="content_select">
                            <option value="title">見出し</option>
                            <option value="text">テキスト</option>
                            <option value="link">リンク</option>
                            <option value="homework">課題</option>
                        </select>
                    </div>
                    <div class="text-right">
                        <rak-black-button @click.native.prevent="contentsAdd()">
                            追加
                        </rak-black-button>
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>
        </template>
    </rak-base-form>
</template>

<style>
    select{
        padding: 10px;
        box-sizing: border-box;
    }
    .color.selectbox select{
        background: theme('colors.blue-shadow');
        color: #fff;
        font-weight: bold;
        border-radius: 2em;
    }

    .card {
        box-shadow: 15px 15px 0px rgba(0,0,0,.1);
    }

    /*Don't forget to add Font Awesome CSS : "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"*/
    input {
    width: 100%;
    border: 2px solid #aaa;
    border-radius: 4px;
    margin: 8px 0;
    outline: none;
    padding: 8px;
    box-sizing: border-box;
    transition: 0.3s;
    }

    input:focus {
    border-color: dodgerBlue;
    box-shadow: 0 0 8px 0 dodgerBlue;
    }

    .input_icon input {
    padding-left: 40px;
    }

    .input_icon {
    position: relative;
    }

    .input_icon i {
    position: absolute;
    left: 0;
    top: 7px;
    padding: 9px 8px;
    color: #aaa;
    transition: 0.3s;
    }

    .input_icon input:focus + i {
    color: dodgerBlue;
    }

    .input_icon.inputIconBg i {
    background-color: #aaa;
    color: #fff;
    padding: 9px 4px;
    border-radius: 4px 0 0 4px;
    }

    .input_icon.inputIconBg input:focus + i {
    color: #fff;
    background-color: dodgerBlue;
    }



    .pro_title {
        border-bottom: 6px solid;
        border-image: linear-gradient(to right, #eb6138ff 0%, rgb(235, 97, 56,0) 100%);
        border-image-slice: 1;
    }

    .delete_ic{
        color: var(--blue-shadow);
        height: 24px;
        padding: 4px 0;
        position: absolute;
    }

    .delete_list{
        position: absolute;
    }
</style>

<script>
    import JetButton from './../../Jetstream/Button'
    import RakBlackButton from './../../rakuju/BaseComponent/BlackButton'
    import JetInput from './../../Jetstream/Input'
    import RakBaseForm from './../../rakuju/BaseComponent/BaseForm'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'
    import JetActionMessage from './../../Jetstream/ActionMessage'
    import draggable from 'vuedraggable'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            RakBlackButton,
            RakBaseForm,
            JetInput,
            JetInputError,
            JetLabel,
            draggable,
        },

        props: [
            'task', 
        ],

        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'PUT',
                    name: this.task.name,
                    sort_num: this.task.sort_num,
                    contents: '',
                }, {
                    bag: 'updateClassworkTask',
                    resetOnSuccess: false,
                }),

                contents: this.task.contents,   // コンテンツの配列
                content_select: 'title', // 選ばれてるコンテンツタイプ格納
                content_inputs: '', // コンテンツ名を格納
                content_url_inputs: '', // リンク、アーカイブ用のurlを格納
                content_filename_inputs: '', // 提出する課題のファイル名を格納
            }
        },

        methods: {
            updateClassworkTask() {
                // contentsをJSONにフォーマットする
                this.form.contents = JSON.stringify(this.contents);

                this.form.put('/api/classwork-task/' + this.task.id, {
                    preserveScroll: true
                });
            },

            contentsAdd() {
                if(this.content_select == "title"){// タイプがtitle(見出し)のとき
                    this.contents.push({"type":"title","text":this.content_inputs,"sort_num":this.contents.length});
                }else if(this.content_select == "text"){// タイプがテキストのとき
                    this.contents.push({"type":"text","text":this.content_inputs,"sort_num":this.contents.length});
                }else if(this.content_select == "link"){// タイプがリンクのとき
                    this.contents.push({"type":"link","text":this.content_inputs,"url":this.content_url_inputs,"sort_num":this.contents.length});
                }else if (this.content_select == "homework") {// タイプがhomework(課題)のとき
                    this.contents.push({"type":"homework","text":this.content_inputs,"file_name":this.content_filename_inputs,"sort_num":this.contents.length});
                }
            },

            deleteContentsItem(index) {
                this.contents.splice(index, 1);
                for (let content_index = index; content_index < this.contents.length; content_index++) {
                    this.contents[content_index].sort_num--;
                }
            }
        },

        filters:{
            // ファイル名の分割記号をカンマに置き換える
            replaceComma(val) {
                return val.replace('|', ', ');   
            }
        },

        computed:{
            // 現在選択されているタイプがURLを必要とするものかどうか
            isActiveURL() {
                return this.content_select == 'link' || this.content_select == 'archive'
            },

            isActiveHomework() {
                return this.content_select == 'homework'
            },
        }
    }
</script>
