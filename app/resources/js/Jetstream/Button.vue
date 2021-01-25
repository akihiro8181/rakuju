<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-blue-shadow border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-ridge-gray active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray focus:bg-ridge-gray transition ease-in-out duration-150">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: 'submit',
            },
        }
    }
</script>
