<template>
    <div class="w-2/3 mx-auto">
        <div class="shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 bg-white sm:p-6">
                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6" v-if="hasActions">
                    <slot name="actions"></slot>
                </div>
                <div class="grid grid-cols-6 gap-6">
                    <slot name="header"></slot>
                </div>


                <table class="text-left w-full border-collapse">
                    <slot name="table"></slot>
                </table>
                
            </div>
            
        </div>
        <slot name="paginate"></slot>

        <slot name="add_modal"></slot>
        <slot name="update_modal"></slot>
        <slot name="delete_modal"></slot>

    </div>
</template>

<script>

    export default {
        computed: {
            hasActions() {
                return !! this.$slots.actions
            }
        }
    }
</script>
