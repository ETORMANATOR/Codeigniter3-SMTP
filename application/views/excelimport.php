<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <title>JE Services</title>
    <style>
    h1,
    h3,
    label,
    th,
    td,
    p {
        color: white;
    }

    html {
        height: 100%;
    }

    body {
        background-image: radial-gradient(circle farthest-corner at center, #3C4B57 0%, #1C262B 100%);
    }
    </style>
</head>

<body>
    <div class="container" style="margin-top:200px">
        <form action="<?=base_url('Emailsender/index')?>" enctype="multipart/form-data" method="post">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-right">Type Service</label>
                <div class="col-sm-4 text-right">
                    <select id="serviceselection" name="serviceselection" class="custom-select custom-select-lg"
                        style="border: 1px solid #ccc;font-size:1vw;color: #555;" required>
                        <option value="" selected>Select service</option>
                        <option value="sendemail">Send Bulk Emails</option>
                        <option value="checkemail">Check Bulk Emails</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 text-center" style="font-size:3vw;margin-bottom:10%;">
                    Import File(xlsl,xls and csv file only)</label>
                <div class="col-3 text-center">
                </div>
                <div class="col-6 text-center">
                    <input type="file" class="form-control-file" id="exampleFormControlFile1"
                        style="font-size: 2vw;color: white;" accept=".xlsx,.xls,.csv" name="upload_excel" required>
                </div>
                <div class="col-3 text-center">
                </div>
                <div class="col-12 text-center">

                    <input type="submit" class="btn btn-primary " name="submit" value="Import"
                        style="font-size: 2vw;margin-top:5%;">
                    <?php if ($this->session->flashdata('success')) { ?>
                    <br>
                    <br>
                    <p class="text-success"><?=$this->session->flashdata('success')?></p>
                    <?php  } ?>
                    <?php if ($this->session->flashdata('error')) { ?>
                    <br>
                    <br>
                    <p class="text-danger"><?=$this->session->flashdata('error')?></p>
                    <?php  } ?>
                </div>
            </div>
        </form>
    </div>
</body>

</html>