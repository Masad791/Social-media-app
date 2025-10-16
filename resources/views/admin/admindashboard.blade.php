<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class=" text-white text-2xl font-bold mb-4">Admin Dashboard</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-white rounded shadow">
                <p class="text-sm text-gray-500">Users</p>
                <p class="text-3xl font-bold">{{ $usersCount }}</p>
            </div>
            <div class="p-4 bg-white rounded shadow">
                <p class="text-sm text-gray-500">Posts</p>
                <p class="text-3xl font-bold">{{ $postsCount }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
