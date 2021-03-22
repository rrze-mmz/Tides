import Plyr from 'plyr';

import $ from 'jquery';
import 'select2';

window.$ = window.jQuery = $;

const player = new Plyr('#player',{
    language:'de',
    iconUrl: '/css/plyr.svg',
    loadSprite: false,
});

$(() => {
    $('.js-example-basic-single').select2();
});

// const video = document.querySelector('video');
// const source = document.getElementById("player").children[0].getAttribute("src");
//
// console.log(source);
//
// if (!Hls.isSupported()) {
//     video.src = document.getElementById("player");
// } else {
//     // For more Hls.js options, see https://github.com/dailymotion/hls.js
//     const hls = new Hls();
//     hls.loadSource(source);
//     hls.attachMedia(video);
//     window.hls = hls;
// }

// Expose player so it can be used from the console
window.player = player;

