<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9469758163081120"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" />

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://bootswatch.com/_vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.6/tinymce.min.js"></script>

    <title>Email Service</title>
    <style>
    .tox-tinymce {
        height: 30vw !important;
    }



    html {
        height: 100%;
    }

    body {
        background-image: radial-gradient(circle farthest-corner at center, #3C4B57 0%, #1C262B 100%);
    }

    .loader {
        position: fixed;
        top: calc(50% - 32px);
        left: calc(50% - 32px);
        width: 64px;
        height: 64px;
        border-radius: 50%;
        perspective: 800px;
    }

    .inner {
        position: absolute;
        box-sizing: border-box;
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }

    .inner.one {
        left: 0%;
        top: 0%;
        animation: rotate-one 1s linear infinite;
        border-bottom: 3px solid #EFEFFA;
    }

    .inner.two {
        right: 0%;
        top: 0%;
        animation: rotate-two 1s linear infinite;
        border-right: 3px solid #EFEFFA;
    }

    .inner.three {
        right: 0%;
        bottom: 0%;
        animation: rotate-three 1s linear infinite;
        border-top: 3px solid #EFEFFA;
    }

    @keyframes rotate-one {
        0% {
            transform: rotateX(35deg) rotateY(-45deg) rotateZ(0deg);
        }

        100% {
            transform: rotateX(35deg) rotateY(-45deg) rotateZ(360deg);
        }
    }

    @keyframes rotate-two {
        0% {
            transform: rotateX(50deg) rotateY(10deg) rotateZ(0deg);
        }

        100% {
            transform: rotateX(50deg) rotateY(10deg) rotateZ(360deg);
        }
    }

    @keyframes rotate-three {
        0% {
            transform: rotateX(35deg) rotateY(55deg) rotateZ(0deg);
        }

        100% {
            transform: rotateX(35deg) rotateY(55deg) rotateZ(360deg);
        }
    }

    #loading-status-text {
        margin-top: 100%;
        color: #C15FD0;
        width: 500px;
    }

    .itooltip {
        padding: 0px 5px 0px 5px;
        color: black;
        background-color: white;
        border-radius: 5px;
    }
    </style>
</head>

<body>

    <div class="loader d-none" style="z-index:999999;">
        <div class="inner one"></div>
        <div class="inner two"></div>
        <div class="inner three"></div>
        <h3 id="loading-status-text"></h3>
    </div>