require('./bootstrap');

import 'select2';
import Alpine from 'alpinejs';
import Hls from 'hls.js';
import $ from 'jquery';
import Pikaday from 'pikaday';

window.$ = window.jQuery = $;
window.Alpine = Alpine
window.Pikaday = Pikaday;

$(() => {

    $('.select2-tides').select2();

    $('.select2-tides-clips').select2({
        allowClear: true,
        placeholder: 'Search for a clip',
        tags: true,
        minimumInputLength: 2,
        ajax: {
            url: "/api/clips/",
            delay: 250,
            data: function (params) {
                return {
                    query: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data, function (obj) {
                        return {id: obj.id, text: obj.name};
                    }),
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
        }
    });

    $('.select2-tides-tags').select2({
        allowClear: true,
        placeholder: 'Add a tag',
        tags: true,
        minimumInputLength: 2,
        ajax: {
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
                return {
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

    $('.select2-tides-presenters').select2({
        allowClear: true,
        placeholder: 'Add a presenter',
        minimumInputLength: 2,
        ajax: {
            url: "/api/presenters/",
            delay: 250,
            data: function (params) {
                return {
                    query: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data, function (obj) {
                        return {id: obj.id, text: obj.name};
                    }),
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
        }
    });

    $('.select2-tides-users').select2({
        placeholder: 'Search for a user',
        minimumInputLength: 2,
        ajax: {
            url: "/api/users/",
            delay: 250,
            data: function (params) {
                return {
                    query: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data, function (obj) {
                        return {id: obj.id, text: obj.name};
                    }),
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
        }
    });

    $('.select2-tides-organization').select2({
        placeholder: 'select an organization',
        minimumInputLength: 2,
        ajax: {
            url: "/api/organizations/",
            delay: 250,
            data: function (params) {
                return {
                    query: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data, function (obj) {
                        return {id: obj.id, text: obj.name};
                    }),
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
        }
    });
});

$('.solution-trix-field-wrapper').find($('trix-editor')).css("min-height", "350px");

document.addEventListener("DOMContentLoaded", () => {
    const video = document.querySelector("video");
    const source = video.getElementsByTagName("source")[0].src;

    // For more options see: https://github.com/sampotts/plyr/#options
    // captions.update is required for captions to work with hls.js
    const defaultOptions = {};

    if (Hls.isSupported()) {
        // For more Hls.js options, see https://github.com/dailymotion/hls.js
        const hls = new Hls();
        hls.loadSource(source);

        // From the m3u8 playlist, hls parses the manifest and returns
        // all available video qualities. This is important, in this approach,
        // we will have one source on the Plyr player.
        hls.on(Hls.Events.MANIFEST_PARSED, function (event, data) {

            // Transform available levels into an array of integers (height values).
            const availableQualities = hls.levels.map((l) => l.height)
            defaultOptions.language = 'de';
            defaultOptions.iconUrl = '/css/plyr.svg';
            defaultOptions.loadSripte = false;

            // Add new qualities to option
            defaultOptions.quality = {
                default: availableQualities[0],
                options: availableQualities,
                // this ensures Plyr to use Hls to update quality level
                forced: true,
                onChange: (e) => updateQuality(e),
            }


            // Initialize here
            const player = new Plyr(video, defaultOptions);
        });
        hls.attachMedia(video);
        window.hls = hls;
    } else {
        // default options with no quality update in case Hls is not supported
        const player = new Plyr(video, {
            language: 'de',
            iconUrl: '/css/plyr.svg',
            loadSprite: false,
        });
    }

    function updateQuality(newQuality) {
        window.hls.levels.forEach((level, levelIndex) => {
            if (level.height === newQuality) {
                console.log("Found quality match with " + newQuality);
                window.hls.currentLevel = levelIndex;
            }
        });
    }
});

Alpine.start();
