import 'boot';

import {
  Livewire,
  Alpine,
} from '../../vendor/livewire/livewire/dist/livewire.esm';
import jQuery from 'jquery';
import Pikaday from 'pikaday';
import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginMediaPreview from 'filepond-plugin-media-preview';
import 'filepond/dist/filepond.min.css';
import select2 from 'select2';
import { VidstackPlayer } from 'vidstack/global/player';
import 'vidstack/player/styles/base.css';
import {
  generateGeolocationLineChart,
  generatePieChart,
  generateBarChart,
  generateClipViewsLineChart,
} from './chartsSetup.js';

window.jQuery = window.$ = jQuery;
window.Pikaday = Pikaday;
window.select2 = select2();

//import Livewire after the above .js libraries
Livewire.start();

$('.solution-trix-field-wrapper')
  .find($('trix-editor'))
  .css('min-height', '100px');

document.addEventListener('alpine:init', () => {
  Alpine.store('darkMode', {
    on: Alpine.$persist(false),
  });
});

document.addEventListener(
  'DOMContentLoaded',
  function () {
    $('.select2-tides').select2();

    $('.select2-tides-clips').select2({
      allowClear: true,
      placeholder: 'Search for a clip',
      tags: true,
      minimumInputLength: 2,
      ajax: {
        url: '/api/clips/',
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

    $('.select2-tides-tags').select2({
      allowClear: true,
      placeholder: 'Add a tag',
      tags: true,
      minimumInputLength: 2,
      ajax: {
        url: '/api/tags/',
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

    $('.select2-tides-roles').select2({
      allowClear: true,
      placeholder: 'Add a role',
      minimumInputLength: 2,
      ajax: {
        url: '/api/roles/',
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

    $('.select2-tides-presenters').select2({
      allowClear: true,
      placeholder: 'Add a presenter',
      minimumInputLength: 2,
      ajax: {
        url: '/api/presenters/',
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

    $('.select2-tides-users').select2({
      placeholder: 'Search for a user',
      minimumInputLength: 2,
      ajax: {
        url: '/api/users/',
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

    $('.select2-tides-organization').select2({
      placeholder: 'select an organization',
      minimumInputLength: 2,
      ajax: {
        url: '/api/organizations/',
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

    $('.select2-tides-images').select2({
      placeholder: 'Select an image',
      minimumInputLength: 2,
      ajax: {
        url: '/api/images/',
        delay: 250,
        dataType: 'json',
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
        '</div>' +
        '<div>' +
        state.text.slice(0, 15) +
        '</div>' +
        '</div>'
      );
    }

    function formatSelect(state) {
      if (!state.id) return state.text;
      return state.text;
    }
  },
  false
);

FilePond.registerPlugin(FilePondPluginImagePreview);
FilePond.registerPlugin(FilePondPluginMediaPreview);

const inputElement = document.querySelector('input[type="file"].filepond');
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute('content');
FilePond.create(inputElement).setOptions({
  server: {
    process: '/admin/uploads/process',
    revert: '/admin/uploads/revert',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
    },
  },
});

const inputElement1 = document.querySelector(
  'input[type="file"].filepond-input1'
);
const csrfToken1 = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute('content');
FilePond.create(inputElement1).setOptions({
  server: {
    process: '/admin/uploads/process',
    revert: '/admin/uploads/revert',
    headers: {
      'X-CSRF-TOKEN': csrfToken1,
    },
  },
});

const inputElement2 = document.querySelector(
  'input[type="file"].filepond-input2'
);
const csrfToken2 = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute('content');
FilePond.create(inputElement2).setOptions({
  server: {
    process: '/admin/uploads/process',
    revert: '/admin/uploads/revert',
    headers: {
      'X-CSRF-TOKEN': csrfToken2,
    },
  },
});

async function initializePlayer() {
  const player = await VidstackPlayer.create({
    target: document.querySelector('#target'),
  });

  function changeVideoSource(newSource) {
    const currentTime = player.currentTime; // Get the current time of the video
    console.log('currentTime:', currentTime);

    function setNewVideoTime() {
      if (player.readyState >= 2) {
        // Ensure media is ready
        player.currentTime = currentTime; // Set the current time for the new video
        player.play();
      } else {
        setTimeout(setNewVideoTime, 100); // Check again after a short delay
      }
    }

    player.addEventListener('loadedmetadata', setNewVideoTime, { once: true }); // Set the time for standard video formats
    player.src = newSource;
  }

  // Attach event listeners to links
  document.addEventListener('DOMContentLoaded', () => {
    const videoLinks = document.querySelectorAll('.video-link');
    videoLinks.forEach((link) => {
      link.addEventListener('click', function (event) {
        event.preventDefault();
        const videoUrl = this.getAttribute('href');
        changeVideoSource(videoUrl);
      });
    });
  });
}

initializePlayer();
// Assuming the data passed from the Blade template
window.generatePieChart = generatePieChart;
window.generateBarChart = generateBarChart;
window.generateGeolocationLineChart = generateGeolocationLineChart;
window.generateClipViewsLineChart = generateClipViewsLineChart;
