<?
header("Content-Type:image/svg+xml");
?>
<svg height="30" width="200" xmlns="http://www.w3.org/2000/svg">
    <text x="0" y="22" fill="#DDD" font-size="20px"><?= htmlspecialchars($_GET["text"]) ?></text>
</svg>
