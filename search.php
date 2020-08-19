<?php

require_once('vendor/autoload.php');

$results = [];

if (isset($_GET['q'])) {
    $engine = new \Crockerio\SearchEngine\Search\TutorialSearch();
    $results = $engine->search($_GET['q']);
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
        foreach ($results as $result) : ?>
            <li>
                <?php
                print htmlentities($result[0]);
                ?>
            </li>
        <?php
        endforeach; ?>
    </ul>
</div>
</body>
</html>
