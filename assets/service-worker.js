const VERBOSE = true;
const VERSION = "V5";
const CACHED_FILES = [
    "/assets/img/logo.ico",
    "/assets/bootstrap.css",
    "/assets/img/logo.png",
    "/assets/illustrations/offline_page.png",
    "/offline",
];

self.addEventListener("install", function (event) {
    self.skipWaiting().then(() => {
        if (VERBOSE) console.log("Service Worker installed: ", VERSION);
    }).catch((err) => {
        console.error("Service Worker installation failed with : ", err);
    });
    event.waitUntil((async () => {
        const cache = await caches.open(VERSION);
        await cache.addAll(CACHED_FILES);
        if (VERBOSE) console.log("Service Worker cached: ", CACHED_FILES);
    })());
});

self.addEventListener("activate", function (event) {
    self.clients.claim().then(() => {
        if (VERBOSE) console.log("Service Worker activated: ", VERSION);
    }).catch((err) => {
        console.error("Service Worker activation failed with : ", err);
    });
    event.waitUntil((async () => {
        const keys = await caches.keys();
        await Promise.all(keys.map((key) => {
            if (key !== VERSION) return caches.delete(key);
        }));
        if (VERBOSE) console.log("Service Worker cleaned up: ", keys);
    })());
});

self.addEventListener("fetch", function (event) {
    if (VERBOSE) console.log("Service Worker fetching: ", event.request.url, " - ", event.request.mode);
    if (event.request.mode === "navigate") {
        event.respondWith((async () => {
            try {
                const preloadResponse = await event.preloadResponse;
                if (preloadResponse) return preloadResponse;
                return await fetch(event.request);
            } catch (error) {
                const cache = await caches.open(VERSION);
                return await cache.match("/offline");
            }
        })());
    } else if (CACHED_FILES.some((url) => event.request.url.includes(url))) {
        event.respondWith(caches.match(event.request));
        event.waitUntil((async () => {
            await (await caches.open(VERSION)).put(event.request, (await fetch(event.request)).clone());
        })());
        if (VERBOSE) console.log("Service Worker cached: ", event.request.url);
    }
});


