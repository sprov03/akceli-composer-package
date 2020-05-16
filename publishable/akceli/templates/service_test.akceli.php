<?php echo '<?php' . PHP_EOL;
/** @var  $Service */
use Illuminate\Support\Str; ?>

namespace Tests\<?=Str::studly($Service)?>Services\<?=Str::studly($Service)?>Service;

use App\Services\<?=Str::studly($Service)?>Service\<?=Str::studly($Service)?>Service;
use GuzzleHttp\Client;
use Tests\Services\<?=Str::studly($Service)?>Service\<?=Str::studly($Service)?>ServiceStubs;
use Tests\TestCase;

class <?=Str::studly($Service)?>ServiceTest extends TestCase
{
    public function set<?=Str::studly($Service)?>ServiceMock()
    {
        app()->instance(<?=Str::studly($Service)?>Service::class, new <?=Str::studly($Service)?>Service(app(Client::class)));
    }

    /**
     * @test
     */
    public function canResolve<?=Str::studly($Service)?>Service()
    {
        $this->set<?=Str::studly($Service)?>ServiceMock();
        $this->assertInstanceOf(<?=Str::studly($Service)?>Service::class, <?=Str::studly($Service)?>Service::resolve());
    }

    /**
     * @test
     */
    public function canGet<?=Str::studly($Service)?>Data()
    {
        $mock = $this->getMock(<?=Str::studly($Service)?>Service::class);
        $mock->shouldReceive('get<?=Str::studly($Service)?>Data')->once()->andReturn(<?=Str::studly($Service)?>ServiceStubs::get<?=Str::studly($Service)?>DataResponse());
        $<?=Str::camel($Service)?>Service = <?=Str::studly($Service)?>Service::resolve();

        $this->assertEquals(<?=Str::studly($Service)?>ServiceStubs::get<?=Str::studly($Service)?>DataResponse(), $<?=Str::camel($Service)?>Service->get<?=Str::studly($Service)?>Data());
    }
}
