<?php

$pages = array(
    'front' => array(
        "description" => "The index page of a shop. Usually contains most popular products advertisement and contact info.",
        "layouts" => array(),
    ),
    'category' => array(
        "description" => "This page lists products of a certain category.",
        "layouts" => array(),
    ),
    'product' => array(
        "description" => "Page of a certain product",
        "layouts" => array(),
    ),
    'cart' => array(
        "description" => "User may get to here after adding products to cart. If you agree it maybe make sense to add links to related/hot/wholesale products in the footer.",
        "layouts" => array(),
    ),
    'checkout' => array(
        "description" => "To improve focus it make sense to remove everything that may distract user from purchase. The logic of assembling the content is too complicated and therefore not editable, but you may simplify header and/or footer a lot.",
        "layouts" => array(),
    ),
    /*
    'blog' => array(
        "description" => "Page of a certain product",
        "layouts" => array(),
    ),
    */
    'information' => array(
        "description" => "This is a stucture for a set pages like contact us, about, terms & conditions, etc.",
        "layouts" => array(),
    ),
);

foreach (glob(__DIR__ . "/layouts/*", GLOB_ONLYDIR) as $dir) {

    $pageType = basename($dir);

    foreach (glob("$dir/*.html") as $file) {

        $html = file_get_contents($file);

        $title = "";
        if (preg_match("~<title>(.*?)</title>~", $html, $matches)) {
            $title = $matches[1];
        }

        $layout = "";
        if (preg_match("~<main.*?>(.*?)</main>~s", $html, $matches)) {
            $layout = $matches[0];
        }
        if ($layout) {
            $id = str_replace(".", "-", str_replace(".html", "", basename($file)));
            $pages[$pageType]["layouts"][] = array(
                "id"    => $id,
                "title" => $title,
                "html"  => $layout,
            );
        }

    }
}

define("VERSION", uniqid());

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>OBB Layout Constructor</title>
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap-3.1.1-dist/css/bootstrap.css"/>
    <script src="js/jquery-2.1.0.js"></script>
    <script src="vendor/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script>
    <script src="js/layoutBuilder.jquery.js?<?= VERSION ?>"></script>
    <script src="js/main.js?<?= VERSION ?>"></script>
    <script src="vendor/mCustomScrollbar/jquery.mCustomScrollbar.js"></script>

    <link rel="stylesheet" href="/js/jquery-ui/themes/smoothness/jquery-ui.css">
    <script src="/js/jquery-ui/ui/jquery-ui.js"></script>

    <link rel="stylesheet" type="text/css" href="css/extra-styles.css?<?= VERSION ?>"/>
    <link rel="stylesheet" type="text/css" href="css/constructor.css?<?= VERSION ?>"/>
    <link rel="stylesheet" type="text/css" href="vendor/mCustomScrollbar/jquery.mCustomScrollbar.css"/>

    <script src="vendor/growl/jquery.growl.js"></script>
    <link rel="stylesheet" type="text/css" href="vendor/growl/jquery.growl.css"/>

    <script src="vendor/switch/bootstrap-switch.js"></script>
    <link rel="stylesheet" type="text/css" href="vendor/switch/bootstrap-switch.css"/>

    <script src="vendor/tinymce/js/tinymce/tinymce.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet' type='text/css'>

</head>
<body>

