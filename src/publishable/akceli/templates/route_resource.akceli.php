<?php /** @var  TemplateData $table */use Akceli\TemplateData;?>
Route::resource('/<?=$table->model_names?>', '<?=$table->ModelName?>Controller');
