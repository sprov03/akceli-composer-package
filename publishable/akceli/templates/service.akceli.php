<?php echo '<?php' . PHP_EOL;
/** @var  $Service */
use Illuminate\Support\Str; ?>

namespace App\Services\<?=Str::studly($Service)?>Service;

use GuzzleHttp\Client;

/**
 * Class <?=Str::studly($Service)?>Service
 */
class <?=Str::studly($Service)?>Service
{
    /**
     * @var Client
     */
    private $client;

    /**
     * <?=Str::studly($Service)?>Service constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return <?=Str::studly($Service)?>Service
     */
    public static function resolve()
    {
        /** @var <?=Str::studly($Service)?>Service $<?=Str::camel($Service)?>Service */
        $<?=Str::camel($Service)?>Service = app(<?=Str::studly($Service)?>Service::class);

        return $<?=Str::camel($Service)?>Service;
    }

    /**
     * This Method should be removed, Its just here to demonstrate how mocking Services Work out of the Box with Akceli
     */
    public function getExampleData()
    {
        return 'Not Example Data';
    }
}
