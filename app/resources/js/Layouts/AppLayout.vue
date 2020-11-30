<template>
    <div class="min-h-screen bg-gray-100 flex" @click='onClick'>
        <nav class="bg-white border-b border-gray-100 flex-none w-64 pb-6 hidden md:block">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <inertia-link :href="route('dashboard')">
                                <jet-application-mark class="block h-9 w-auto" />
                            </inertia-link>
                        </div>
                        <!-- school name -->
                        <div>{{$page.user.school.name}}</div>
                        <div>{{$page.user.roll_flag}}</div>
                        <!-- Settings Dropdown -->
                        <div class="flex-grow">
                            <div class="ml-3">
                                <jet-dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                            <img class="h-8 w-8 rounded-full object-cover" :src="$page.user.profile_photo_url" :alt="$page.user.name" />
                                        </button>

                                        <button v-else class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                            <div>{{ $page.user.name }}</div>

                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Manage Account
                                        </div>

                                        <jet-dropdown-link :href="route('profile.show')">
                                            Profile
                                        </jet-dropdown-link>

                                        <jet-dropdown-link :href="route('api-tokens.index')" v-if="$page.jetstream.hasApiFeatures">
                                            API Tokens
                                        </jet-dropdown-link>

                                        <div class="border-t border-gray-100"></div>

                                        <!-- Team Management -->
                                        <!-- <template v-if="$page.jetstream.hasTeamFeatures">
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                Manage Team
                                            </div> -->

                                            <!-- Team Settings -->
                                            <!-- <jet-dropdown-link :href="route('teams.show', $page.user.current_team)">
                                                Team Settings
                                            </jet-dropdown-link>

                                            <jet-dropdown-link :href="route('teams.create')" v-if="$page.jetstream.canCreateTeams">
                                                Create New Team
                                            </jet-dropdown-link>

                                            <div class="border-t border-gray-100"></div> -->

                                            <!-- Team Switcher -->
                                            <!-- <div class="block px-4 py-2 text-xs text-gray-400">
                                                Switch Teams
                                            </div>

                                            <template v-for="team in $page.user.all_teams">
                                                <form @submit.prevent="switchToTeam(team)">
                                                    <jet-dropdown-link as="button">
                                                        <div class="flex items-center">
                                                            <svg v-if="team.id == $page.user.current_team_id" class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            <div>{{ team.name }}</div>
                                                        </div>
                                                    </jet-dropdown-link>
                                                </form>
                                            </template>

                                            <div class="border-t border-gray-100"></div>
                                        </template> -->

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <jet-dropdown-link as="button">
                                                Logout
                                            </jet-dropdown-link>
                                        </form>
                                    </template>
                                </jet-dropdown>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <!-- <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"> -->
                    <div>
                        <jet-nav-link :href="route('dashboard')" :active="$page.currentRouteName == 'dashboard'">
                            Dashboard
                        </jet-nav-link>
                    </div>
                    <div>
                        <jet-nav-link :href="route('datatest')" :active="$page.currentRouteName == 'datatest'">
                            DataTest
                        </jet-nav-link>
                    </div>
                    <div>
                        <attendance-list />
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Add Button (test) -->
                    <div>
                        <jet-nav-link v-if="$page.user.roll_flag == 'st'" :href="route('student')" :active="$page.currentRouteName == 'student'">
                            move page(student)(test)
                        </jet-nav-link>
                        <jet-nav-link v-if="$page.user.roll_flag == 'te'" :href="route('teacher')" :active="$page.currentRouteName == 'teacher'">
                            move page(teacher)(test)
                        </jet-nav-link>
                        <jet-nav-link v-if="$page.user.roll_flag == 'ad'" :href="route('admin')" :active="$page.currentRouteName == 'admin'">
                            move page(admin)(test)
                        </jet-nav-link>
                    </div>

                    <!-- new video window -->
                    <div>
                        <!-- 学生用のvideo リンク -->
                        <a v-if="$page.user.roll_flag == 'st'" @click="showDialog()" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            Video(Start)(student)
                        </a>
                        <!-- 先生用のvideoリンク -->
                        <a v-if="$page.user.roll_flag == 'te'" @click="showDialog()" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            Video(Start)(teacher)
                        </a>

                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <jet-responsive-nav-link :href="route('dashboard')" :active="$page.currentRouteName == 'dashboard'">
                        Dashboard
                    </jet-responsive-nav-link>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" :src="$page.user.profile_photo_url" :alt="$page.user.name" />
                        </div>

                        <div class="ml-3">
                            <div class="font-medium text-base text-gray-800">{{ $page.user.name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.user.email }}</div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <jet-responsive-nav-link :href="route('profile.show')" :active="$page.currentRouteName == 'profile.show'">
                            Profile
                        </jet-responsive-nav-link>

                        <jet-responsive-nav-link :href="route('api-tokens.index')" :active="$page.currentRouteName == 'api-tokens.index'" v-if="$page.jetstream.hasApiFeatures">
                            API Tokens
                        </jet-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" @submit.prevent="logout">
                            <jet-responsive-nav-link as="button">
                                Logout
                            </jet-responsive-nav-link>
                        </form>

                        <!-- Team Management
                        <template v-if="$page.jetstream.hasTeamFeatures">
                            <div class="border-t border-gray-200"></div>

                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Manage Team
                            </div> -->

                            <!-- Team Settings -->
                            <!-- <jet-responsive-nav-link :href="route('teams.show', $page.user.current_team)" :active="$page.currentRouteName == 'teams.show'">
                                Team Settings
                            </jet-responsive-nav-link>

                            <jet-responsive-nav-link :href="route('teams.create')" :active="$page.currentRouteName == 'teams.create'">
                                Create New Team
                            </jet-responsive-nav-link>

                            <div class="border-t border-gray-200"></div> -->

                            <!-- Team Switcher -->
                            <!-- <div class="block px-4 py-2 text-xs text-gray-400">
                                Switch Teams
                            </div>

                            <template v-for="team in $page.user.all_teams">
                                <form @submit.prevent="switchToTeam(team)" :key="team.id">
                                    <jet-responsive-nav-link as="button">
                                        <div class="flex items-center">
                                            <svg v-if="team.id == $page.user.current_team_id" class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <div>{{ team.name }}</div>
                                        </div>
                                    </jet-responsive-nav-link>
                                </form>
                            </template>
                        </template> -->
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header"></slot>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <slot></slot>
        </main>
        <div class="dialog" ref="startDialog" v-if="this.diaglogFlag">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close" ref="js_Close_Dialog">&times;</span>
                    <h2>ラクジュ</h2>
                </div>
                <div class="modal-body">
                    <div class="content-text" v-if="$page.user.roll_flag == 'te'">
                        授業を開設します、よろしいですか？
                    </div>
                    <div class="content-text" v-if="$page.user.roll_flag == 'st'">
                        授業に参加します、よろしいですか？
                    </div>
                    <div class="okButton" ref="js-ok-button" @click="startVideo($page.user.roll_flag)">はい</div>
                </div>
            </div>
        </div>

        <!-- Modal Portal -->
        <portal-target name="modal" multiple>
        </portal-target>
    </div>
