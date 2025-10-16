 @vite(['resources/css/profile-tilt.css', 'resources/js/app.js'])
<x-app-layout>
    <x-slot name="header">
        {{ __('Profile') }}
    </x-slot>
  <div class="profile-card flex justify-center">
    <div class=" bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300">
      <div class="h-32 bg-gradient-to-r from-[#138282] to-[#0a2d2d] relative">
        <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2">
          <div class="h-24 w-24 rounded-full border-2 border-[#138282] bg-white overflow-hidden shadow-md">
            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=default" alt="Profile" class="h-full w-full object-cover bg-white ">
          </div>
        </div>
      </div>

      <div class=" bg-white pt-16 px-6 pb-6 text-center border-2 border-[#0a2d2d]/65 border-t-0 rounded-b-xl">
        <h1 class="text-2xl font-bold text-gray-800">Username</h1>
        <p class="text-purple-600 font-medium"></p>

        <div class="flex justify-center gap-20 my-4">
          <div>
            <p class="text-gray-600 text-sm">Posts</p>
            <p class="font-bold text-gray-800">0</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm">Followers</p>
            <p class="font-bold text-gray-800">1.2K</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm">Following</p>
            <p class="font-bold text-gray-800">47</p>
          </div>
        </div>

        <p class="text-gray-600 text-sm mt-2 mb-4">About:</p>

        <div class="flex flex-wrap justify-center gap-2 my-4">
          <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">Tailwind CSS</span>
          <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">React</span>
          <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">Figma</span>
          <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">JavaScript</span>
        </div>

        <div class="flex flex-col sm:flex-row gap-10 justify-center mt-6">
          <button class="px-6 py-2 bg-gradient-to-r from-[#142f39] to-[#04373c] text-white rounded-lg font-medium shadow-md hover:opacity-80 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#0b555c]/40">
            Follow
          </button>

          <button class="px-6 py-2 border-2 border-[#142f39] text-[#142f39] rounded-lg font-medium hover:text-white hover:bg-[#142f39] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#142f39]/80">
            My Profile
          </button>

          <button class="px-6 py-2 bg-gradient-to-r from-[#142f39] to-[#04373c] text-white rounded-lg font-medium shadow-md hover:opacity-80 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#0b555c]/40">
            Message
          </button>
        </div>

        <div class="flex justify-center gap-4 mt-6 text-gray-500 text-xs sm:text-sm">
          <div class="hover:text-[#0b555c] transition-colors">
            <i>All Rights Reserved by Postinger</i>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>