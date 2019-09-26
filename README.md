# laravel-crud-generator

php artisan command to generate fully working crud api endpoints with paginated server side only by having database tables.
This comes with a config file that allows you do reference project templates and dynamic destination paths for the templates
to be published to.


### Installing
```
composer require sprov03/laravel-code-generator --dev
```

### Custom Templates

The best power of this plugin relies on you making your own templates and generating the code the way you like
This step is required for this to work.

Run this command:
```
php artisan vendor:publish
```
and you will have now in resources/templates/ the files you need to modify along with a new config file config/crud.php


### Usage

Use the desired table_name as the input 


CRUD for students table
```
// generate Model and Relationships for the students table
php artisan gen:relationships students

// generate Code Templates for a table/Model
php artisan gen:templates students

// generate Templates and Relationshps Command Combined into One
php artisan gen:full students

// Declare a Namespace For your Model
php artisan gen:full students --namespace=Models\\Users\\Students

// Select another custom Template Configuration other then default
php artisan gen:full students --template-set=api-endpoints

// Allows to overwrite template variables, or inject new ones
php artisan gen:full students --other-variables=action:Dancing|location:SomeLocation

// Will Overwrite Existing files, Mainly used when customizing your templates
php artisan gen:full students --force

// Will Display a full list of variables available to you in your templates
php artisan gen:full students --dump
```
You will be asked a series of questions to determine the relationships that need to be generated.
By then end of the questions your relationships will be generated and placed in the appropriate classes.
Default templates include model, model_test, model_controller, model_controller_test, model_factory, and model_seeder.
The factory, seeder, and test templates require a final touch up that can be taken care of in about a 2 minutes. The model
validation rules come stubbed out but will need tweaking based on the details of your application. 


### Database requirements
```
    /**
     * No relationships defined on the user tabel
     */
    Schema::create('users', function (Blueprint $table) {
        $table->increments('id')->index();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
    });

    /**
     * Site belongs to user and user has many sites
     */
    Schema::create('sites', function (Blueprint $table) {
        $table->increments('id')->index();

        /** A Foreign key is all that is required to setup belongsTo relationship and the reverse. */
        $table->unsignedInteger('user_id')->index();
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });

    /**
     * Form belongs to user and user has many forms
     */
    Schema::create('forms', function (Blueprint $table) {
        $table->increments('id')->index();

        /** A Foreign key is all that is required to setup belongsTo relationship and the reverse. */
        $table->unsignedInteger('user_id')->index();
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });

    /**
     *  Field Types commonly used for drop down selections to enforce data integrity
     *  Notice there is not id and the primary key is name
     *  This is primary key not being id is what triggers the required model template
     *  settings to get applied
     */
    Schema::create('form_field_types', function (Blueprint $table) {
        $table->string('name')->primary();
    });


    Schema::create('form_fields', function (Blueprint $table) {
        $table->increments('id')->index();

        $table->unsignedInteger('form_id')->index();
        $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');

        $table->string('form_field_type')->index();
        $table->foreign('form_field_type')->references('name')->on('form_field_types')->onDelete('cascade');
    });

    /**
     * Many to many Two Foreign keys that are both part of a primary key will notify the script
     * That this is a many to many relationship
     *
     * Note: To Not Create a model for the table use the following line
     *
     * php artisan gen:relationships form_site
     */
    Schema::create('form_site', function (Blueprint $table) {
        $table->unsignedInteger('site_id')->index();
        $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');

        $table->unsignedInteger('form_id')->index();
        $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');

        $table->primary(['site_id', 'form_id']);
    });


    /**
     * Polymorphic MorphTo
     * website type
     * The Key to setting this up is the var_id and var_type on the same table
     * This will set up the polymorphic relationships and build out the Interface
     * Along with a handy Trait. All you have to do is implement the Interface and use the Trait
     * On all the models that implement the interface and they will be ready to go.
     *
     * NOTE: the relationships being build will be the following.
     *      $site->website; website come form the website_id and website_type
     *      $website->site; site come form the model that is being referenced in this case will be the site
     *      You get to decide what the Interface is Called and All the necessary information gets filled out
     */
    Schema::table('sites', function (Blueprint $table) {
        $table->unsignedInteger('website_id')->index();
        $table->string('website_type', '50')->index();
    });
    Schema::create('buying_websites', function (Blueprint $table) {
        $table->increments('id')->index();
    });
    Schema::create('selling_websites', function (Blueprint $table) {
        $table->increments('id')->index();
    });

    /**
     * Polymorphic MorphToMany
     * The same as above just this will be a morphMany in the interface instead of a morphOne
     */
    Schema::create('histories', function (Blueprint $table) {
        $table->increments('id')->index();

        $table->unsignedInteger('subject_id')->index();
        $table->string('subject_type')->index();
    });
```

### WalkThrew

```php
    /** Step 1 */
    php artisan gen:full users
    
    /** Step 2 */
    Open UserFactory.php and fill out the factory requirements.  The Columns are setup so it will be easy
    Get your default data configured.
    
    /** Step 3 */
    Open UserControllerTest and fill out default test data. The info is stubed out and will be qucik to setup each
    test to match what you will be sending form the fornt end.
    
    /** Step 4 */
    Run the test The things that will fail is that all the columns are requied in the fillable array by default.
    This is by design, this is easy to modify and tayler to your application during this testing setup.
    
    /** Step 5 Only for Polymorphic Relationships */
    Impliment the generated Interface and use the Trait by all models that need it.
```
