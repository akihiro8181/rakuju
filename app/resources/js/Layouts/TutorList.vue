<template>
    <div>
        <p>TutorList</p>
        <ul id="classwork-tasks">
            <li v-for="classwork in $inertia.page.props.classworks" :key="classwork.id">
                <jet-nav-link :href="'/classwork-task/'+classwork.id" :active="$inertia.page.url == '/classwork-task/'+classwork.id">
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