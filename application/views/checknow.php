<?php $increNum = 1; ?>
<div class="container" style="margin-top:2%;width: 90% !important;
    max-width: 100% !important;">
    <center>
        <h2><b>Email Bulk Checker</b></h2>
    </center>

    <div style="border: 1px solid #3479B7;margin-bottom:50px;" id="smtp-Setup">
        <div style="padding:10px;">
            <div class="form-group row">
                <h3 class="col-12 text-center">SMTP SETUP</h3>
                <label class="col-sm-2 col-form-label">SMTP Server</label>
                <div class="col-sm-10">
                    <select id="stmpserverselection" class="custom-select custom-select-lg"
                        style="border: 1px solid #ccc;font-size:1vw;color: #555;">
                        <option value="Emptyvalue" selected>Select smtp server</option>
                        <option value="Gmail">Gmail (G-suite)</option>
                        <option value="Ops">Internal Email (SMTP)</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="SenderE" class="col-sm-2 col-form-label">Email Sender</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="SenderE" placeholder="Email Sender" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="SenderP" class="col-sm-2 col-form-label">Email Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="SenderP" placeholder="Email Password" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-primary" id="Credential-Checker">Check Email
                        Credential</button>
                </div>
            </div>
        </div>
    </div>

    <div style="border: 1px solid #6EAC47;margin-bottom:50px;" class="d-none" id="email-Setup">
        <div style="padding:10px;">
            <div class="form-group row">
                <div class="col-12 text-center">
                    <?php $increTol = 0;
foreach ($allinfo as $eachdata) {?>
                    <?php $increTol ++;
} ?>
                    <h1>Total Email: <b id="totalEmail"><?=$increTol ?></b></h1>

                    <input type="submit" class="btn btn-primary " style="font-size:1vw" name="submit" value="Check NOW"
                        id="checkNowbtn">
                    <?php if ($this->session->flashdata('stmpSetup')) { ?>
                    <br>
                    <br>
                    <p class="text-danger"><?=$this->session->flashdata('stmpSetup')?></p>
                    <?php  } ?>
                </div>
            </div>
        </div>
    </div>
    <div style="border: 1px solid #ED7C31;margin-bottom:50px;" class="" id="email-Status">
        <div style="padding:10px;">
            <h3 class="col-12 text-center">Email STATUS</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Full name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $increNumClass = 1;
foreach ($allinfo as $eachdata) {
    ?>
                    <tr class="eachTR">
                        <th scope="row"><?= $increNumClass; ?></th>
                        <td class="sendingFname<?= $increNumClass; ?>"><?=  $eachdata[0]." ".$eachdata[1]; ?></td>
                        <td class="sendingEmail<?= $increNumClass; ?>"><?=  $eachdata[2]; ?></td>
                        <td class="StatusClass sendingstatus<?= $increNumClass; ?>"><b>Stanby ...</b> </td>

                    </tr>
                    <?php $increNumClass ++;
} ?>
                </tbody>
            </table>
        </div>
    </div>
</div>