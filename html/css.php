<?php defined('ABSPATH') || exit; ?>
<link rel="stylesheet" href="https://static.justauth.me/medias/jam-button.css" />
<style type="text/css">
    .jam-notice {
        margin: 1rem;
        border-width: 1px;
        border-style: solid;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 15px;
    }

    .jam-notice.jam-notice-error {
        background-color: rgba(231, 76, 60, .2);
        border-color: #c0392b;
        color:#c0392b;
    }

    .jam-notice.jam-notice-success {
        background-color: rgba(46, 204, 113, .2);
        border-color: #27ae60;
        color: #27ae60;
    }

    .jam-notice.jam-notice-info {
        background-color: rgba(52, 152, 219, .2);
        border-color: #2980b9;
        color: #2980b9;
    }

    .jam-notice .jam-notice-dismiss {
        float: right;
        color: white;
        background-color: #bdc3c7;
        height: 20px;
        width: 20px;
        text-align: center;
        line-height: 18px;
        border-radius: 10px;
        cursor: pointer;
        transition-duration: .3s;
    }

    .jam-notice .jam-notice-dismiss:hover {
        background-color: #7f8c8d;
    }
</style>
