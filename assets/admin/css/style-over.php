<?php
/*** set the content type header ***/
header("Content-type: text/css");

$site_color = $_GET['color'];
$rgb = $_GET['rgb'];
?>

.btn-info {
    background-color: #<?= $_GET['color'] ?> !important;
    border-color: #<?= $_GET['color'] ?> !important;
}

.btn-info:hover {
    background: rgba(<?= $_GET['rgb'] ?>,0.8) !important;
    border-color: rgba(<?= $_GET['rgb'] ?>,0.8) !important;
    color: #fff !important;
}

.cancel-inv:hover {
    color: #fff !important;
    background: #fc4b6c;
}

a:active, a:focus, a:hover {
    outline: 0;
    text-decoration: none;
    color: #<?= $_GET['color'] ?> !important;
}

[type=radio].with-gap:checked+label:after, [type=radio]:checked+label:after {
    background-color: #<?= $_GET['color'] ?> !important;
    z-index: 0;
}

.dropdown-item:hover {
    text-decoration: none;
    background-color: rgba(<?= $_GET['rgb'] ?>,1) !important;
    color: #fff !important;
}

.delete:hover {
    color: #fc4b6c !important;
}

a.new_business_link:hover {
    color: #<?= $_GET['color'] ?> !important;
    transition: none;
}


.rounded{
    border-radius: 4px !important;
}

.btn-rounded{
    border-radius: 4px !important;
}

#floating-container .fab-button {
    background-color: #<?= $_GET['color'] ?> !important;
}

.btn-default.hover, .btn-default:active, .btn-default:hover {
    border: 2px solid #<?= $_GET['color'] ?> !important;
}

.paging_simple_numbers .pagination .paginate_button:hover a {
    background: #<?= $_GET['color'] ?> !important;
}

.btn {
    border-radius: 4px !important;
}
a.new_business_link:hover {
    color: #<?= $_GET['color'] ?> !important;
    transition: none;
}

a.btn.btn-default.add_item_btn {
    color: #<?= $_GET['color'] ?> !important;
}

.btn-outline-info:hover {
    border: 2px solid #<?= $_GET['color'] ?> !important;
    color: #<?= $_GET['color'] ?> !important;
}

.label-primary{
    background-color: #<?= $_GET['color'] ?> !important;
}

.text-primary {
    color: #<?= $_GET['color'] ?> !important;
}

.badge-info {
    background-color: #<?= $_GET['color'] ?> !important;
}

.nav-tabs-custom>.nav-tabs.admin>li a.active {
    background:  #<?= $_GET['color'] ?> !important;
}

.nav.nav-tabs.custab>li>a:hover {
    color: #<?= $_GET['color'] ?> !important;
}

.nav-tabs .nav-link.active, .nav-tabs .nav-link.active:hover {
    border-bottom-color: #<?= $_GET['color'] ?> !important;
}

a.on-default:hover {
    color: #<?= $_GET['color'] ?> !important;
    border: 2px solid #<?= $_GET['color'] ?> !important;
}

.form-control:focus {
    border-color: #<?= $_GET['color'] ?> !important;
}

[type=checkbox].filled-in:checked.chk-col-blue+label:after {
    border: 2px solid #<?= $_GET['color'] ?> !important;
    background-color: #<?= $_GET['color'] ?> !important;
}

#floating-container .floating-menus > div {
    background-color: #<?= $_GET['color'] ?> !important;
}

