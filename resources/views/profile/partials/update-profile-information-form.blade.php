<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6" x-data="{ preview: '{{ asset('client/pfp/' . $user->pfp_url) }}', clearPreview() { this.preview = null; } }">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
    
        <div>
            <x-input-label for="birthday" :value="__('Birthday')" />
            <x-text-input id="birthday" name="birthday" type="date" class="mt-1 block w-full" :value="old('birthday', $user->birthday)" autocomplete="birthday" />
            <x-input-error class="mt-2" :messages="$errors->get('birthday')" />
        </div>
    
        <div>
            <x-input-label for="image" :value="__('Profile Picture')" />
            <div class="mt-1">
                <!-- Image Preview -->
                <img :src="preview" alt="Profile Picture Preview" class="w-full rounded-lg shadow-md" />
                <button type="button" class="text-red-500 mt-2 hover:text-red-700" x-show="preview" @click="clearPreview">
                    Remove Image
                </button>
            </div>
    
            <div class="flex items-center justify-between mt-3">
                <!-- File Input -->
                <button type="button" @click="$refs.imageUpload.click()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                    <i class="fas fa-camera"></i> Upload New Image
                </button>
                <input type="file" x-ref="imageUpload" id="imageUpload" name="image" class="hidden" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])" />
            </div>
    
            <x-input-error class="mt-2" :messages="$errors->get('image')" />
        </div>
    
        <div>
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="male" @if(old('gender', $user->gender) === 'male') selected @endif>{{ __('Male') }}</option>
                <option value="female" @if(old('gender', $user->gender) === 'female') selected @endif>{{ __('Female') }}</option>
                <option value="other" @if(old('gender', $user->gender) === 'other') selected @endif>{{ __('Other') }}</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
        </div>
    
        <div>
            <x-input-label for="bio" :value="__('Bio')" />
            <textarea id="bio" name="bio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>
    
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" autocomplete="address-line1" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>
    
        <div>
            <x-input-label for="website" :value="__('Website')" />
            <x-text-input id="website" name="website" type="url" class="mt-1 block w-full" :value="old('website', $user->website)" autocomplete="url" />
            <x-input-error class="mt-2" :messages="$errors->get('website')" />
        </div>    

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
