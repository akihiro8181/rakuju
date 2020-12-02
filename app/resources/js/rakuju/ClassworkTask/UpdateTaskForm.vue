<template>
    <rak-base-form @submitted="updateClassworkTask">
        <template #form>
            <!-- Name -->
            <div class="col-span-5">
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                <jet-input-error :message="form.error('name')" class="mt-2" />
            </div>

            <!-- SortNum -->
            <div class="col-span-5">
                <jet-label for="sort_num" value="SortNum" />
                <jet-input id="sort_num" type="number" class="mt-1 block w-full" v-model="form.sort_num" />
                <jet-input-error :message="form.error('sort_num')" class="mt-2" />
            </div>

            <!-- Contents -->
            <div class="col-span-5">
                <jet-label for="contents" value="Contents" />
                <textarea id="contents" name="contents" rows="5" cols="33" class="mt-1 block w-full" v-model="form.contents" ></textarea>
                <jet-input-error :message="form.error('contents')" class="mt-2" />
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

        props: ['task'],

        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'PUT',
                    name: this.task.name,
                    sort_num: this.task.sort_num,
                    contents: JSON.stringify(this.task.contents),
                }, {
                    bag: 'updateClassworkTask',
                    resetOnSuccess: false,
                }),
            }
        },

        methods: {
            updateClassworkTask() {
                this.form.put('/api/classwork-task/' + this.task.id, {
                    preserveScroll: true
                });
            }
        },

    }
</script>
