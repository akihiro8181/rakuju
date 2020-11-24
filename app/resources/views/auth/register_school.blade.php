<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mt-4">
                <x-jet-label for="school_name" value="{{ __('School Name') }}" />
                <x-jet-input id="school_name" class="block mt-1 w-full" type="text" name="school_name" :value="old('school_name')" required />
            </div>
            

            <div class="mt-4">
                <x-jet-label for="workspace_URL" value="{{ __('Workspace URL') }}" />
                <x-jet-input id="workspace_URL" class="block mt-1 w-full" type="text" name="workspace_URL" :value="old('workspace_URL')" required />
            </div>


            <div>
                <x-jet-label for="school_admin_name" value="{{ __('School Admin Name') }}" />
                <x-jet-input id="school_admin_name" class="block mt-1 w-full" type="text" name="school_admin_name" :value="old('school_admin_name')" required autofocus autocomplete="school_admin_name" />
            </div>


            <div class="mt-4">
                <x-jet-label for="login_number" value="{{ __('Login Number') }}" />
                <x-jet-input id="login_number" class="block mt-1 w-full" type="text" name="login_number" :value="old('login_number')" required />
            </div>


            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>


            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>


            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-jet-button class="ml-4">
                    {{ __('Register') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
