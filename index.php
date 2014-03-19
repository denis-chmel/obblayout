<?php


ini_set('display_errors', 1);
error_reporting(-1);

$layouts = array();
foreach (glob(__DIR__ . "/layouts/*.html") as $file) {

    $html = file_get_contents($file);

    $title = "";
    if (preg_match("~<title>(.*?)</title>~", $html, $matches)) {
        $title = $matches[1];
    }

    $layout = "";
    if (preg_match("~<body>(.*?)</body>~s", $html, $matches)) {
        $layout = $matches[1];
    }

    if ($layout) {
        $layouts[] = array(
            "title" => $title,
            "html"  => $layout,
        );
    }

}

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1">
    <title>OBB Layout Constructor</title>
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap-3.1.1-dist/css/bootstrap.css"/>
    <script src="js/jquery-2.1.0.js"></script>
    <script src="vendor/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

    <link rel="stylesheet" href="/js/jquery-ui/themes/smoothness/jquery-ui.css">
    <script src="/js/jquery-ui/ui/jquery-ui.js"></script>

    <link rel="stylesheet" type="text/css" href="css/extra-styles.css"/>
    <link rel="stylesheet" type="text/css" href="css/constructor.css"/>

    <script src="vendor/tinymce/js/tinymce/tinymce.min.js"></script>

