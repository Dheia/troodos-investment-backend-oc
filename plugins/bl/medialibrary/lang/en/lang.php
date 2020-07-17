<?php return [
    'plugin' => [
        'name' => 'Media Library',
        'description' => 'A plugin for Blupath apps to enable backend administrators to have their own media library.'
    ],
    'mediacategories' => [
        'label' => 'Category name',
        'name' => 'Media category',
        'names' => 'Media categories',
        'comment' => "Category in which this media item belongs"
    ],
    'mediaitems' => [
        'label' => 'Media item title',
        'description' => "Media item description",
        'description_comment' => 'Description of this media item. Will be used as the item description when the item is presented.',
        'comment' => 'Title of this media item. Will be used as the item caption when the item is presented.',
        'name' => 'Media item',
        'names' => 'Media items'
    ],
    'medialibraries' => [
        'label' => 'Media library name',
        'name' => 'Media library',
        'names' => 'Media libraries',
        'comment' => 'Media libraries allow you to store your various media files in different groups.'
    ],
    'parameters' => [
        'batch_details' => 'Batch job file details',
        'batch_title' => 'Title given to all items uploaded to the library in this batch',
        'batch_title_comment' => 'All media items that you upload in the library in this batch will be assigned this title. The title will be shown in all cases where this media is shown to the public with a caption.',
        'batch_description' => 'Description given to all items uploaded to the library in this batch',
        'batch_description_comment' => 'All media items that you upload in the library in this batch will be assigned this description. This will be shown in all cases where this media is shown to the public with a description.',
        'batch_category' => 'Category assigned to all items uploaded to the library in this batch',
        'batch_category_comment' => 'All media items that you upload in the library in this batch will be assigned this category.',
        'tags' => 'Tags',
        'category' => 'Category',
        'file_size' => 'File size',
        'file_selected' => 'File selected',
        'file_format' => 'File format',
        'type' => 'Type',
        'preview' => 'Preview',
        'file_name' => 'File name'
    ],
    'options' => [
        'photo' => 'Photo',
        'audio' => 'Audio',
        'video' => 'Video',
        'document' => 'Document',
        'unknown' => 'Unknown',
    ],
    'tabs' => [
        'photos_label' => 'Media Library photos',
        'images' => 'Images',
        'audios_label' => 'Media Library audios',
        'audio' => 'Audio',
        'videos_label' => 'Media Library videos',
        'video' => 'Video',
        'documents_label' => 'Media Library documents',
        'documents' => 'Documents'
    ]
];
