<template>
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white flex flex-col">
      <div class="p-6 text-2xl font-bold border-b border-gray-700">
        Event Manager
      </div>
      <nav class="flex-1 p-6">
        <ul class="space-y-4">
          <li>
            <router-link to="/" class="hover:text-yellow-400" exact>Dashboard</router-link>
          </li>
          <li>
            <router-link to="/events" class="hover:text-yellow-400">Events</router-link>
          </li>
          <li>
            <router-link to="/registrations" class="hover:text-yellow-400">Registrations</router-link>
          </li>
          <li>
            <router-link to="/certificates" class="hover:text-yellow-400">Certificates</router-link>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 bg-gray-100 p-8 overflow-auto">
      <router-view />
    </main>
  </div>
</template>

<script>
export default {
  name: "App",
};
</script>

<style>
/* Add any extra custom styles here */
</style>
