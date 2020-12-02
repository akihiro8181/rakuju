<template>
    <div>
        <p>TutorList</p>
        <ul id="classwork-tasks">
            <li v-for="tutor in $inertia.page.props.user.tutors" :key="tutor.id">
                <jet-nav-link :href="'/classwork-task/'+tutor.id" :active="$page.currentRouteName == 'classwork-task'">
                    {{ tutor.classwork.name }}
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