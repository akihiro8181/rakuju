<template>
    <div>
        <p>授業一覧</p>
        <ul id="classwork-tasks" class="ml-3">
            <li v-for="classwork in $inertia.page.props.classworks" :key="classwork.id">
                <jet-nav-link :href="'/classwork-task/'+classwork.id" :active="$inertia.page.url == '/classwork-task/'+classwork.id" class="ml-2">
                    {{ classwork.name }}
                </jet-nav-link>
            </li>
        </ul>
    </div>
</template>
<script>
import JetNavLink from './../Jetstream/NavLink'

export default {
    components: {
        JetNavLink,
    },
}
</script>