import Plyr from 'plyr';
import $ from 'jquery';
import 'select2';
import 'alpinejs';


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

window.$ = window.jQuery = $;

const player = new Plyr('#player', {
    language: 'de',
    iconUrl: '/css/plyr.svg',
    loadSprite: false,
});

$(() => {
    $('.js-example-basic-single').select2({
        allowClear: true,
        placeholder: 'Add a tag',
        tags: true,
        minimumInputLength: 2,
        ajax:{
            url: "/api/tags/",
            delay: 250,
            data: function (params) {
                return {
                    query: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return  {
                    results: $.map(data, function (obj) {
                        return {id: obj.name, text: obj.name};
                    }),
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
        }
    });
});
