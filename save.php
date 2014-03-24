<?php

require("functions.php");

$id = $_POST["id"] ? : uniqid();
$pageType = $_POST["pageType"];
$title = trim($_POST["title"]);
$html = trim($_POST["html"]);

/*
$tidy = new Tidy();
$options = array('indent' => true, "wrap" => 160, "indent-spaces" => 4);
$tidy->parseString(getLayoutHtml($title, $html), $options);
$tidy->cleanRepair();
$html = (string) $tidy;
*/

file_put_contents(__DIR__ . "/layouts/$pageType/$id.html", getLayoutHtml($title, $html));

echo json_encode(
    array(
        "id"    => $id,
        "title" => $title,
        "html"  => $html,
    )
);
