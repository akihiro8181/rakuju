<template>
    <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="px-6 py-4">
            <div class="text-lg">
                <slot name="title">
                </slot>
            </div>

            <div class="mt-4">
                
                <slot name="content">
                </slot>

                <form @submit.prevent="$emit('submitted')" :id="formName">
                    <!-- <div class="shadow overflow-hidden sm:rounded-md"> -->
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <slot name="form"></slot>
                            </div>
                        </div>
                    <!-- </div> -->
                </form>



            </div>
        </div>

        <div class="px-6 py-4 bg-gray-100 text-right">
            <slot name="footer">
            </slot>
        </div>
    </modal>
</template>

<script>
    import Modal from './../../Jetstream/Modal'

    export default {
        components: {
            Modal,
        },

        props: {
            show: {
                default: false
            },
            maxWidth: {
                default: '2xl'
            },
            closeable: {
                default: true
            },
            formName:{

            },
        },

        methods: {
            close() {
                this.$emit('close')
            },
        }
    }
</script>
