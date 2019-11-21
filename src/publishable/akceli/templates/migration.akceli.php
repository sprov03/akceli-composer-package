<?php echo '<?php' . PHP_EOL;
/**
 * @var $migration_timestamp
 * @var $migration_name
 * @var $migration_type
 * @var $table_name
 */

use Illuminate\Support\Str; ?>

use Akceli\Schema\Builders\AkceliRelationshipBuilder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

/**
 *  Documentation: https://laravel.com/docs/5.8/migrations#creating-columns
 */
class <?=Str::studly($migration_name)?> extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
<?php if ($migration_type === 'create'): ?>
        Schema::create('<?=$table_name?>', function (Blueprint $table) {
            $table->bigIncrements('id');

            AkceliRelationshipBuilder::table($table);

            $table->softDeletes();
            $table->timestamps();
        });
<?php else: ?>
        Schema::table('<?=$table_name?>', function (Blueprint $table) {

        });
<?php endif; ?>
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
<?php if ($migration_type === 'create'): ?>
        Schema::drop('<?=$table_name?>');
<?php else: ?>
        Schema::table('<?=$table_name?>', function (Blueprint $table) {
        });
<?php endif; ?>
    }
}
