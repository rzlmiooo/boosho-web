<!-- Notification Bell Dropdown -->
<div class="relative" 
     x-data="{ 
        notifOpen: false, 
        unreadCount: {{ isset($unreadNotifCount) ? $unreadNotifCount : 0 }}, 
        notifications: [],
        toastOpen: false,
        toastNotif: null,
        toastTimer: null,
        
        fetchNotifs() {
            fetch('{{ route('api.notifications') }}')
                .then(res => res.json())
                .then(data => {
                    // Cek jika ada notifikasi baru (unread count bertambah)
                    if (this.notifications.length > 0 && data.unread_count > this.unreadCount) {
                        const newNotif = data.notifications[0];
                        if (newNotif) {
                            this.triggerToast(newNotif);
                        }
                    }
                    this.unreadCount = data.unread_count;
                    this.notifications = data.notifications;
                })
                .catch(err => console.error('Error fetching notifications:', err));
        },
        triggerToast(notif) {
            this.toastNotif = notif;
            this.toastOpen = true;
            if (this.toastTimer) clearTimeout(this.toastTimer);
            this.toastTimer = setTimeout(() => {
                this.toastOpen = false;
            }, 8000); // Tutup otomatis setelah 8 detik
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
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                Notifikasi
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
                                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                </template>
                                <template x-if="notif.type === 'promo'">
                                    <div class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                                    </div>
                                </template>
                                <template x-if="notif.type === 'recommendation'">
                                    <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                </template>
                                <template x-if="notif.type !== 'order' && notif.type !== 'promo' && notif.type !== 'recommendation'">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
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
                <div class="px-4 py-8 text-center flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <p class="text-xs text-gray-400 font-medium">Belum ada notifikasi</p>
                </div>
            </template>
        </div>
    </div>

    <!-- Floating Toast Alert -->
    <template x-teleport="body">
        <div x-show="toastOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="transform translate-y-10 opacity-0 scale-90"
             x-transition:enter-end="transform translate-y-0 opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="transform translate-y-0 opacity-100 scale-100"
             x-transition:leave-end="transform translate-y-10 opacity-0 scale-90"
             class="fixed bottom-5 right-5 z-[9999] w-96 bg-white/95 backdrop-blur rounded-2xl shadow-2xl border border-indigo-100 p-4 flex gap-3 items-start select-none"
             style="display: none;">
            
            <!-- Icon -->
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="toastNotif && toastNotif.type === 'order'">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                </template>
                <template x-if="toastNotif && toastNotif.type === 'promo'">
                    <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    </div>
                </template>
                <template x-if="toastNotif && toastNotif.type === 'recommendation'">
                    <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </template>
                <template x-if="toastNotif && toastNotif.type !== 'order' && toastNotif.type !== 'promo' && toastNotif.type !== 'recommendation'">
                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </template>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-sm text-gray-800" x-text="toastNotif ? toastNotif.title : ''"></h4>
                <p class="text-xs text-gray-600 leading-relaxed mt-1" x-text="toastNotif ? toastNotif.message : ''"></p>
                
                <!-- Refresh Action Button (Only on /account or /admin/orders or /admin/dashboard or /dashboard) -->
                <template x-if="window.location.pathname === '/account' || window.location.pathname === '/admin/orders' || window.location.pathname === '/dashboard' || window.location.pathname === '/admin/dashboard'">
                    <button @click="window.location.reload()" class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold rounded-lg transition shadow-sm hover:shadow">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3-3 3 3m-3-3v12"/></svg>
                        Perbarui Data Halaman
                    </button>
                </template>
            </div>

            <!-- Close Button -->
            <button @click="toastOpen = false" class="text-gray-400 hover:text-gray-600 transition p-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </template>
</div>