</head>
<body class="layout-constructor">

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

    <div class="left">

        <div class="header">

            <span class="glyphicon glyphicon-th-large"></span>
            Openbizbox Layout Constructor

        </div>
        <div class="contents">

            <button type="button" class="btn btn-primary" id="btn-toggle-grid">Show grid</button>
            <button type="button" class="btn btn-primary disabled" id="btn-undo">Undo</button>
            <br>
            <br>

            Blue-line delay:
            <input type="text" class="input" value="600" style="color: #000" size="4" id="blue-line-delay">&nbsp;ms


            <hr>
            <span style="color: #888">Grids:</span>

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

            <br>
            <span style="color: #888">Blocks:</span>

            <div id="draggable-blocks">

                <div class="draggable-block">
                    <div class="block-label">
                        <span class="glyphicon glyphicon glyphicon-shopping-cart"></span> Checkout button
                    </div>
                    <div class="block-code">
                        <div class="btn btn-danger">Checkout</div>
                    </div>
                </div>

                <div class="draggable-block">
                    <div class="block-label">
                        <span class="glyphicon glyphicon glyphicon-shopping-cart"></span> Breadcrumb
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
                        <span class="glyphicon glyphicon glyphicon-align-justify"></span> Custom text / HTML
                    </div>
                    <div class="block-code">
                        <div class="custom-text-html">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt tellus eget tortor elementum pellentesque. Vivamus a ornare turpis. Curabitur quis urna in erat elementum dapibus et et sem. Morbi congue sem vitae urna adipiscing laoreet. Ut lectus elit, vulputate ac metus vitae, cursus malesuada purus. Pellentesque tristique egestas elit, vitae rhoncus est sagittis vitae. Morbi lobortis condimentum massa eget vehicula. Duis nec massa vel mauris dignissim fermentum eget vel nulla. Pellentesque quis nunc mattis, mollis nibh vel, ornare sapien.</div>
                    </div>
                </div>

                <div class="draggable-block">
                    <div class="block-label">
                        <span class="glyphicon glyphicon glyphicon-info-sign"></span> Info pages links
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
                        <span class="glyphicon glyphicon glyphicon-info-sign"></span> Account pages links
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
                        <span class="glyphicon glyphicon glyphicon-tags"></span> Supported credit cards
                    </div>
                    <div class="block-code">
                        <p>TODO</p>
                    </div>
                </div>

                <div class="draggable-block todo">
                    <div class="block-label">
                        <span class="glyphicon glyphicon glyphicon-tags"></span> Contact us form
                    </div>
                    <div class="block-code">
                        <p>TODO</p>
                    </div>
                </div>

                <div class="draggable-block todo">
                    <div class="block-label">
                        <span class="glyphicon glyphicon glyphicon-tags"></span> Subscribe form
                    </div>
                    <div class="block-code">
                        <p>TODO</p>
                    </div>
                </div>

                <div class="draggable-block">
                    <div class="block-label">
                        <span class="glyphicon glyphicon glyphicon-picture"></span> Shop logo
                    </div>
                    <div class="block-code">
                        <div class="shop-logo">
                            <div class="block-title">
                                <span class="glyphicon glyphicon glyphicon-picture"></span> Shop Logo
                            </div>
                        </div>
                    </div>
                </div>

                <div class="draggable-block">
                    <div class="block-label">
                        <span class="glyphicon glyphicon glyphicon-font"></span> Shop Title
                    </div>
                    <div class="block-code">
                        <h2 class="shop-title">Shop Title</h2>
                    </div>
                </div>

                <div class="draggable-block">
                    <div class="block-label">
                        <span class="glyphicon glyphicon glyphicon-picture"></span> Custom Banner / Image
                    </div>
                    <div class="block-code">
                        <div class="shop-logo">
                            <div class="block-title">
                                <span class="glyphicon glyphicon glyphicon-picture"></span> Banner
                            </div>
                        </div>
                    </div>
                </div>

                <div class="draggable-block">
                    <div class="block-label">

                        <span class="glyphicon glyphicon glyphicon-th-list"></span> Menu
                    </div>

                    <div class="block-code">
                        <ul class="nav nav-pills">
                            <li class="active"><a href="#">Home</a></li>
                            <li><a href="#">Profile</a></li>
                            <li><a href="#">Messages</a></li>
                            <li><a href="#">Friends</a></li>
                            <li><a href="#">Photos</a></li>
                            <li><a href="#">Calendar</a></li>
                        </ul>
                    </div>
                </div>

                <div class="draggable-block">
                    <div class="block-label">
                        <span class="glyphicon glyphicon glyphicon-search"></span> Search
                    </div>
                    <div class="block-code">
                        <div class="input-group" style="padding: 5px;">
                            <input type="text" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <span class="glyphicon glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>


                <div class="draggable-block">
                    <div class="block-label">
                        <span class="glyphicon glyphicon glyphicon-film"></span> Slider
                    </div>
                    <div class="block-code">
                        <div class="slider jumbotron">
                            <h1>Slider</h1>

                            <p>This is your home page, you are able to remove and add any block. Each block has such hint</p>

                            <p><a class="btn btn-primary btn-lg" role="button">Learn more</a></p>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>
    <div class="right">

        <div class="header">

            <div class="btn-group btn-group-device">
                <button type="button" class="btn btn-sm btn-info" data-device="phone">Phone</button>
                <button type="button" class="btn btn-sm btn-info" data-device="tablet">Tablet</button>
                <button type="button" class="btn btn-sm btn-info active" data-device="desktop">Desktop</button>
            </div>

            <button class="btn btn-info btn-saved-layouts-prev"><span class="glyphicon glyphicon-chevron-left"></span></button>
            <select class="saved-layouts">
                <? foreach ($layouts as $i => $layout): ?>
                <option value="<?= $i ?>"><?= $layout["title"] ?></option>
                <? endforeach ?>
                <option value="new">New layout...</option>
            </select>
            <button class="btn btn-info btn-saved-layouts-next"><span class="glyphicon glyphicon-chevron-right"></span></button>

            <!--
            <div class="btn-group">
                <button type="button" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Save as..</a></li>
                    <li><a href="#">Preview</a></li>
                    <li><a href="#">Save and publish</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Delete</a></li>
                </ul>
            </div>
            -->


        </div>

        <div id="sandbox">

            <? foreach ($layouts as $i => $layout): ?>

                <div id="layout-<?= $i ?>" style="margin-left: <?= $i * 150?>%; margin-right: <?= ($i - 3) * 150?>%"  class="layout">
                    <?= $layout["html"] ?>
                </div>

            <? endforeach ?>

            <div id="layout-new" style="margin-left: 300%; margin-right: -150%;" class="layout">
                <div class="container">

                    <header class="sortable"></header>
                    <main class="sortable"></main>
                    <footer class="sortable"></footer>

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
                    <textarea id="content-en" name="content[en]" style1="width:100%"></textarea>
                </div>
                <div class="lang-dependent lang-da hidden">
                    <textarea id="content-da" name="content[da]" style1="width:100%"></textarea>
                </div>
                <div class="lang-dependent lang-de hidden">
                    <textarea id="content-de" name="content[de]" style1="width:100%"></textarea>
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


</body>
</html>