<div class="layout-constructor">

    <div id="settings-template">
        <div class="block-controls">
            <div class="block-name"></div>
            <div class="block-controls-buttons">
                <a class="settings"><span class="glyphicon glyphicon-cog"></span></a>
                <a class="delete"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
        </div>
    </div>

    <div id="left" <?= isset($_GET["blue"]) ? ' style="background-color: rgba(0, 95, 160, 0.7)" ' : '' ?>>

        <div class="contents">

            <div id="logo">

                <img src="/img/logo.png">
                <span class="subpage">Layout Builder</span>

            </div>

            <div id="pages-switcher" class="list-group" style="margin-top: 30px">
                <? foreach (array_keys($pages) as $i => $pageType): ?>
                    <a href="#" class="list-group-item <?= $i ? "" : " active " ?>" data-value="<?= $pageType ?>"><?= ucfirst($pageType) ?> page</a>
                <? endforeach ?>
                <? /* <a href="#" class="list-group-item" data-value="new">New page</a> */ ?>
            </div>

            <!--
            Blue-line delay:
            <input type="text" class="input" value="600" style="color: #000" size="4" id="blue-line-delay">&nbsp;ms


            <hr>
            -->




            <h5>Grids</h5>

            <div class="wrap">


                <div class="row">
                    <div class="col-min-150">

                        <div class="draggable-block">
                            <div class="block-label">
                                <img src="/img/icons/grid2-8-2.svg" width="16" height="16"> Grid 6/6
                            </div>
                            <div class="block-code">
                                <div class="grid-row row">
                                    <div class="sortable col-md-6"></div>
                                    <div class="sortable col-md-6"></div>
                                </div>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">
                                <img src="/img/icons/grid2-8-2.svg" width="16" height="16"> Grid 3/9
                            </div>
                            <div class="block-code">
                                <div class="grid-row row">
                                    <div class="sortable col-md-3"></div>
                                    <div class="sortable col-md-9"></div>
                                </div>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">
                                <img src="/img/icons/grid2-8-2.svg" width="16" height="16"> Grid 9/3
                            </div>
                            <div class="block-code">
                                <div class="grid-row row">
                                    <div class="sortable col-md-9"></div>
                                    <div class="sortable col-md-3"></div>
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="col-min-150">

                        <div class="draggable-block">
                            <div class="block-label">
                                <img src="/img/icons/grid4-4-4.svg" width="16" height="16"> Grid 4/4/4
                            </div>
                            <div class="block-code">
                                <div class="grid-row row">
                                    <div class="sortable col-md-4"></div>
                                    <div class="sortable col-md-4"></div>
                                    <div class="sortable col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">
                                <img src="/img/icons/grid4-4-4.svg" width="16" height="16"> Grid 3/6/3
                            </div>
                            <div class="block-code">
                                <div class="grid-row row">
                                    <div class="sortable col-md-3"></div>
                                    <div class="sortable col-md-6"></div>
                                    <div class="sortable col-md-3"></div>
                                </div>
                            </div>
                        </div>


                        <div class="draggable-block">
                            <div class="block-label">
                                <img src="/img/icons/grid2-8-2.svg" width="16" height="16"> Grid 2/8/2
                            </div>
                            <div class="block-code">
                                <div class="grid-row row">
                                    <div class="sortable col-md-2"></div>
                                    <div class="sortable col-md-8"></div>
                                    <div class="sortable col-md-2"></div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="col-min-150">


                        <div class="draggable-block">
                            <div class="block-label">
                                <img src="/img/icons/grid3-3-3-3.svg" width="16" height="16"> Grid 3/3/3/3
                            </div>
                            <div class="block-code">
                                <div class="grid-row row">
                                    <div class="sortable col-md-3"></div>
                                    <div class="sortable col-md-3"></div>
                                    <div class="sortable col-md-3"></div>
                                    <div class="sortable col-md-3"></div>
                                </div>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-tasks"></span>
                                Custom grid...
                            </div>
                            <div class="block-code">
                                <div class="grid-row row customize">
                                    <div class="sortable col-md-6"></div>
                                    <div class="sortable col-md-6"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <h5>Blocks</h5>

            <div class="wrap">

                <div id="draggable-blocks" class="row">
                    <div class="col-min-150">

                        <div class="draggable-block">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-map-marker"></span> Map address
                            </div>
                            <div class="block-code">
                                <div class="google-map"></div>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-shopping-cart"></span> Breadcrumb
                            </div>
                            <div class="block-code">
                                <ol class="breadcrumb">
                                    <li><a href="#">Home</a></li>
                                    <li><a href="#">Library</a></li>
                                    <li class="active">Data</li>
                                </ol>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-align-justify"></span> Custom text / HTML
                            </div>
                            <div class="block-code">
                                <div class="custom-text-html">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt tellus eget tortor elementum pellentesque. Vivamus a ornare turpis. Curabitur quis urna in erat elementum dapibus et et sem. Morbi congue sem vitae urna adipiscing laoreet. Ut lectus elit, vulputate ac metus vitae, cursus malesuada purus. Pellentesque tristique egestas elit, vitae rhoncus est sagittis vitae. Morbi lobortis condimentum massa eget vehicula. Duis nec massa vel mauris dignissim fermentum eget vel nulla. Pellentesque quis nunc mattis, mollis nibh vel, ornare sapien.</div>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-info-sign"></span> Info pages links
                            </div>
                            <div class="block-code">
                                <p class="info-pages-links">
                                    <a href="#">Privacy Notice</a><br>
                                    <a href="#">Conditions of Use</a><br>
                                    <a href="#">Sitemap</a><br>
                                    <a href="#">Newsletter</a><br>
                                </p>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-info-sign"></span> Account pages links
                            </div>
                            <div class="block-code">
                                <p class="account-pages-links">
                                    <a href="#">My Account</a><br>
                                    <a href="#">Edit Account</a><br>
                                    <a href="#">Password</a><br>
                                    <a href="#">Address Books</a><br>
                                    <a href="#">Wish List</a><br>
                                    <a href="#">Order History</a><br>
                                    <a href="#">Newsletter</a><br>
                                    <a href="#">Logout</a>
                                </p>
                            </div>
                        </div>

                        <div class="draggable-block todo">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-tags"></span> Supported credit cards
                            </div>
                            <div class="block-code">
                                <p>TODO</p>
                            </div>
                        </div>

                        <div class="draggable-block todo">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-remove"></span> Top featured products
                            </div>
                            <div class="block-code">
                                <p>TODO</p>
                            </div>
                        </div>

                        <div class="draggable-block todo">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-remove"></span> Top bestsellers
                            </div>
                            <div class="block-code">
                                <p>TODO</p>
                            </div>
                        </div>

                        <div class="draggable-block todo">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-remove"></span> Newest products
                            </div>
                            <div class="block-code">
                                <p>TODO</p>
                            </div>
                        </div>

                        <div class="draggable-block todo">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-remove"></span> Wholesale products
                            </div>
                            <div class="block-code">
                                <p>TODO</p>
                            </div>
                        </div>

                        <div class="draggable-block todo">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-shopping-cart"></span> Shopping cart
                            </div>
                            <div class="block-code">
                                <p>TODO</p>
                            </div>
                        </div>


                    </div>
                    <div class="col-min-150">

                        <div class="draggable-block">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-search"></span> Search
                            </div>
                            <div class="block-code">
                                <div class="input-group" style="padding: 5px;">
                                    <input type="text" class="form-control">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="draggable-block todo">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-tags"></span> Contact us form
                            </div>
                            <div class="block-code">
                                <p>TODO</p>
                            </div>
                        </div>

                        <div class="draggable-block todo">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-tags"></span> Subscribe form
                            </div>
                            <div class="block-code">
                                <p>TODO</p>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-picture"></span> Shop logo
                            </div>
                            <div class="block-code">
                                <div class="shop-logo">
                                    <div class="block-title">
                                        <span class="glyphicon glyphicon-picture"></span> Shop Logo
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-font"></span> Shop Title
                            </div>
                            <div class="block-code">
                                <h2 class="shop-title">Shop Title</h2>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-picture"></span> Custom Banner / Image
                            </div>
                            <div class="block-code">
                                <div class="shop-logo">
                                    <div class="block-title">
                                        <span class="glyphicon glyphicon-picture"></span> Banner
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">

                                <span class="glyphicon glyphicon-th-list"></span> Categories navigation
                            </div>

                            <div class="block-code">
                                <ul class="nav nav-pills">
                                    <li class="active"><a href="#">Jewelry</a></li>
                                    <li><a href="#">Electronics & Appliances</a></li>
                                    <li><a href="#">Clothing</a></li>
                                    <li><a href="#">Interior Design</a></li>
                                    <li><a href="#">Bags & Wallets</a></li>
                                    <li><a href="#">Shoes</a></li>
                                </ul>
                            </div>
                        </div>


                        <div class="draggable-block todo">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-remove"></span> Currency switcher
                            </div>
                            <div class="block-code">
                                <p>TODO</p>
                            </div>
                        </div>

                        <div class="draggable-block todo">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-remove"></span> Localization switcher
                            </div>
                            <div class="block-code">
                                <p>TODO</p>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-film"></span> Images carousel
                            </div>
                            <div class="block-code">
                                <div id="carousel-example" class="carousel slide" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                        <li data-target="#carousel-example" data-slide-to="0" class="active"></li>
                                        <li data-target="#carousel-example" data-slide-to="1"></li>
                                        <li data-target="#carousel-example" data-slide-to="2"></li>
                                    </ol>
                                    <div class="carousel-inner">
                                        <div class="item active">
                                            <h1>Product 1</h1>
                                            <p class="description">A trendy tool to cycle products advertisement. Products which are promoted to front page are rotated here.</p>
                                        </div>
                                        <div class="item">
                                            <h1>Product 2</h1>
                                            <p class="description">A trendy tool to cycle products advertisement. Products which are promoted to front page are rotated here.</p>
                                        </div>
                                        <div class="item">
                                            <h1>Product 3</h1>
                                            <p class="description">A trendy tool to cycle products advertisement. Products which are promoted to front page are rotated here.</p>
                                        </div>
                                    </div>
                                    <a class="left carousel-control" data-target="#carousel-example" data-slide="prev">
                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                    </a>
                                    <a class="right carousel-control" data-target="#carousel-example" data-slide="next">
                                        <span class="glyphicon glyphicon-chevron-right"></span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="draggable-block">
                            <div class="block-label">
                                <span class="glyphicon glyphicon-shopping-cart"></span> Checkout button
                            </div>
                            <div class="block-code">
                                <div class="btn btn-danger">Checkout</div>
                            </div>
                        </div>



                    </div>
                </div>

            </div>

        </div>

    </div>
    <div id="right">

        <div class="header">

            <!--
            <div class="btn-group btn-group-device">
                <button type="button" class="btn btn-sm btn-info" data-device="phone">Phone</button>
                <button type="button" class="btn btn-sm btn-info" data-device="tablet">Tablet</button>
                <button type="button" class="btn btn-sm btn-info active" data-device="desktop">Desktop</button>
                <button type="button" class="btn btn-sm btn-info" data-device="wide">Wide</button>
            </div>
            -->


        </div>

        <div id="sandbox">

            <div class="obb-page">

                <div class="next-prev-buttons">
                    <!--
                    <button class="btn btn-sm1 btn-info btn-saved-layouts-prev"><span class="glyphicon glyphicon-chevron-left"></span> Back</button>
                    <button class="btn btn-sm1 btn-info btn-saved-layouts-next">Next <span class="glyphicon glyphicon-chevron-right"></span></button>
                    -->
                    <button type="button" class="btn btn-sm btn-info disabled btn-undo">Undo</button>
                    <input id="btn-toggle-grid" data-on-color="info" type="checkbox" data-size="mini" data-label-text="<span class='grid-icon'><table><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table></span>">
                    <button type="button" class="btn btn-info" id="btn-help">Help</button>


                </div>

                <div id="header-layout-presets">
                    <div class="layout-preset" data-title="header 1">
                        <header>
                            <div class="grid-row row">
                                <div class="sortable col-md-3">
                                    <div class="shop-logo">
                                        <div class="block-title">
                                            <span class="glyphicon glyphicon glyphicon-picture"></span> Shop Logo
                                        </div>
                                    </div>
                                </div>
                                <div class="sortable col-md-9">
                                    <h2 class="shop-title">Shop Title</h2>
                                    <ol class="breadcrumb">
                                        <li><a href="#">Home</a></li>
                                        <li><a href="#">Library</a></li>
                                        <li class="active">Data</li>
                                    </ol>
                                </div>
                            </div>
                        </header>
                    </div>
                    <div class="layout-preset" data-title="header 2">
                        <header>
                            <div class="grid-row row">
                                <div class="sortable col-md-3">
                                    <h2 class="shop-title">
                                        Shop Title</h2>

                                </div>
                                <div class="sortable col-md-9">
                                    <div class="shop-logo">
                                        <div class="block-title">
                                            <span class="glyphicon glyphicon glyphicon-picture"></span> Shop Logo
                                        </div>
                                    </div>
                                    <ol class="breadcrumb">
                                        <li><a href="#">Home</a></li>
                                        <li><a href="#">Library</a></li>
                                        <li class="active">Data</li>
                                    </ol>
                                </div>
                            </div>
                        </header>
                    </div>
                    <div class="layout-preset" data-title="new header">
                        <header></header>
                    </div>
                </div>

                <div id="footer-layout-presets">
                    <div class="layout-preset" data-title="footer 1">
                        <footer>
                            <div class="grid-row row">
                                <div class="sortable col-md-3">
                                    <p class="info-pages-links">
                                        <a href="#">Privacy Notice</a><br>
                                        <a href="#">Conditions of Use</a><br>
                                        <a href="#">Sitemap</a><br>
                                        <a href="#">Newsletter</a><br>
                                    </p>
                                </div>
                                <div class="sortable col-md-3">
                                    <p class="account-pages-links">
                                        <a href="#">My Account</a><br>
                                        <a href="#">Edit Account</a><br>
                                        <a href="#">Password</a><br>
                                        <a href="#">Address Books</a><br>
                                        <a href="#">Wish List</a><br>
                                        <a href="#">Order History</a><br>
                                        <a href="#">Newsletter</a><br>
                                        <a href="#">Logout</a>
                                    </p>
                                </div>
                                <div class="sortable col-md-3"></div>
                                <div class="sortable col-md-3"></div>
                            </div>
                        </footer>
                    </div>
                    <div class="layout-preset" data-title="footer image">
                        <footer>
                            <div class="shop-logo">
                                <div class="block-title">
                                    <span class="glyphicon glyphicon-picture"></span> Banner
                                </div>
                            </div>
                        </footer>
                    </div>
                    <div class="layout-preset" data-title="new footer">
                        <footer></footer>
                    </div>
                </div>

                <div class="layouts">

                    <? foreach (array_keys($pages) as $i => $pageType): ?>

                            <div class="layout obb-page-<?= $pageType ?> <?= $i ? " locked-header locked-footer " : "" ?>" data-obb-page="<?= $pageType ?>">

                                <div class="layout-presets">
                                    <? foreach ($pages[$pageType]["layouts"] as $layout): ?>
                                        <div class="layout-preset" data-title="<?= $layout["title"] ?>"><?= $layout["html"]?></div>
                                    <? endforeach ?>
                                    <div class="layout-preset" data-title="new layout"><main></main></div>
                                </div>

                                <h2>
                                    <?= ucfirst($pageType) ?> page

                                    <span class="page-buttons">
                                        <button type="button" class="btn btn-sm btn-warning disabled btn-save-layout">Saved</button>
                                    </span>
                                </h2>

                                <p style="max-width: 800px"><?= $pages[$pageType]["description"] ?></p>

                                <? if (empty($pages[$pageType]["layouts"])): ?>

                                    <div class="page-html">
                                    <div class="container">
                                        <header></header>
                                        <main class="editing-disabled"></main>
                                        <footer></footer>
                                    </div>
                                    </div>

                                <? else: ?>

                                <? $layout = $pages[$pageType]["layouts"][0]; ?>
                                <div class="page-html">
                                    <div class="container">
                                    <header>
                                        <div class="grid-row row">
                                            <div class="sortable col-md-3">
                                                <div class="shop-logo">
                                                    <div class="block-title">
                                                        <span class="glyphicon glyphicon glyphicon-picture"></span> Shop Logo
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="sortable col-md-9">
                                                <h2 class="shop-title">Shop Title</h2>
                                                <ol class="breadcrumb">
                                                    <li><a href="#">Home</a></li>
                                                    <li><a href="#">Library</a></li>
                                                    <li class="active">Data</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </header>
                                    <?= $layout["html"] ?>
                                    <footer>
                                        <div class="grid-row row">
                                            <div class="sortable col-md-3">
                                                <p class="info-pages-links">
                                                    <a href="#">Privacy Notice</a><br>
                                                    <a href="#">Conditions of Use</a><br>
                                                    <a href="#">Sitemap</a><br>
                                                    <a href="#">Newsletter</a><br>
                                                </p>
                                            </div>
                                            <div class="sortable col-md-3">
                                                <p class="account-pages-links">
                                                    <a href="#">My Account</a><br>
                                                    <a href="#">Edit Account</a><br>
                                                    <a href="#">Password</a><br>
                                                    <a href="#">Address Books</a><br>
                                                    <a href="#">Wish List</a><br>
                                                    <a href="#">Order History</a><br>
                                                    <a href="#">Newsletter</a><br>
                                                    <a href="#">Logout</a>
                                                </p>
                                            </div>
                                            <div class="sortable col-md-3"></div>
                                            <div class="sortable col-md-3"></div>
                                        </div>
                                    </footer>
                                    </div>

                                </div>
                                <? endif ?>

                                <? /*
                                <div id="copyright">
                                    <div class="text-center text-muted credit">Content Copyright © Golden Planet ApS</div>
                                    <div class="text-center text-muted credit">OpenBizBox System Copyright © <a target="_blank" href="http://www.goldenplanet.com/">Golden Planet</a>
                                    </div>
                                </div>
                                */ ?>

                            </div>

                    <? endforeach ?>

                    <? /*
                    <div class="layout obb-page-new">

                        <h2>
                            New page

                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-warning btn-save-layout">Save as</button>
                            </div>

                        </h2>

                        <p style="color: #999">Left here to ignite imagination :)</p>

                        <div class="page-html">
                        <div class="container">

                            <header class="sortable"></header>
                            <main class="sortable"></main>
                            <footer class="sortable"></footer>

                        </div>
                        </div>
                    </div>
                    */ ?>

                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="grid-row-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-vertical-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Grid settings</h4>
                </div>
                <div class="modal-body">


                    <h5>Cells count</h5>

                    <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group btn-group-col-counts">
                            <button class="btn btn-default">1</button>
                            <button class="btn btn-default">2</button>
                            <button class="btn btn-default">3</button>
                            <button class="btn btn-default active">4</button>
                            <button class="btn btn-default">5</button>
                            <button class="btn btn-default">6</button>
                            <button class="btn btn-default">7</button>
                            <button class="btn btn-default">8</button>
                            <button class="btn btn-default">9</button>
                            <button class="btn btn-default">10</button>
                            <button class="btn btn-default">11</button>
                            <button class="btn btn-default">12</button>
                        </div>
                    </div>

                    <br>

                    <h5>Cells proportions (drag blue lines)</h5>

                    <div class="grid-setup">
                        <div class="grid-setup-line" style="left: 40px"></div>
                        <div class="grid-setup-line" style="left: 80px"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-save" data-dismiss="modal">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="custom-text-html-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-vertical-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Text / Custom HTML</h4>
                </div>
                <div class="modal-body">

                    <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group btn-group-language-switcher">
                            <button class="btn btn-default active" data-lang-code="en">English</button>
                            <button class="btn btn-default" data-lang-code="da">Danish</button>
                            <button class="btn btn-default" data-lang-code="de">German</button>
                        </div>
                    </div>

                    <div class="lang-dependent lang-en">
                        <textarea id="content-en" name="content[en]" class="wysiwyg"></textarea>
                    </div>
                    <div class="lang-dependent lang-da hidden">
                        <textarea id="content-da" name="content[da]" class="wysiwyg"></textarea>
                    </div>
                    <div class="lang-dependent lang-de hidden">
                        <textarea id="content-de" name="content[de]" class="wysiwyg"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-save" data-dismiss="modal">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="shop-logo-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-vertical-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Shop logo settings</h4>
                </div>
                <div class="modal-body">
                    ...<br>...<br>...<br>...<br>...<br>...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-save" data-dismiss="modal">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="component-tabs-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-vertical-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Tabs</h4>
                </div>
                <div class="modal-body">

                    <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group btn-group-language-switcher">
                            <button class="btn btn-default active" data-lang-code="en">English</button>
                            <button class="btn btn-default" data-lang-code="da">Danish</button>
                            <button class="btn btn-default" data-lang-code="de">German</button>
                        </div>
                    </div>

                    <div class="grid-row row">
                        <div class="col-md-6">

                            <div class="lang-dependent lang-en">
                                <textarea class="form-control" rows="6"><?=
                                    <<<EOF
