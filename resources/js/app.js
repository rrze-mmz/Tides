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
        <div v-show="isVisible" class="rounded-md bg-green-200 p-2 mb-2">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm leading-5 font-medium text-green-800">
                       {{ body }}
                    </p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button  @click="isVisible = false" type="button"
                                class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:bg-green-100 transition ease-in-out duration-150"
                                aria-label="Dismiss">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                      clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
`,

})

new Vue({
    el : '#app'
})

