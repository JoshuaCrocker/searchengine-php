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
<div>
    <form action="search.php" method="get">
        <input type="search" name="q" value="<?php
        print isset($_GET['q']) ? $_GET['q'] : ''; ?>"/>
    </form>
</div>

<div>
    <ul>
        <?php
        foreach ($indices as $index) : ?>
            <li><?php
                print $index->domain->domain; ?> (<?php
                print $index->domain->id; ?>)
            </li>
        <?php
        endforeach; ?>
    </ul>
</div>
</body>
</html>
