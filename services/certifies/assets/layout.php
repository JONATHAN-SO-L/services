<?php
function section() {
    header('Content-Type: text/html; charset=UTF-8');
    echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../../../assets/css/main.css">';
    include '../../assets/links2.php';
    include '../../assets/navbar.php';
    echo '</head>

    <body>

    <div id="menu-overlay"></div>
    <div id="menu-toggle" class="closed" data-title="Menu">
        <i class="fa fa-bars"></i>
        <i class="fa fa-times"></i>
    </div>

    <section id="content">
    <header id="content-header">
    ';
}

function end_section() {
    echo '
    </header>
    </section>
    </body>
    </html>
    ';
}
?>