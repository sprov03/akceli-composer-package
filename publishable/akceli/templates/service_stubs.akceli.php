<?php echo '<?php' . PHP_EOL;
/** @var  $Service */
use Illuminate\Support\Str; ?>

namespace Tests\Services\<?=Str::studly($Service)?>Service;

class <?=Str::studly($Service)?>ServiceStubs
{
    public static function get<?=Str::studly($Service)?>DataResponse()
    {
        return <<< EOF
            [
                {
                    "id": 1,
                    "name": "Akceli",
                },
                {
                    "id": 2,
                    "name": "Rocks!!",
                },
            ]
EOF;
    }
}
