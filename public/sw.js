// Service Worker for OrderFlow PWA
// This service worker enables PWA installation but does NOT cache anything for offline use

const CACHE_VERSION = 'v1';

// Install event - just skip waiting, no caching
self.addEventListener('install', (event) => {
    console.log('[SW] Installing service worker...');
    // Skip waiting to activate immediately
    self.skipWaiting();
});

// Activate event - clean up any old caches and take control
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating service worker...');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            // Delete all caches to ensure no offline functionality
            return Promise.all(
                cacheNames.map((cacheName) => {
                    console.log('[SW] Deleting cache:', cacheName);
                    return caches.delete(cacheName);
                })
            );
        }).then(() => {
            // Take control of all pages immediately
            return self.clients.claim();
        })
    );
});

// Fetch event - always go to network, never use cache
self.addEventListener('fetch', (event) => {
    // Simply let the request pass through to the network
    // No caching, no offline support
    event.respondWith(
        fetch(event.request).catch((error) => {
            // If network fails, we can't do anything - just show browser's offline page
            console.log('[SW] Network request failed:', event.request.url);
            throw error;
        })
    );
});

// Handle messages from the main thread
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

