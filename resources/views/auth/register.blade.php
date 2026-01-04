<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Student Registration</h2>
        <p class="text-sm text-gray-600 mt-1">Join our hostel community</p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md">
            <ul class="list-disc list-inside text-sm text-red-600">
                 @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" x-data="{ role: 'student' }">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">Full Name</label>
            <input id="name" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
            <input id="email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition" type="email" name="email" :value="old('email')" required autocomplete="username" />
        </div>
        
        <!-- Phone -->
        <div class="mt-4">
             <label for="phone" class="block font-medium text-sm text-gray-700">Phone Number <span class="text-red-500">*</span></label>
             <input id="phone" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition" type="text" name="phone" :value="old('phone')" required />
        </div>

        <!-- Role Selection -->
        <div class="mt-4">
            <label for="role" class="block font-medium text-sm text-gray-700">Register As</label>
            <select id="role" name="role" x-model="role" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                <option value="student">Student</option>
                <option value="visitor">Visitor</option>
            </select>
        </div>

        <!-- Student Specific Fields -->
        <div x-show="role === 'student'" class="space-y-4 mt-4">
            <!-- Parent WhatsApp Number -->
            <div>
                 <label for="parent_phone" class="block font-medium text-sm text-gray-700">
                    Parent's WhatsApp Number <span class="text-red-500">*</span>
                 </label>
                 <input id="parent_phone" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition" type="text" name="parent_phone" :value="old('parent_phone')" :required="role === 'student'" />
                 <p class="mt-1 text-xs text-gray-500">Required for attendance notifications</p>
            </div>

            <!-- Academic Information -->
            <div>
                <label for="year" class="block font-medium text-sm text-gray-700">Year <span class="text-red-500">*</span></label>
                <select id="year" name="year" :required="role === 'student'" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                    <option value="">Select Year</option>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
            </div>

            <div>
                <label for="department" class="block font-medium text-sm text-gray-700">Department <span class="text-red-500">*</span></label>
                <input id="department" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition" type="text" name="department" :value="old('department')" :required="role === 'student'" placeholder="e.g., Computer Science, Engineering" />
            </div>

            <div>
                <label for="semester" class="block font-medium text-sm text-gray-700">Semester <span class="text-red-500">*</span></label>
                <select id="semester" name="semester" :required="role === 'student'" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                    <option value="">Select Semester</option>
                    <option value="1">1st Semester</option>
                    <option value="2">2nd Semester</option>
                    <option value="3">3rd Semester</option>
                    <option value="4">4th Semester</option>
                    <option value="5">5th Semester</option>
                    <option value="6">6th Semester</option>
                    <option value="7">7th Semester</option>
                    <option value="8">8th Semester</option>
                </select>
            </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
            <input id="password" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition" type="password" name="password" required autocomplete="new-password" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm Password</label>
            <input id="password_confirmation" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('login') }}">
                Already registered?
            </a>

            <button class="ml-4 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition transform hover:-translate-y-0.5">
                Register
            </button>
        </div>
    </form>
</x-guest-layout>
