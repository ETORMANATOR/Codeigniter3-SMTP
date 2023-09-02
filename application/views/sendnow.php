<?php $increNum = 1; ?>
<div class="container" style="margin-top:2%;width: 90% !important;
         max-width: 100% !important;">
    <center>
        <h2><b>Email Bulk Sender</b></h2>
    </center>
    <div class="col-md-8 mx-auto" style="border: 1px solid #3479B7;margin-bottom:50px;width:50vw !important"
        id="smtp-Setup">
        <div style="padding:10px;">
            <div class="form-group">
                <h3 class="text-center">SMTP SETUP</h3>
                <label class="form-label">SMTP Server <span class="text-info form-label" data-bs-container="body"
                        data-bs-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-bs-content="<p><b>G-suite</b> Turn on Less secure app access to used your G-suite account.
                                    </p>" data-bs-original-title="SMTP Server Note"
                        aria-describedby="smtp">&#63;</span></label>
                <select id="stmpserverselection" class="form-select"
                    style="border: 1px solid #ccc;font-size:1vw;color: #555;">
                    <option value="Emptyvalue" selected>Select smtp server</option>
                    <option value="Gmail">Gmail (G-suite only)</option>
                    <option value="Ops">Other</option>
                </select>


            </div>
            <div class="d-none adnvaceSmtpServer">
                <div class="form-group">
                    <label for="IMAPServer" class="form-label mb-3">IMAP Server <span class="text-info form-label"
                            data-bs-trigger="hover" data-bs-container="body" data-bs-toggle="popover"
                            data-bs-placement="right" data-bs-content="<p>
                                        To connect to a remote server replace 'localhost' with the name or the</br>
                                        IP address of the server you want to connect to.</b><br>

                                        // To connect to an IMAP server running on port 143 on the local machine,</br>
                                        // do the following:</br>
                                        <b>{localhost:143}</b></br>
                                        // To connect to a POP3 server on port 110 on the local server, use:</br>
                                        <b>{localhost:110/pop3}</b></br>
                                        // To connect to an SSL IMAP or POP3 server, add /ssl after the protocol</br>
                                        // specification:</br>
                                        <b>{localhost:993/imap/ssl}</b></br>
                                        // To connect to an SSL IMAP or POP3 server with a self-signed certificate,</br>
                                        // add /ssl/novalidate-cert after the protocol specification:</br>
                                        <b>{localhost:995/pop3/ssl/novalidate-cert}</b></br>
                                        // To connect to an NNTP server on port 119 on the local server, use:
                                    </p>" data-bs-original-title="Note:" aria-describedby="smtp">&#63;</span></label>

                    <input type="text" class="form-control" id="IMAPServer" placeholder="Imap server" value="empty"
                        required>
                </div>
                <div class="form-group">
                    <label for="SMTPHostname" class="form-label">SMTP Hostname <span class="text-info form-label"
                            data-bs-container="body" data-bs-trigger="hover" data-bs-toggle="popover"
                            data-bs-placement="right" data-bs-content="<p><b>Example:</b> mail.domain.com
                                    </p>" data-bs-original-title="SMTP Hostname"
                            aria-describedby="smtp">&#63;</span></label>
                    <input type="text" class="form-control" id="SMTPHostname" value="empty" placeholder="SMTP Hostname"
                        required>
                </div>
                <div class="form-group">
                    <label for="SMTPPort" class="form-label">Port <span class="text-info form-label"
                            data-bs-container="body" data-bs-trigger="hover" data-bs-toggle="popover"
                            data-bs-placement="right" data-bs-content="<p><b>Example:</b> 25, 2525, 465, 587
                                    </p>" data-bs-original-title="SMTP Port"
                            aria-describedby="smtp">&#63;</span></label>
                    <input type="text" class="form-control" id="SMTPPort" value="empty" placeholder="Port" required>
                </div>



            </div>
            <div class="form-group">
                <label for="SenderE" class="col-form-label">Email Sender</label>
                <input type="email" class="form-control" id="SenderE" placeholder="Email Sender" required>

            </div>
            <div class="form-group ">
                <label for="SenderP" class="col-form-label">Email Password
                </label>
                <input type="password" class="form-control" id="SenderP" placeholder="Email Password" required>
            </div>
            <div class="form-group">
                <div class="text-center">
                    <button type="button" class="btn btn-success btn-lg" id="Credential-Checker">Check Email
                        Credential</button>
                </div>
            </div>
        </div>
    </div>



    <div style="border: 1px solid #6EAC47;margin-bottom:50px;" class="d-none" id="email-Setup">
        <div style="padding:10px;">
            <div class="form-group">
                <h3 class="col-12 text-center">EMAIL SETUP</h3>
                <div class="col-12">
                    <input type="text" class="form-control" id="SubjectT" placeholder="Subject Title" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-12">
                    <textarea style="color:black; height: 40vw;" class="form-control" name="emailmessage"
                        id="emailmessage" rows="12"></textarea>

                    <p style='background-color:white;color:black;'> Note: $DataFname = First Name | $DataLname =
                        Last Name | $DataEmail = Email Address | $DataCode = Draw Code"</p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 text-center">
                    <?php $increTol = 0;
foreach ($allinfo as $eachdata) {?>
                    <?php $increTol ++;
} ?>
                    <h1>Total Receiver Email: <b id="totalEmail"><?=$increTol ?></b></h1>

                    <input type="submit" class="btn btn-primary " style="font-size:1vw" name="submit" value="SEND NOW"
                        id="sendNowbtn">
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
            <h3 class="col-12 text-center">SENDING STATUS</h3>
            <table class="table table-hover">
                <thead>
                    <tr class="table table-hover">
                        <th scope="col">#</th>
                        <th scope="col">First</th>
                        <th scope="col">Last</th>
                        <th scope="col">Email</th>
                        <th scope="col">Code</th>
                        <th scope="col">Sending Status</th>
                        <th scope="col">Bounce/Recived</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $increNumClass = 1;
foreach ($allinfo as $eachdata) {
    ?>
                    <tr class="table-light eachTR <?= "checherEM".$increNumClass; ?>">
                        <th scope="row"><?= $increNumClass; ?></th>
                        <td class="sendingFname<?= $increNumClass; ?>"><?=  $eachdata[0]; ?></td>
                        <td class="sendingLname<?= $increNumClass; ?>"><?=  $eachdata[1]; ?></td>
                        <td class="sendingEmail<?= $increNumClass; ?>"><?=  $eachdata[2]; ?></td>
                        <td class="sendingCode<?= $increNumClass; ?>"><?=  $eachdata[3]; ?></td>
                        <td class="StatusClass sendingstatus<?= $increNumClass; ?>"><b>Stanby ...</b> </td>
                        <td class="BounceClass bouncestatus<?= $increNumClass; ?>"><b>Stanby ...</b> </td>

                    </tr>
                    <?php $increNumClass ++;
} ?>
                </tbody>
            </table>
        </div>
    </div>
</div>