</template>

<script>
    import JetApplicationLogo from './../Jetstream/ApplicationLogo'
    import JetApplicationMark from './../Jetstream/ApplicationMark'
    import JetDropdown from './../Jetstream/Dropdown'
    import JetDropdownLink from './../Jetstream/DropdownLink'
    import JetNavLink from './../Jetstream/NavLink'
    import JetResponsiveNavLink from './../Jetstream/ResponsiveNavLink'
    import AttendanceList from './AttendanceList'

    export default {
        components: {
            JetApplicationLogo,
            JetApplicationMark,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,
            AttendanceList,
        },

        data() {
            return {
                showingNavigationDropdown: false,
                videoWindow : undefined,　　// video window 用の変数
                diaglogFlag : false,　　　//dialog 表示変数
            }
        },

        methods: {
            //dialog の呼び出し　メソッド
            showDialog(){
                if(this.videoWindow === undefined || this.videoWindow.closed){
                    this.diaglogFlag = true;
                }else{
                    this.videoWindow.focus();
                }
            },
            startVideo(roll_flag) {
                this.diaglogFlag = false;
                var height = screen.availHeight;
                var width = screen.availWidth;
                var windowWidth = width/8*6;
                var windowHeight = height / 14 * 13;
                var left = (width - windowWidth) / 2;
                var url;
                if(roll_flag==='st'){
                    url = route('studentVideo');
                }else{
                    url = route('teacherVideo');
                }
                this.videoWindow = window.open(url, '授業名',`left=${left},width=${windowWidth},height=${windowHeight}`);
            },

            // switchToTeam(team) {
            //     this.$inertia.put(route('current-team.update'), {
            //         'team_id': team.id
            //     }, {
            //         preserveState: false
            //     })
            // },

            logout() {
                axios.post(route('logout').url()).then(response => {
                    window.location = '/';
                })
            },
            onClick: function (e) {
                if (e.target == this.$refs.startDialog || e.target== this.$refs.js_Close_Dialog) {
                    this.diaglogFlag = false;
                }
            }
        },

        computed: {
            path() {
                return window.location.pathname
            }
        }
    }
</script>

<style>
a{
    cursor: pointer;
}
/* dialog style */
.dialog{
    display: flex;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}
.modal-content {
    position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
    border: 1px solid #888;
    width: 410px;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    animation-name: animatetop;
    animation-duration: 0.4s;
    border-radius: 10px;
}
@keyframes animatetop {
    from {
        top:-300px; 
        opacity:0}

    to {
        top:0; 
        opacity:1
    }
}
.close {
    color: white;
    float: right;
    font-size: 30px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
h2{
    font-size: revert;
    font-weight: bolder;
}

.modal-header {
    padding: 2px 16px;
    background-color: #7d93baff;
    color: #404b73ff;
    line-height: 38px;
    border-top-left-radius: 9px;
    border-top-right-radius: 9px;
}

.modal-body {
    padding: 8px 16px;
}
.content-text{
    margin: 5px;
    font-size: 21px;
    font-weight: bold;
    display: flex;
    line-height: 50px;
}
.okButton{
    outline: none;
    background-color:#404b73ff;
    color: #7d93baff;
    width: 70px;
    height: 35px;
    line-height: 35px;
    font-size: 21px;
    border-radius: 12px;
    margin: auto;
    text-align: center;
    cursor: pointer;
}
.okButton:hover{
    background-color: #3b404f;
}
</style>