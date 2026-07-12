<!-- Notification Bell Dropdown -->
<div class="relative" 
     x-data="{ 
        notifOpen: false, 
        unreadCount: {{ isset($unreadNotifCount) ? $unreadNotifCount : 0 }}, 
        notifications: [],
        fetchNotifs() {
            fetch('{{ route('api.notifications') }}')
                .then(res => res.json())
                .then(data => {
                    this.unreadCount = data.unread_count;
                    this.notifications = data.notifications;
                })
                .catch(err => console.error('Error fetching notifications:', err));
        },
        markAllRead() {
            fetch('{{ route('notifications.readAll') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(() => {
                this.fetchNotifs();
            })
            .catch(err => console.error('Error marking all as read:', err));
        }
     }" 
     x-init="fetchNotifs(); setInterval(() => fetchNotifs(), 5000)">
     
    <button @click="notifOpen = !notifOpen; fetchNotifs()" @click.outside="notifOpen = false" class="relative p-2 rounded-full hover:bg-indigo-50 transition focus:outline-none" title="Notifikasi">
        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        <template x-if="unreadCount > 0">
            <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[9px] font-bold w-4.5 h-4.5 flex items-center justify-center rounded-full shadow-sm min-w-[18px] px-1" x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
        </template>
    </button>

    <div x-show="notifOpen"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="transform opacity-0 scale-95 -translate-y-1"
         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95 -translate-y-1"
         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-[999] overflow-hidden"
         style="display: none;">

        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-indigo-50 to-white">
            <h4 class="font-bold text-sm text-gray-800 flex items-center gap-1.5">
                🔔 Notifikasi
                <template x-if="unreadCount > 0">
                    <span class="bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full" x-text="unreadCount"></span>
                </template>
            </h4>
            <template x-if="unreadCount > 0">
                <button type="button" @click="markAllRead()" class="text-[10px] text-indigo-600 hover:text-indigo-800 font-bold transition hover:underline">Tandai semua dibaca</button>
            </template>
        </div>

        <!-- Notification List -->
        <div class="max-h-72 overflow-y-auto">
            <template x-if="notifications.length > 0">
                <div class="divide-y divide-gray-50">
                    <template x-for="notif in notifications" :key="notif.id">
                        <a :href="notif.url ? notif.url : 'javascript:void(0)'" 
                           class="block px-4 py-3 hover:bg-gray-50 transition flex gap-3 items-start"
                           :class="!notif.is_read ? 'bg-indigo-50/50' : ''">
                            <div class="mt-0.5 flex-shrink-0">
                                <template x-if="notif.type === 'order'">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-sm">📦</div>
                                </template>
                                <template x-if="notif.type === 'promo'">
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-sm">🔥</div>
                                </template>
                                <template x-if="notif.type === 'recommendation'">
                                    <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-sm">✨</div>
                                </template>
                                <template x-if="notif.type !== 'order' && notif.type !== 'promo' && notif.type !== 'recommendation'">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-sm">ℹ️</div>
                                </template>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold text-xs text-gray-800 truncate" x-text="notif.title"></span>
                                    <template x-if="!notif.is_read">
                                        <span class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></span>
                                    </template>
                                </div>
                                <p class="text-[11px] text-gray-500 leading-relaxed mt-0.5 line-clamp-2" x-text="notif.message"></p>
                                <span class="text-[10px] text-gray-400 mt-1 block" x-text="notif.created_at_human"></span>
                            </div>
                        </a>
                    </template>
                </div>
            </template>
            
            <template x-if="notifications.length === 0">
                <div class="px-4 py-8 text-center">
                    <div class="text-3xl mb-2">🔕</div>
                    <p class="text-xs text-gray-400 font-medium">Belum ada notifikasi</p>
                </div>
            </template>
        </div>
    </div>
</div>
