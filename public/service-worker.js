const CACHE_NAME = 'offline-cache-v1';
const OFFLINE_URL = '/offline.html';

// Step 1: When the service worker installs, cache the offline page
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.add(OFFLINE_URL);
        })
    );
    self.skipWaiting(); // activate immediately, don't wait for old tabs to close
});

// Step 2: Clean up old caches when a new version activates
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            );
        })
    );
    self.clients.claim();
});

// Step 3: Intercept every fetch; if it's a page navigation and it fails, serve offline.html
self.addEventListener('fetch', (event) => {
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => {
                return caches.match(OFFLINE_URL);
            })
        );
    }
});