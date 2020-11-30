<template>
    <rak-base-form @submitted="createNewClassworkTask">
        <template #form>
            <!-- Name -->
            <div class="col-span-5">
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                <jet-input-error :message="form.error('name')" class="mt-2" />
            </div>

            <!-- Deadline -->
            <div class="col-span-5">
                <jet-label for="deadline" value="Deadline" />
                <jet-input id="deadline" type="date" class="mt-1 block w-full" v-model="form.deadline" />
                <jet-input-error :message="form.error('deadline')" class="mt-2" />
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

        props: ['in_charge_id'],

        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'POST',
                    name: this.name,
                    deadline: this.deadline,
                    sort_num: this.sort_num,
                    contents: this.contents,
                }, {
                    bag: 'createNewClassworkTask',
                    resetOnSuccess: false,
                }),
            }
        },

        methods: {
            createNewClassworkTask() {
                this.form.post('/api/classwork-task/' + this.in_charge_id, {
                    preserveScroll: true
                });
            }
        },
    }
</script>
