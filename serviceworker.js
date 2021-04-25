
// ZIM Zapps PWA Service Worker to cache app files
// Please check to see all files have been listed with local links
// (Do not worry about icon files)

var cacheName = 'zim_pwa_kill_covid-19';
var filesToCache = [
  './',
  'index.html',
  'libraries/createjs.js',
  'libraries/zim.js',
  'libraries/game_2.4.js',
  'libraries/zimserver_urls.js',
  'assets/character.png',
  'assets/logo.png',
  'assets/bullet.wav',
  'assets/virusdie.wav',
  'assets/playerdie.wav',
  'assets/keyboard.png',
  'assets/player(man).png',
  'assets/bullet.png',
  'assets/{src:https://fonts.googleapis.com/css2?family=Bangers&display=swap}',
  'assets/{src:https://fonts.googleapis.com/css2?family=Coda+Caption:wght@800&display=swap}'
];

/* Start the service worker and cache all of the app's content */
self.addEventListener('install', function(e) {
  e.waitUntil(
    caches.open(cacheName).then(function(cache) {
      return cache.addAll(filesToCache);
    })
  );
});

/* Serve cached content when offline */
self.addEventListener('fetch', function(e) {
  e.respondWith(
    caches.match(e.request).then(function(response) {
      return response || fetch(e.request);
    })
  );
});
