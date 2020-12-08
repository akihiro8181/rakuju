<template>
    <rak-base-form @submitted="updateSchoolInformation">
        <template #form>
            <!-- SchoolName -->
            <div class="col-span-5">
                <jet-label for="name" value="学校名" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                <jet-input-error :message="form.error('name')" class="mt-2" />
            </div>

            <!-- Workspace_URL -->
            <div class="col-span-5">
                <jet-label for="workspace_url" value="ワークスペースURL" />
                <jet-input id="workspace_url" type="text" class="mt-1 block w-full" v-model="form.workspace_url" />
                <jet-input-error :message="form.error('workspace_url')" class="mt-2" />
            </div>
            

        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>
        </template>

    </rak-base-form>
</template>

<script>
    import JetButton from './../../Jetstream/Button'
    import JetInput from './../../Jetstream/Input'
    import RakBaseForm from './../../rakuju/BaseComponent/BaseForm'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'
    import JetActionMessage from './../../Jetstream/ActionMessage'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            RakBaseForm,
            JetInput,
            JetInputError,
            JetLabel,
        },

        props:['school'],

        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'PUT',
                    name: this.school.name,
                    workspace_url: this.school.workspace_url,
                }, {
                    bag: 'updateSchoolInfomation',
                    resetOnSuccess: false,
                }),

            }
        },

        methods: {
            
            updateSchoolInformation(){
                
               this.form.put('/api/schoolinformation/' + this.school.id, {
                    preserveScroll: true
                });

            },

        },

    }
</script>
