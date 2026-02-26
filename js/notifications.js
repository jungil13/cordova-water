/**
 * Real-time Notifications with Socket.IO
 */
document.addEventListener('DOMContentLoaded', () => {
    const socket = io(SOCKET_URL);
    const badge = document.getElementById('notifications-badge');
    const list = document.getElementById('notifications-list');
    const btn = document.getElementById('notifications-btn');
    const menu = document.getElementById('notifications-menu');

    // Register user with socket
    socket.on('connect', () => {
        socket.emit('register', USER_ID);
        console.log('Connected to notification server');
    });

    // Handle incoming notifications
    socket.on('notification', (data) => {
        showNotification(data);
    });

    // Toggle dropdown
    if (btn && menu) {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.toggle('hidden');
            if (!menu.classList.contains('hidden')) {
                badge.classList.add('hidden');
            }
        });

        document.addEventListener('click', () => {
            menu.classList.add('hidden');
        });
    }

    function showNotification(data) {
        // Show badge
        if (badge) badge.classList.remove('hidden');

        // Add to list
        const item = document.createElement('div');
        item.className = 'p-4 hover:bg-slate-50 transition-colors cursor-pointer';
        item.innerHTML = `
            <div class="flex gap-3">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex-shrink-0 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-900">${data.title || 'New Notification'}</p>
                    <p class="text-xs text-slate-600 mt-0.5">${data.message}</p>
                    <p class="text-[10px] text-slate-400 mt-1">Just now</p>
                </div>
            </div>
        `;

        // Remove empty state if present
        const empty = list.querySelector('.text-center');
        if (empty) empty.remove();

        list.insertBefore(item, list.firstChild);

        // Optional: Show Toast
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(data.title, { body: data.message });
        }
    }

    // Request permissions
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
});
