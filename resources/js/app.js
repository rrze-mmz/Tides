import Plyr from 'plyr';
import $ from 'jquery';
import 'select2';

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

// window.Vue = require('vue');
import Vue from 'vue'

Vue.component('tides-flash-message', {
    props:['body'],

    data() {
        return {
            isVisible : true
        };
    },

    created() {
        setTimeout(() => this.isVisible = false, 2000)
    },

    template: `
        <div v-show="isVisible" class="transition-all duration-500 ease-in-out text-white px-6 py-4 border-0 rounded relative mb-4 bg-blue-500">
            <span class="text-xl inline-block mr-5 align-middle">
                                <i class="fas fa-bell" />
                              </span>
            <span class="inline-block align-middle mr-8">
                                <b >{{body}}</b>
                              </span>
            <button class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none">
                <button @click="isVisible = false">×</button>
            </button>
        </div>
`,

})

new Vue({
    el : '#app'
})

