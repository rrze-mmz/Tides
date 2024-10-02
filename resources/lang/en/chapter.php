<?php

return [
    'common' => [

    ],
    'frontend' => [

    ],
    'backend' => [
        'actions' => [
            'create chapter' => 'Create chapter',
            'edit chapter' => 'Edit chapter',
            'update chapters' => 'Update chapters',
            'delete chapter' => 'Delete chapter',
            'update chapter' => 'Update chapter',
            'back to series chapters' => 'Back to series chapters',
            'add selected clips to chapter' => 'Add selected clips to chapter',
            'remove selected clips from chapter' => 'Remove selected clips from chapter ',
        ],
        'chapters for series' => 'Chapters for Series: :seriesTitle / SeriesID: :seriesID',
        'edit chapter for series' => 'Edit Chapter: :chapterPosition - :chapterTitle 
                                    for series: <span class="italic">:seriesTitle </span>',
        'series chapters info text' => 'Check if a chapter should be the default one or not. One only chapter '.'
                                        can be default',
        'new chapter' => 'New Chapter',
        'chapter title' => 'Chapter Title',
        'series chapters' => 'Series Chapters',
        'no chapters found for' => 'No chapters found for :seriesTitle',
        'default chapter' => 'Default chapter',
        'add clips to chapter' => 'Add clips to chapter',
        'clips for this chapter' => 'Clips for this chapter',
        'delete' => [
            'modal title' => 'Are you certain you wish to delete the chapter ":chapter_title"?',
            'modal body' => 'This action will not delete any clips associated with this chapter. If this chapter '.
                            'has clips, they will be reordered and assigned without any chapter association.',
        ],
    ],
];
