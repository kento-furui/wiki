<form method="POST" action="{{ route('register') }}">
    @csrf
    <div>
        <input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
    </div>
    <div class="mt-4">
        <input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
    </div>
    <div class="mt-4">
        <input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
    </div>
    <div class="mt-4">
        <input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
    </div>
    <div class="flex items-center justify-end mt-4">
        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
            {{ __('Already registered?') }}
        </a>
        <button class="ml-4">
            {{ __('Register') }}
        </button>
    </div>
</form>