Wholesale
Bestsellers
Reach us
- Address
- Leave a message

EOF;
                                    ?></textarea>
                            </div>
                            <div class="lang-dependent lang-da hidden">
                                <textarea class="form-control" rows="6"><?=
                                    <<<EOF
Wholesale
Bestsellere
Kontakt os
- Adresse
- Efterlad en besked

EOF;
                                    ?></textarea>
                            </div>
                            <div class="lang-dependent lang-de hidden">
                                <textarea class="form-control" rows="6"><?=
                                    <<<EOF
Großhandel
Bestseller
Kontaktieren Sie uns
- Adresse
- Lassen Sie eine Nachricht

EOF;
                                    ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            Enter tab labels one per line, optionally translate to languages. Ensure that if translation is present it contains same amount of tabs.
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-save">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="save-as-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-vertical-centered">
            <div class="modal-content">
                <form method="get">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Save layout as...</h4>
                    </div>
                    <div class="modal-body">

                        <form method="get">
                            <div class="form-group">
                                <label for="layout_name">Name:</label>
                                <input class="form-control" id="layout_name" required="required" name="layout_name" value="" type="text">

                                <p class="help-block">Isn't shown for visitors. Name it somehow for yourself.</p>
                            </div>

                            <!--
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Publish after saving
                                </label>
                            </div>
                            -->

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary disabled btn-save">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {

            // $("#sandbox").layoutBuilder();
            $("#left").mCustomScrollbar({
                scrollInertia: 300
            });

        });
    </script>

    <div id="help" style="display1: block">
        <div class="help-text">

            <svg width="1200" height="1200" xmlns="http://www.w3.org/2000/svg">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->

                <g class="step">
                    <path d="m359,227c-1,-1 -2,-3 -3,-4c-2,-2 -3.794891,-4.220215 -7,-7c-3.777283,-3.276016 -9.084534,-7.850754 -18,-13c-10.098572,-5.832565 -19.817291,-13.347855 -31,-19c-14.773132,-7.466873 -29.889801,-14.296509 -43,-19c-11.906067,-4.271484 -23.98056,-9.130737 -37,-11c-14.979141,-2.150635 -27.885345,-4.997086 -41,-6c-12.962158,-0.991257 -22,0 -31,0c-9,0 -17.790253,-0.990234 -25,-2c-6.932343,-0.970901 -11,0 -15,0c-2,0 -5,0 -9,0c-2,0 -4,1 -6,1c-3,0 -5,0 -8,0c-3,0 -5,0 -6,0c-1,0 -2,0 -3,0c-1,0 -2,0 -3,0c-1,0 -2.292892,-0.707108 -3,0c-0.707108,0.707108 1.186005,1.692551 3,3c2.29454,1.653809 3.692551,4.186005 5,6c0.826904,1.147263 2.458801,1.693436 3,3c0.382683,0.923874 1.923882,1.61731 1,2c-1.306564,0.541199 -2,-2 -3,-3c-1,-1 -2.173096,-1.852737 -3,-3c-1.307449,-1.813995 -1.692551,-3.186005 -3,-5c-0.826904,-1.147263 -2,-2 -4,-4c-1,-1 -2.076118,-1.61731 -3,-2c-1.306564,-0.541199 -1.292892,-1.292892 -2,-2c-0.707108,-0.707108 1,-1 2,-1c1,0 2,0 3,0c2,0 4,0 6,-1c2,-1 4.881523,-2.190277 8,-4c1.933998,-1.122345 4.186005,-1.692551 6,-3c1.14727,-0.826904 2,-2 3,-2c1,0 1.292892,-0.292892 2,-1c0.707108,-0.707108 2,0 1,0c-1,0 -2.186005,-0.307449 -4,1c-1.14727,0.826904 -3,1 -6,2c-3,1 -6.041321,1.847229 -10,3c-3.036163,0.88414 -5.078438,1.789856 -8,3c-2.065857,0.855713 -5.186008,1.692551 -7,3c-1.14727,0.826904 -2.292892,0.292892 -3,1c-0.707108,0.707108 -1.52573,1.149353 -1,2c1.175571,1.902115 3,2 4,3c1,1 1.85273,2.173096 3,3c1.813995,1.307449 5,2 6,3c1,1 2,2 3,3c1,1 2,2 3,3c1,1 2,2 3,3c1,1 2,1 2,2l1,1" id="svg_11" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="5" stroke="#000000" fill="none"/>
                    <text xml:space="preserve" text-anchor="middle" font-family="serif" font-size="24" id="svg_12" y="275" x="407" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#000000">1. Choose page for which</text>
                    <text style="cursor: move;" xml:space="preserve" text-anchor="middle" font-family="serif" font-size="24" id="svg_13" y="304" x="415" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#000000">you want to alter the layout</text>
                </g>

                <g class="step">
                    <path stroke="#000000" d="m638,187c-1.439331,0 -2.666626,-0.737259 -4.317932,-1.256943c-2.610901,-0.821686 -5.757202,-2.51387 -10.075134,-4.399261c-4.317932,-1.885406 -18.378479,-7.774796 -41.739868,-20.739426c-12.105347,-6.717926 -23.512756,-13.479034 -43.179199,-25.767136c-8.244629,-5.151443 -19.631653,-11.700325 -37.421997,-25.138687c-10.635193,-8.033585 -20.150269,-16.340141 -40.300537,-32.680283c-10.075195,-8.170074 -22.227112,-15.926254 -34.543335,-28.909485c-10.450684,-11.016647 -10.075134,-12.569336 -12.953796,-13.826279c-1.43927,-0.628464 0.109558,-0.868973 1.439331,-0.628464c1.880493,0.340134 4.317932,1.256943 5.757202,1.885406c1.439331,0.628464 3.146301,1.063705 5.757263,1.885391c1.651245,0.519684 3.127747,1.164398 4.317871,1.885406c1.881836,1.140045 1.925049,2.70871 4.317932,4.399277c2.140259,1.512085 4.317932,1.885391 5.757263,2.513855c1.43927,0.628479 2.878601,0.628479 4.317871,1.256943c1.439331,0.628464 2.988159,1.016418 4.317932,1.256927c1.880554,0.340134 3.300232,0.812546 4.317932,1.256943c1.0177,0.444397 1.439331,1.885391 2.878601,1.256927c1.43927,-0.628464 -3.146301,-1.063705 -5.757263,-1.885391c-1.651245,-0.519684 -2.87854,-1.256943 -4.317871,-1.885406c-1.439331,-0.628464 -2.878601,-1.256943 -4.317932,-1.885406c-1.43927,-0.628464 -1.860901,-0.812531 -2.878601,-1.256927c-1.0177,-0.444397 -0.888489,-0.676315 -1.439331,-1.256943c-0.778931,-0.821121 -1.548828,-1.016418 -2.878601,-1.256927c-1.880493,-0.340118 -1.43927,-1.256927 -2.878601,-1.885406c-1.439331,-0.628464 -1.439331,-1.256927 -2.878662,-1.885391c-1.43927,-0.628479 -2.87854,-1.256943 -4.317871,-1.885406c-1.43927,-0.628464 -1.43927,-1.256927 -2.878601,-1.885406c-1.439331,-0.628464 -2.98822,-1.016418 -4.317932,-1.256927c-3.761108,-0.680252 -4.427429,-1.644897 -5.757202,-1.885406c-1.880554,-0.340118 -2.878601,-1.256927 -4.317993,-1.256927c-1.43927,0 -2.87854,0 -4.317871,0c-1.43927,0 -3.300171,-0.444397 -4.317932,0c-1.0177,0.444397 -1.43927,1.256927 -1.43927,1.885406c0,0.628464 0.992065,1.307358 0,3.142334c-0.739441,1.367691 -1.439331,3.142334 -1.439331,5.656204c0,3.142334 0,7.541595 -2.878601,13.1978c-2.878601,5.656204 -2.878601,10.055481 -4.317932,13.197815l0,2.513855l0,1.256943l0,0.628464" id="svg_8" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="5" fill="none"/>
                    <text style="cursor: move;" xml:space="preserve" text-anchor="middle" font-family="serif" font-size="24" id="svg_9" y="210" x="825" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#000000">2. Choose page layout from preset</text>
                    <text xml:space="preserve" text-anchor="middle" font-family="serif" font-size="24" id="svg_14" y="240" x="830" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="0" stroke="#000000" fill="#000000">edit it or create your own</text>
                </g>


                <g class="step">
                    <path stroke="#000000" d="m2,606c0,-1 0,-2 2.086521,-3c2.086521,-1 2.086521,-2 4.173042,-3c2.086521,-1 4.173042,-2 6.259563,-4c2.086521,-2 4.173046,-4 8.346088,-8c4.173038,-4 12.021328,-10.141846 20.865215,-17c7.91021,-6.134155 14.269058,-12.170471 20.865211,-15c9.955101,-4.270386 21.350822,-9.733276 33.384338,-15c13.073578,-5.721924 24.483826,-12.278076 37.557388,-18c12.033524,-5.266724 24.871948,-9.113525 39.643906,-14c12.382935,-4.096222 24.750183,-6.475464 37.557388,-8c12.317551,-1.466278 25.038254,-3 37.557388,-5c12.519135,-2 24.531464,-3.986908 37.557373,-5c12.357483,-0.96109 22.822296,-2.498291 35.470856,-3c12.476471,-0.494873 23.012695,-0.591217 35.470886,0c10.587433,0.502441 22.716614,3.376678 35.470856,5c10.282806,1.308746 19.638489,1.968567 27.124786,4c5.135559,1.393555 8.346069,3 10.432587,4c4.173065,2 8.734192,2.692566 12.519135,4c2.393799,0.826904 3.374542,1.076111 4.173035,2c1.129242,1.30658 3.531586,1.186005 6.259583,3c1.725342,1.147278 4.173035,3 6.259552,4c2.086517,1 4.173035,2 6.259583,3c2.086517,1 3.533417,2.541199 6.259552,2c1.927734,-0.38269 0,-4 0,-6c0,-2 0,-4 0,-7c0,-2 -1.083893,-5.038727 -2.086517,-8c-1.056885,-3.121429 -4.173035,-6 -4.173035,-9c0,-4 2.395477,-6.228363 0,-9c-1.129242,-1.306549 -2.086548,-2 -2.086548,-3c0,-1 1.927734,-2.61731 0,-3c-2.726135,-0.541199 -2.086517,4 -2.086517,8c0,5 -4.173035,9 -4.173035,12c0,3 0,6 0,9c0,4 -1.97287,10.09021 0,15c2.124847,5.287964 5.130341,9.693481 6.259552,11c0.798492,0.923889 -2.086517,3 0,3c2.086548,0 0,-2 -2.086517,-3c-2.086517,-1 -4.173035,-2 -6.259552,-3c-2.086517,-1 -5.835999,-2.25647 -8.3461,-3c-5.612701,-1.662476 -10.295197,-2.144287 -14.605652,-3c-6.095917,-1.210144 -10.820709,-0.692566 -14.605621,-2c-2.393799,-0.826904 -4.173065,-1 -6.259583,-1c-2.086517,0 -2.086517,-1 -4.173035,-1c-2.086517,0 -4.173035,0 -6.259552,0c-2.086548,0 -4.173065,0 -6.259583,0l-2.086517,0l-2.086517,0" id="svg_1" stroke-width="5" fill="none"/>
                    <text style="cursor: move;" xml:space="preserve" text-anchor="middle" font-family="serif" font-size="24" id="svg_2" y="568" x="451" stroke-width="0" stroke="#000000" fill="#000000">3. Drag items onto canvas</text>
                </g>

                <g class="step">
                    <path d="m612,509c2,2 6.150513,3.658447 11,6c7.260254,3.505554 14.041809,5.708557 22,7c10.066345,1.633545 20.875916,4.996582 33,6c12.95575,1.072266 25,0 38,0c12,0 25,2 38,2c14,0 29.072693,-1.514832 44,-4c15.089355,-2.512146 30.045715,-5.779846 45,-9c13.042725,-2.808594 26.083069,-7.51947 39,-10c13.102295,-2.516083 26,-8 39,-13c13,-5 26,-10 39,-15c13,-5 24.063599,-9.732605 33,-12c7.056519,-1.790436 12,-4 13,-5c1,-1 2.458801,-1.693451 3,-3c0.38269,-0.923889 0,-2 0,-3c0,-1 -1.173096,-0.147278 -2,1c-1.307434,1.813995 -2.144287,4.934143 -3,7c-1.210144,2.92157 -3.779602,10.114075 -8,18c-3.80426,7.108276 -7.862671,15.080261 -12,22c-4.841248,8.097046 -8.118958,15.215393 -10,21c-1.382996,4.25293 -3,5 -2,4c1,-1 0.770264,-2.026733 1,-3c0.513733,-2.17627 1,-5 2,-8c1,-3 1.610657,-6.159241 3,-9c1.584106,-3.238922 4,-6 5,-9c1,-3 3,-6 4,-9c1,-3 2,-5 3,-7c1,-2 2,-3 2,-4c0,-1 0,-2 -1,-2c-2,0 -3,-1 -6,-2c-3,-1 -6,-2 -9,-3c-3,-1 -5.963806,-2.115875 -9,-3c-3.958679,-1.152771 -9.063599,-1.732605 -18,-4c-7.056519,-1.790436 -15,-3 -22,-3c-7,0 -13,0 -17,0l-1,-2l-1,0l-1,0" id="svg_3" stroke-width="5" stroke="#000000" fill="none"/>
                    <text xml:space="preserve" text-anchor="middle" font-family="serif" font-size="24" id="svg_4" y="427" x="977" stroke-width="0" stroke="#000000" fill="#000000">4. Doble-click block to change settings</text>
                </g>

                <g class="step">
                    <rect stroke="#000000" id="svg_6" height="46.342594" width="154.000005" y="651" x="560" fill-opacity="0" stroke-width="3" fill="#000000"/>
                    <text style="cursor: move;" stroke="#000000" xml:space="preserve" text-anchor="middle" font-family="serif" font-size="24" id="svg_7" y="682" x="639" stroke-width="0" fill="#000000">Close help</text>
                </g>

            </svg>

        </div>
        <div id="help-cover"></div>
    </div>

    <div id="section-preset-switcher-tpl" class="hidden">
        <div class="section-preset-switcher">
            <div class="section-preset-switcher-wrap">
                <span class="glyphicon preset-unlock glyphicon-lock"></span><span class="preset-prev disabled glyphicon glyphicon-chevron-left"></span><span class="preset-next glyphicon glyphicon-chevron-right"></span>

                <div class="preset-name"><var>name</var> <b class="caret"></b></div>
            </div>
        </div>
    </div>

</body>
</html>
