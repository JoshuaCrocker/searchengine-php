<?php

require_once __DIR__ . '/bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::connection()->getSchemaBuilder()::defaultStringLength(191);

\Illuminate\Database\Schema\Builder::defaultStringLength(191);

Capsule::schema()->create('domains', function ($table) {
    $table->uuid('id');
    $table->string('domain');//, 2048);
    $table->timestamp('last_crawl_time')->nullable();
    $table->timestamp('last_index_time')->nullable();
    $table->timestamps();
    
    $table->primary('id');
});

Capsule::schema()->create('words', function ($table) {
    $table->uuid('id');
    $table->string('word')->unique();
    $table->timestamps();
    
    $table->primary('id');
});

Capsule::schema()->create('indexes', function ($table) {
    $table->uuid('id');
    $table->uuid('domain_id');
    $table->uuid('document_id');
    $table->uuid('word_id');
    $table->integer('occurrences')->unsigned()->default(0);
    $table->timestamps();
    
    $table->primary('id');
});

Capsule::schema()->create('documents', function ($table) {
    $table->uuid('id');
    $table->text('document');
    $table->timestamps();
    
    $table->primary('id');
});

Capsule::schema()->table('indexes', function ($table) {
    $table->foreign('domain_id')->references('id')->on('domains');
    $table->foreign('word_id')->references('id')->on('words');
    $table->foreign('document_id')->references('id')->on('documents');
});



