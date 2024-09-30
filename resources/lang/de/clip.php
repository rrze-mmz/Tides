<?php

return [
    'common' => [
        'no clips' => 'Keine Clips gefunden',
        'trending clips' => 'Trendende Clips',
    ],
    'frontend' => [
        'index' => [
            'Clips index' => 'Clip-Index',
            'Portal has no clips yet!' => 'Portal hat noch keine Clips',
        ],
        'show' => [
            'Back to clip edit page' => 'Zurück zur Clip-Bearbeitungsseite',
            'presenter video stream' => 'Präsentator-Videostream',
            'presentation video stream' => 'Präsentations-Videostream',
            'composite video stream' => 'Komposit-Videostream',
            'to LMS course' => 'Zum LMS-Kurs',
            'views' => ':numViews Videoaufrufe',
        ],

        'not authorized to view video' => 'Sie sind nicht berechtigt, dieses Video anzusehen!',
        'this clip is exclusively accessible to logged-in users' => 'Dieser Clip ist ausschließlich für angemeldete'.
                                                                     'Benutzer zugänglich.',
        'access to this clip is restricted to LMS course participants' => 'Der Zugang zu diesem Clip ist auf '.
                                                                            'LMS-Kursteilnehmer beschränkt.',
        'this clip requires a password for access' => 'Für diesen Clip ist ein Passwort erforderlich. '.
                                                        'Bitte kontaktieren Sie die Administratoren der Videoserie.',
        'comments' => 'Kommentare',
    ],
    'backend' => [
        'actions' => [
            'go to clip public page' => 'Zur öffentlichen Clip-Seite gehen',
            'got to clip statistics page' => 'Zur Clip-Statistikseite gehen',
            'generate preview from frame' => 'Vorschau aus Frame erstellen',
            'upload an image' => 'Ein Bild hochladen',
        ],
        'delete' => [
            'modal title' => 'Sind Sie sicher, dass Sie den Clip „:clip_title“ löschen möchten?',
            'modal body' => 'Bitte vorsichtig vorgehen. Das Löschen dieses Clips wird alle zugehörigen Ressourcen,'.
                            ' einschließlich Videodateien und Transkriptionen, dauerhaft entfernen. Nach dem Löschen '.
                            ' ist der Clip für Benutzer nicht mehr zugänglich.',
        ],
        'delete series connection' => [
            'modal title' => 'Möchten Sie den Clip aus der Serie mit dem Titel ":series_title" entfernen?',
            'modal body' => 'Durch das Entfernen des Clips aus der Serie wird dieser nicht mehr Teil dieser Serie'.
               'sein. Der Clip behält jedoch alle seine Informationen, einschließlich Metadaten, Zugriffskontrollen'.
                'und Videodateien, bei und wird als eigenständiger Clip aufgeführt.',
        ],
    ],
];
