<?php

function getLayoutHtml($title, $innerHtml)
{

    $innerHtml = preg_replace('~\s*$~m', '', $innerHtml);

    return <<<END
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>$title</title>
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap-3.1.1-dist/css/bootstrap.css"/>
    <script src="../js/jquery-2.1.0.js"></script>
    <script src="../vendor/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/extra-styles.css"/>
</head>
<body>

$innerHtml

</body>
</html>

END;
}
