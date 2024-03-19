const VERSION = "V2";
const CACHED_FILES = [
    "/assets/img/logo.ico",
    "/assets/bootstrap.css",
    "/offline.html",
];

self.addEventListener("install", function (event) {
    self.skipWaiting().then(() => {
        console.log("Service Worker installed: ", VERSION);
    }).catch((err) => {
        console.error("Service Worker installation failed with : ", err);
    });
    event.waitUntil((async () => {
        const cache = await caches.open(VERSION);
        await cache.addAll(CACHED_FILES);
        console.log("Service Worker cached files");
    })());
});

self.addEventListener("activate", function (event) {
    self.clients.claim().then(() => {
        console.log("Service Worker activated: ", VERSION);
    }).catch((err) => {
        console.error("Service Worker activation failed with : ", err);
    });
    event.waitUntil((async () => {
        const keys = await caches.keys();
        await Promise.all(keys.map((key) => {
            if (key !== VERSION) return caches.delete(key);
        }));
        console.log("Service Worker cleared old caches");
    })());
});

self.addEventListener("fetch", function (event) {
    console.log(VERSION, "Fetch request for:", event.request.url);

    if (event.request.mode === "navigate") {
        event.respondWith((async () => {
            try {
                const preloadResponse = await event.preloadResponse;
                if (preloadResponse) return preloadResponse;
                return await fetch(event.request);
            } catch (error) {
                const cache = await caches.open(VERSION);
                return await cache.match("/offline.html");
            }
        })());
    } else if (CACHED_FILES.some((url) => event.request.url.includes(url))) {
        console.log("Returning cached file for:", event.request.url);
        event.respondWith(caches.match(event.request));
        event.waitUntil((async () => {
            await (await caches.open(VERSION)).put(event.request, (await fetch(event.request)).clone());
        })());
    }
});
