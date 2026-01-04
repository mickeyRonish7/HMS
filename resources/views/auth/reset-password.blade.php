<x-guest-layout>
    <div class="space-y-4">
        <div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                Set a New Password
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Enter your email and new password.
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email-address" class="sr-only">Email address</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required class="appearance-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email address" value="{{ $email ?? old('email') }}">
                </div>
                <div>
                    <label for="password" class="sr-only">New Password</label>
                    <input id="password" name="password" type="password" required class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="New Password">
                </div>
                <div>
                    <label for="password_confirmation" class="sr-only">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none rounded-b-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Confirm Password">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
