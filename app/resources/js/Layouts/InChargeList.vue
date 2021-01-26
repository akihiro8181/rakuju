<template>
    <div>
        <p>授業一覧</p>
        <ul id="classwork-tasks">
            <li v-for="classwork in $inertia.page.props.classworks" :key="classwork.id">
                {{classwork.name}}
                <ul v-for="in_charge in classwork.in_charges" :key="in_charge.id">
                    <jet-nav-link :href="'/classwork-task/'+in_charge.id" :active="$inertia.page.url == '/classwork-task/'+in_charge.id">
                        担当者：{{ in_charge.name }}
                    </jet-nav-link>
                </ul>
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