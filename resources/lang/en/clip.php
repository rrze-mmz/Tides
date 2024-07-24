<?php

return [
    'common' => [
        'no clips' => 'Keine Clips gefunden',
    ],
    'frontend' => [
        'index' => [
            'Clips index' => 'Clips index',
            'Portal has no clips yet!' => 'Portal has no clips yet',
        ],
        'show' => [
            'Back to clip edit page' => 'Back to clip edit page',
        ],
        'not authorized to view video' => 'You are not authorized to view this video!',
        'this clip is exclusively accessible to logged-in users' => 'This clip is exclusively accessible to logged-in'.
                                                                    ' users.',
        'access to this clip is restricted to LMS course participants' => 'Access to this clip is restricted to LMS '.
                                                                            'course participants.',
        'this clip requires a password for access' => 'This clip requires a password for access. '.'
                                                        Please contact the Video Series administrators.',
        'comments' => 'Kommentare',
    ],
    'backend' => [
        'delete' => [
            'modal title' => 'Are you certain you wish to delete the clip titled ":clip_title"?',
            'modal body' => 'Please proceed with caution. Deleting this clip will permanently remove all associated '.
                            'assets, including video files and transcriptions. Once deleted, the clip will no '.
                            'longer be accessible to users.',
        ],
        'delete series connection' => [
            'modal title' => 'Would you like to disassociate the clip from the series titled ":series_title"?',
            'modal body' => 'By removing the clip from the series, it will no longer be a part of this series.'.
                'However, the clip will retain all its information, including metadata, access controls, and '.
                'video files, and will be listed as an independent, standalone clip.',
        ],
    ],
];
