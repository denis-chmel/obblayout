<?
header("Content-Type:image/svg+xml");
$color = "#" . (@$_GET["color"] ?: "DDD");
?>
<svg height="30" width="<?= @$_GET["width"] ?: '' ?>" xmlns="http://www.w3.org/2000/svg">
    <text x="0" y="22" fill="<?= $color ?>" font-size="16px"><?= htmlspecialchars($_GET["text"]) ?></text>
</svg>
