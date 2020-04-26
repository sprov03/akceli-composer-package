<?php

return [
    'string_replacements' => [
        'App\Models\User' => 'App\Models\User'
    ],
    'files_to_remove' => [
        'app/User.php'
    ],
    'file_modifiers' => [
        AkceliFileMofidier::filePath('tests/TestCase.php')->useTrait('Akceli\RealtimeClientStoreSync\Middleware\ClientStoreTestMiddlewareOverwrites'),
    ]
];