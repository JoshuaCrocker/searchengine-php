<?php

require_once __DIR__ . '/bootstrap.php';

$indices = [];

if (isset($_GET['q'])) {
    $engine = new \Crockerio\SearchEngine\Engine\Search();
    $indices = $engine->search($_GET['q']);
}

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search Engine</title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<div class="container mx-auto px-8 py-8">
    <div class="bg-green-300 p-2 rounded">
        <form action="search.php" method="get">
            <input type="search" name="q" value="<?php
            print isset($_GET['q']) ? $_GET['q'] : ''; ?>" class="w-full px-3 py-2 text-xl rounded"/>
        </form>
    </div>

    <div class="flex flex-wrap justify-around">
        <?php
        foreach ($indices as $index) : ?>
            <div class="flex-none m-1 w-3/12 bg-gray-200 rounded">
                <h4 class="mx-4 mt-2">
                    <a href="<?php print $index->domain->domain; ?>" class="text-blue-700 font-semibold underline"><?php print json_decode($index->document->document)->title; ?></a>
                </h4>
                
                <p class="mx-4 mt-0 mb-2 text-sm text-gray-700">Last crawled: <span><?php print $index->domain->last_crawl_time; ?></span></p>
            </div>
        <?php
        endforeach; ?>
    </div>
</div>
</body>
</html>
