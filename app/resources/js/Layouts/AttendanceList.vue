<template>
    <div>
        <p>AttendanceList</p>
        <ul id="classwork-tasks">
            <li v-for="attendance in $inertia.page.props.user.attendances" :key="attendance.id">
                <jet-nav-link :href="'/classwork-task/'+attendance.id" :active="$page.currentRouteName == 'classwork-task'">
                    {{ attendance.classwork.name }}
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