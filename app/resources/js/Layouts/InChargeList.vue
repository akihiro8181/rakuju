<template>
    <div>
        <p>InChargeList</p>
        <ul id="classwork-tasks">
            <li v-for="classwork in $inertia.page.props.user.school.classworks" :key="classwork.id">
                {{classwork.name}}
                <ul v-for="in_charge in classwork.in_charges" :key="in_charge.id">
                    <jet-nav-link :href="'/classwork-task/'+in_charge.id" :active="$page.currentRouteName == 'classwork-task'">
                        {{ in_charge.teacher.name }}
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