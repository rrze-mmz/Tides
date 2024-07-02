<?php

use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Str;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

// Home > Podcasts
Breadcrumbs::for('frontend.podcasts.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Podcasts Index', route('frontend.podcasts.index'));
});

// Home > Podcasts
Breadcrumbs::for('frontend.podcasts.show', function (BreadcrumbTrail $trail, Podcast $podcast) {
    $trail->parent('frontend.podcasts.index');
    $trail->push($podcast->title, route('frontend.podcasts.show', $podcast));
});

Breadcrumbs::for(
    'frontend.podcasts.episode.show',
    function (BreadcrumbTrail $trail, Podcast $podcast, PodcastEpisode $podcastEpisode) {
        $trail->parent('frontend.podcasts.show', $podcast);
        $trail->push(
            Str::limit($podcastEpisode->episode_number.' - '.$podcastEpisode->title, 60, '...'),
            route('frontend.podcasts.episode.show', [$podcast, $podcastEpisode])
        );
    }
);

Breadcrumbs::for('errors.404', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Page Not Found');
});

//// Home > Podcasts > [Podcast]
//Breadcrumbs::for('podcasts.show', function (BreadcrumbTrail $trail, $podcast) {
//    $trail->parent('frontend.podcasts.index');
//    $trail->push($podcast->title, route('frontend.podcasts.show', $podcast->id));
//});
//
//// Home > Podcasts > [Podcast] > [Episode]
//Breadcrumbs::for('podcasts.episodes.show', function (BreadcrumbTrail $trail, $podcast, $episode) {
//    $trail->parent('podcasts.show', $podcast);
//    $trail->push($episode->title, route('podcasts.episodes.show', [$podcast->id, $episode->id]));
//});
