<?php echo '<?php' . PHP_EOL;
/**
 * @var string $Command
 * @var string $Signature
 */
?>


namespace Tests\Console\Commands;

use Tests\TestCase;

class <?=$Command?>Test extends TestCase
{
    /**
     * @test
     *
     * Documentation: https://laravel.com/docs/5.8/console-tests
     */
    public function command()
    {
        $this->artisan('<?=$Signature?>')
            //->expectsQuestion('What is your name?', 'Taylor Otwell')
            //->expectsOutput('Your name is Taylor Otwell and you program in PHP.')
            ->assertExitCode(0);
    }
}

