import "boot";

import {
    Livewire,
    Alpine,
} from "../../vendor/livewire/livewire/dist/livewire.esm";
import jQuery from "jquery";
import Hls from "hls.js";
import Pikaday from "pikaday";
import * as FilePond from "filepond";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import "filepond/dist/filepond.min.css";
import select2 from "select2";

Livewire.start();

window.jQuery = window.$ = jQuery;
window.Pikaday = Pikaday;
window.select2 = select2();

$(".solution-trix-field-wrapper")
    .find($("trix-editor"))
    .css("min-height", "350px");

document.addEventListener(
    "DOMContentLoaded",
    function () {
        $(".select2-tides").select2();

        $(".select2-tides-clips").select2({
            allowClear: true,
            placeholder: "Search for a clip",
            tags: true,
            minimumInputLength: 2,
            ajax: {
                url: "/api/clips/",
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data, function (obj) {
                            return { id: obj.id, text: obj.name };
                        }),
                        pagination: {
                            more: params.page * 30 < data.total_count,
                        },
                    };
                },
            },
        });

        $(".select2-tides-tags").select2({
            allowClear: true,
            placeholder: "Add a tag",
            tags: true,
            minimumInputLength: 2,
            ajax: {
                url: "/api/tags/",
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data, function (obj) {
                            return { id: obj.name, text: obj.name };
                        }),
                        pagination: {
                            more: params.page * 30 < data.total_count,
                        },
                    };
                },
            },
        });

        $(".select2-tides-presenters").select2({
            allowClear: true,
            placeholder: "Add a presenter",
            minimumInputLength: 2,
            ajax: {
                url: "/api/presenters/",
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data, function (obj) {
                            return { id: obj.id, text: obj.name };
                        }),
                        pagination: {
                            more: params.page * 30 < data.total_count,
                        },
                    };
                },
            },
        });

        $(".select2-tides-users").select2({
            placeholder: "Search for a user",
            minimumInputLength: 2,
            ajax: {
                url: "/api/users/",
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data, function (obj) {
                            return { id: obj.id, text: obj.name };
                        }),
                        pagination: {
                            more: params.page * 30 < data.total_count,
                        },
                    };
                },
            },
        });

        $(".select2-tides-organization").select2({
            placeholder: "select an organization",
            minimumInputLength: 2,
            ajax: {
                url: "/api/organizations/",
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data, function (obj) {
                            return { id: obj.id, text: obj.name };
                        }),
                        pagination: {
                            more: params.page * 30 < data.total_count,
                        },
                    };
                },
            },
        });

        $(".select2-tides-images").select2({
            placeholder: "Select an image",
            minimumInputLength: 2,
            ajax: {
                url: "/api/images/",
                delay: 250,
                dataType: "json",
                data: function (params) {
                    return {
                        query: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data, function (obj) {
                            return { id: obj.id, text: obj.name };
                        }),
                        pagination: {
                            more: params.page * 30 < data.total_count,
                        },
                    };
                },
            },
            templateResult: format,
            templateSelection: formatSelect,
            escapeMarkup: function (m) {
                return m;
            },
        });

        function format(state) {
            if (!state.id) return state.text; // optgroup
            return (
                '<div class="flex items-center"><div>' +
                '<img src="/images/' +
                state.text +
                '" class="mx-auto h-auto w-20 pr-2" />' +
                "</div>" +
                "<div>" +
                state.text.slice(0, 15) +
                "</div>" +
                "</div>"
            );
        }

        function formatSelect(state) {
            if (!state.id) return state.text;
            return state.text;
        }

        const video = document.querySelector("video");

        if (video === null) {
            console.log("Video element not found");
        } else {
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
                    const availableQualities = hls.levels.map((l) => l.height);
                    defaultOptions.language = "de";
                    defaultOptions.iconUrl = "/css/plyr.svg";
                    defaultOptions.loadSripte = false;

                    // Add new qualities to option
                    defaultOptions.quality = {
                        default: availableQualities[0],
                        options: availableQualities,
                        // this ensures Plyr to use Hls to update quality level
                        forced: true,
                        onChange: (e) => updateQuality(e),
                    };

                    // Initialize here
                    const player = new Plyr(video, defaultOptions);
                });
                hls.attachMedia(video);
                window.hls = hls;
            } else {
                // default options with no quality update in case Hls is not supported
                const player = new Plyr(video, {
                    language: "de",
                    iconUrl: "/css/plyr.svg",
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
        }
    },
    false
);

FilePond.registerPlugin(FilePondPluginImagePreview);

const inputElement = document.querySelector('input[type="file"].filepond');
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");
FilePond.create(inputElement).setOptions({
    server: {
        process: "/admin/uploads/process",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
    },
});
