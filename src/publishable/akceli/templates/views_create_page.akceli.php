<?php /** @var  TemplateData $table */
use Akceli\TemplateData;
use Illuminate\Support\Str; ?>
<html>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

    <body class="container">
        <h1><?=$table->ModelNames?> Edit Page</h1>
        <br>
        <form action="/<?=$table->model_names?>" method="POST" class="form-horizontal">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
<?php foreach ($table->filterDates($table->columns) as $column): ?>
<?php if (($column->getField() === 'id')): ?>
<?php elseif ($column->isString() || $column->isEnum() || $column->isBoolean() || $column->isInteger()): ?>
            <div class="form-group">
                <label for="<?=$column->getField()?>"><?=Str::studly($column->getField())?>:</label>
                <input type="text" name="<?=$column->getField()?>" class="form-control">
            </div>
<?php elseif ($column->isTimeStamp()): ?>
            <div class="form-group">
                <label for="<?=$column->getField()?>"><?=Str::studly($column->getField())?>:</label>
                <input type="text" name="<?=$column->getField()?>" readonly class="form-control">
            </div>
<?php endif ?>
<?php endforeach; ?>

            <div class="form-group">
                <button class="btn btn-primary pull-right">Submit</button>
            </div>
        </form>
    </body>
</html>
