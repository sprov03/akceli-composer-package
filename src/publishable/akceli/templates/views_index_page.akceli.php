<?php /** @var  TemplateData $table */
use Akceli\TemplateData;?>
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
        <h1><?=$table->ModelNames?> Index Page</h1>
        <a href="/<?=$table->model_names?>/create" class="btn btn-primary">Create New <?=$table->ModelName?></a>
        <table class="table table-striped">
            <thead>
                <tr>
<?php foreach ($table->columns as $column): ?>
                    <th><?=$column->getField()?></th>
<?php endforeach; ?>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($<?=$table->model_names?> as $<?=$table->model_name?>)
                    <tr>
<?php foreach ($table->columns as $column): ?>
                        <td>{{$<?=$table->model_name?>-><?=$column->getField()?>}}</td>
<?php endforeach; ?>
                        <td><a href="/<?=$table->model_names?>/{{$<?=$table->model_name?>->id}}/edit" class="btn btn-primary">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
