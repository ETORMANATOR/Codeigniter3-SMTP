</body>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- <script src="<?= base_url() ?>plugins/tinymce/tinymce.min.js"></script>
<script src="<?= base_url() ?>plugins/js/tinymce_editor.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>

<script>
tinymce.init({
    selector: 'textarea',
    height: 800,
    plugins: "code preview textcolor colorpicker link image imagetools autolink autoresize codesample fullscreen charmap searchreplace wordcount visualchars table emoticons lists advlist media paste ",
    toolbar1: "| fontselect | styleselect | fontsizeselect| forecolor backcolor ",
    toolbar2: "| undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table charmap searchreplace wordcount ",
    toolbar3: "| emoticons link image code codesample preview fullscreen| paste pastetext ",
    //toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link code image_upload",
    menubar: 'file edit insert view format table tools help',
    statusbar: false,
    fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
    contextmenu_avoid_overlap: '.mce-spelling-word',
    contextmenu: 'link image table',
    browser_spellcheck: true,
    gecko_spellcheck: false,
    file_picker_types: 'file image media',
    spellchecker_language: 'en'

});


var SMTPServer, EmialSender, EmailPassword;
var SubjectTitle, EmailMessage;
var finalMessage, finalSubjectTitle;
$(function() {
    $('[data-bs-toggle="popover"]').popover({
        html: true,
        sanitize: false,
    })
})
$(document).ready(function() {





    $('#stmpserverselection').on('change', function() {
        if ($('#stmpserverselection').val() == "Ops") {
            $(".adnvaceSmtpServer").removeClass("d-none");
            $("#SMTPHostname").val("");
            $("#SMTPPort").val("");
            $("#IMAPServer").val("");
        } else {
            $(".adnvaceSmtpServer").addClass("d-none");
            $("#SMTPHostname").val("empty");
            $("#SMTPPort").val("empty");
            $("#IMAPServer").val("empty");
        }
    });

    $("#sendNowbtn").on("click", function() {

        var select_imap_server = $("#stmpserverselection").val();
        alertify.dismissAll();
        var completeinput = true;
        SubjectTitle = $("#SubjectT").val();
        EmailMessage = tinymce.get('emailmessage').getContent();
        if (SubjectTitle.trim() === "" || SubjectTitle.length < 1) {
            alertify.error('Please check your subject.');
            completeinput = false;
        }
        if (EmailMessage.trim() === "" || EmailMessage.length < 2) {
            alertify.error('Please check your message.');
            completeinput = false;
        }
        if (completeinput) {
            $("#email-Setup").addClass("d-none");
            $(".loader").removeClass("d-none");
            if (select_imap_server == "Ops" || select_imap_server == "Gmail") {
                $("#loading-status-text").removeAttr('style');
                $("#loading-status-text").attr("style", "margin-left:-50%;");
                $("#loading-status-text").text("Sending...");

                var sendCount = 1;
                $(".eachTR").each(function(index) {
                    var postionIng = index + 1;
                    finalMessage = EmailMessage.replaceAll("$DataFname", $(".sendingFname" +
                        postionIng + "").text()).replaceAll("$DataLname", $(
                        ".sendingLname" + postionIng + "").text()).replaceAll("$DataEmail",
                        $(".sendingEmail" + postionIng + "").text()).replaceAll("$DataCode",
                        $(".sendingCode" + postionIng + "").text()).replaceAll(
                        "<p>&nbsp;</p>",
                        "<p style='padding-top:4px;padding-bottom:4px;'></p>");
                    finalSubjectTitle = SubjectTitle.replaceAll("$DataFname", $(
                            ".sendingFname" + postionIng + "").text()).replaceAll("$DataLname",
                            $(".sendingLname" + postionIng + "").text()).replaceAll(
                            "$DataEmail", $(".sendingEmail" + postionIng + "").text())
                        .replaceAll("$DataCode", $(".sendingCode" + postionIng + "").text());
                    $(".sendingstatus" + postionIng + "").attr("style",
                        "color:#FE981F !important");
                    $(".sendingstatus" + postionIng + "").text("Sending ...");
                    $(".bouncestatus" + postionIng + "").text("Waitng ...");

                    $.ajax({
                        url: select_imap_server,
                        type: "POST",
                        data: {
                            emailReceiver: $(".sendingEmail" + postionIng + "").text(),
                            emailSubject: finalSubjectTitle,
                            emailHtmlMessage: finalMessage,
                        },
                        cache: false,
                        success: function(Result, statusText, error) {
                            let responseData = JSON.parse(Result);
                            sendCount++;
                            console.log(responseData.responseMessage);
                            if (responseData.responseStatus == "Send") {

                                processdd = true;
                                $(".sendingstatus" + postionIng + "").removeAttr(
                                    "style");
                                $(".sendingstatus" + postionIng + "").attr("style",
                                    "color:green !important");
                                $(".sendingstatus" + postionIng + "").text("SEND");
                                if (sendCount == $('.eachTR').length || $('.eachTR')
                                    .length == 1) {
                                    $(".loader").addClass("d-none");
                                    swal({
                                        title: "Complete!",
                                        text: "Sending " + $("#totalEmail")
                                            .text() + " Emails Complete!",
                                        icon: "success",
                                        button: "Ok!",
                                        closeOnEsc: false,
                                        closeOnClickOutside: false
                                    }).then((willOk) => {
                                        if (willOk) {

                                            $("#loading-status-text").text(
                                                "Analyzing the bounce email..."
                                            );
                                            $("#loading-status-text")
                                                .removeAttr('style');
                                            $("#loading-status-text").attr(
                                                "style",
                                                "margin-left:-250%;");
                                            $(".loader").removeClass(
                                                "d-none");
                                            setTimeout(checkbounce(
                                                    select_imap_server),
                                                20000);

                                        }
                                    });
                                }

                            } else if (responseData.responseMessage.indexOf(
                                    "is not a valid") >= 0) {
                                $(".sendingstatus" + postionIng + "").removeAttr(
                                    "style");
                                $(".sendingstatus" + postionIng + "").attr("style",
                                    "color:red !important");
                                $(".sendingstatus" + postionIng + "").text(
                                    "Invalid email");
                                if (sendCount == $('.eachTR').length || $('.eachTR')
                                    .length == 1) {
                                    $(".loader").addClass("d-none");
                                    swal({
                                        title: "Complete!",
                                        text: "Sending " + $("#totalEmail")
                                            .text() + " Emails Complete!",
                                        icon: "success",
                                        button: "Ok!",
                                        closeOnEsc: false,
                                        closeOnClickOutside: false
                                    }).then((willOk) => {
                                        if (willOk) {

                                            $("#loading-status-text").text(
                                                "Analyzing the bounce email..."
                                            );
                                            $("#loading-status-text")
                                                .removeAttr('style');
                                            $("#loading-status-text").attr(
                                                "style",
                                                "margin-left:-250%;");
                                            $(".loader").removeClass(
                                                "d-none");
                                            setTimeout(checkbounce(
                                                    select_imap_server),
                                                20000);
                                        }

                                    });


                                }
                            } else if (responseData.responseStatus == "Error") {
                                $(".sendingstatus" + postionIng + "").removeAttr(
                                    "style");
                                $(".sendingstatus" + postionIng + "").attr("style",
                                    "color:red !important");
                                $(".sendingstatus" + postionIng + "").text("ERROR");
                                // $("checherEM" + postionIng + "").removeClass(
                                //     "eachTR");
                                if (sendCount == $('.eachTR').length || $('.eachTR')
                                    .length == 1) {
                                    $(".loader").addClass("d-none");
                                    swal({
                                        title: "Complete!",
                                        text: "Sending " + $("#totalEmail")
                                            .text() + " Emails Complete!",
                                        icon: "success",
                                        button: "Ok!",
                                        closeOnEsc: false,
                                        closeOnClickOutside: false
                                    }).then((willOk) => {
                                        if (willOk) {

                                            $("#loading-status-text").text(
                                                "Analyzing the bounce email..."
                                            );
                                            $("#loading-status-text")
                                                .removeAttr('style');
                                            $("#loading-status-text").attr(
                                                "style",
                                                "margin-left:-250%;");
                                            $(".loader").removeClass(
                                                "d-none");
                                            setTimeout(checkbounce(
                                                    select_imap_server),
                                                20000);
                                        }

                                    });


                                }
                            } else {
                                $(".loader").addClass("d-none");
                                swal({
                                    title: "Server Error!",
                                    text: "Contact webmaster @thinklogicmediagroup.com!",
                                    icon: "error",
                                    button: "Ok!",
                                    closeOnEsc: false,
                                    closeOnClickOutside: false
                                });
                            }
                        },
                        error: function(request, status, error) {

                            $(".loader").addClass("d-none");
                            swal({
                                title: "Server Error!",
                                text: "Contact webmaster @thinklogicmediagroup.com!",
                                icon: "error",
                                button: "Ok!",
                                closeOnEsc: false,
                                closeOnClickOutside: false
                            });
                        },

                    });


                });
            }



        }
    });
    $("#Credential-Checker").on("click", function() {
        alertify.dismissAll();
        SMTPServer = $("#stmpserverselection").val();
        EmialSender = $('#SenderE').val();
        EmailPassword = $('#SenderP').val();
        SMTPHostName = $("#SMTPHostname").val();
        SMTPPort = $("#SMTPPort").val();
        IMAPServer = $("#IMAPServer").val();
        var validatesmtp = true;
        if (SMTPServer == "Emptyvalue") {
            alertify.error('Please select an smtp server.');
            validatesmtp = false;
        }
        const re =
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var emailValidSender = re.test(String(EmialSender).toLowerCase());

        if (EmialSender.trim() === "" || EmialSender.length < 3 || !emailValidSender) {
            alertify.error('Please check your email.');
            validatesmtp = false;
        }
        if (EmailPassword.trim() === "" || EmailPassword.length < 3) {
            alertify.error('Please check your password.');
            validatesmtp = false;
        }
        if (SMTPHostName.trim() === "") {
            alertify.error('Please check your Hostname.');
            validatesmtp = false;
        }
        if (SMTPPort.trim() === "") {
            alertify.error('Please check your SMTP port.');
            validatesmtp = false;
        }
        if (IMAPServer.trim() === "") {
            alertify.error('Please check your Imap server.');
            validatesmtp = false;
        }

        if (validatesmtp) {
            $("#loading-status-text").removeAttr('style');
            $("#loading-status-text").attr("style", "margin-left:-150%;");
            $("#loading-status-text").text("Connecting to Server...");
            $("#stmpserverselection").attr("disabled", "disabled");
            $("#SenderE").attr("disabled", "disabled");
            $("#SenderP").attr("disabled", "disabled");
            $("#Credential-Checker").attr("disabled", "disabled");
            $(".loader").removeClass("d-none");
            $.ajax({
                url: "emailCreditialsCheck",
                type: "POST",
                data: {
                    smtpserver: SMTPServer,
                    smatpemail: EmialSender,
                    smatppassword: EmailPassword,
                    smtphostname: SMTPHostName,
                    smtpport: SMTPPort,
                    imapserver: IMAPServer
                },
                cache: false,
                success: function(Result, status, jqXH) {
                    let responseData = JSON.parse(Result);
                    if (responseData.responseStatus == 'Valid') {
                        $(".loader").addClass("d-none");
                        swal({
                                title: "Valid account!",
                                icon: "success",
                                closeOnEsc: false,
                                closeOnClickOutside: false
                            })
                            .then((willOk) => {
                                if (willOk) {

                                    $("#smtp-Setup").addClass("d-none");
                                    $("#email-Setup").removeClass("d-none");
                                }
                            });
                    } else if (responseData.responseStatus == "Invalid account") {
                        $(".loader").addClass("d-none");
                        $("#Credential-Checker").removeAttr("disabled");
                        $("#stmpserverselection").removeAttr("disabled");
                        $("#SenderE").removeAttr("disabled");
                        $("#SenderP").removeAttr("disabled");
                        swal({
                            title: "Invalid account!",
                            text: "Check email and password",
                            icon: "error",
                            button: "Ok!",
                            closeOnEsc: false,
                            closeOnClickOutside: false
                        });
                    } else if (responseData.responseStatus == "Invalid SMTP Server") {
                        $("#Credential-Checker").removeAttr("disabled");
                        $("#stmpserverselection").removeAttr("disabled");
                        $("#SenderE").removeAttr("disabled");
                        $("#SenderP").removeAttr("disabled");
                        $(".loader").addClass("d-none");
                        swal({
                            title: "Error!",
                            text: "Invalid your SMTP Server!",
                            icon: "error",
                            button: "Ok!",
                            closeOnEsc: false,
                            closeOnClickOutside: false
                        });
                    } else if (responseData.responseMessage.indexOf(
                            "fsockopen(): unable to connect") >= 0) {
                        $("#Credential-Checker").removeAttr("disabled");
                        $("#stmpserverselection").removeAttr("disabled");
                        $("#SenderE").removeAttr("disabled");
                        $("#SenderP").removeAttr("disabled");
                        $(".loader").addClass("d-none");
                        swal({
                            title: "Error!",
                            text: "Invalid SMTP port!",
                            icon: "error",
                            button: "Ok!",
                            closeOnEsc: false,
                            closeOnClickOutside: false
                        });
                    } else if (responseData.responseMessage.indexOf(
                            "fsockopen(): php_network_getaddresses: getaddrinfo failed: Name or service not known"
                        ) >= 0) {
                        $("#Credential-Checker").removeAttr("disabled");
                        $("#stmpserverselection").removeAttr("disabled");
                        $("#SenderE").removeAttr("disabled");
                        $("#SenderP").removeAttr("disabled");
                        $(".loader").addClass("d-none");
                        swal({
                            title: "Error!",
                            text: "Invalid SMTP Hostname not exist!",
                            icon: "error",
                            button: "Ok!",
                            closeOnEsc: false,
                            closeOnClickOutside: false
                        });
                    } else {
                        $("#Credential-Checker").removeAttr("disabled");
                        $("#stmpserverselection").removeAttr("disabled");
                        $("#SenderE").removeAttr("disabled");
                        $("#SenderP").removeAttr("disabled");
                        $(".loader").addClass("d-none");
                        swal({
                            title: "Invalid Imap Server!",
                            text: "Check your imap server",
                            icon: "error",
                            button: "Ok!",
                            closeOnEsc: false,
                            closeOnClickOutside: false
                        });
                    }

                },
                error: function(request, statusText, err) {
                    $("#Credential-Checker").removeAttr("disabled");
                    $("#stmpserverselection").removeAttr("disabled");
                    $("#SenderE").removeAttr("disabled");
                    $("#SenderP").removeAttr("disabled");
                    $(".loader").addClass("d-none");
                    swal({
                        title: "Invalid Imap Server!",
                        text: "Check your imap server1",
                        icon: "error",
                        button: "Ok!",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                    });
                },
            })
        }

    });

    function checkbounce(imap_server) {

        var analyzeCount = 1;
        $(".eachTR").each(function(index) {
            var scanbounceindex = index + 1;
            $(".bouncestatus" + scanbounceindex + "").text("Analyzing ...");
            $.ajax({
                url: 'scanbounce',
                type: "POST",
                data: {
                    gmail_or_ops: imap_server,
                    email_scan: $(".sendingEmail" + scanbounceindex + "").text(),
                },
                cache: false,
                success: function(Result) {
                    let responseData = JSON.parse(Result);
                    analyzeCount++;

                    if (responseData.responseStatus == '200') {
                        if ($(".sendingstatus" + scanbounceindex + "").text() != "ERROR" &&
                            $(".sendingstatus" + scanbounceindex + "").text() !=
                            "Invalid email") {
                            $(".bouncestatus" + scanbounceindex + "").attr("style",
                                "color:green !important");
                            $(".bouncestatus" + scanbounceindex + "").text("Received");
                        } else {
                            $(".bouncestatus" + scanbounceindex + "").attr("style",
                                "color:#93312F !important");
                            $(".bouncestatus" + scanbounceindex + "").text("ERROR");
                        }

                        if (analyzeCount == $('.eachTR').length || $('.eachTR').length ==
                            1) {
                            $(".loader").addClass("d-none");
                            swal({
                                title: "Complete!",
                                text: "Analyzing " + $("#totalEmail").text() +
                                    " Emails Complete!",
                                icon: "success",
                                button: "Ok!",
                                closeOnEsc: false,
                                closeOnClickOutside: false
                            });

                        }

                    } else if (responseData.responseStatus == '550') {
                        $(".bouncestatus" + scanbounceindex + "").attr("style",
                            "color:#93312F !important");
                        $(".bouncestatus" + scanbounceindex + "").text("Bounce");
                        if (analyzeCount == $('.eachTR').length || $('.eachTR').length ==
                            1) {
                            $(".loader").addClass("d-none");
                            swal({
                                title: "Complete!",
                                text: "Analyzing " + $("#totalEmail").text() +
                                    " Emails Complete!",
                                icon: "success",
                                button: "Ok!",
                                closeOnEsc: false,
                                closeOnClickOutside: false
                            });

                        }
                    } else {
                        $(".bouncestatus" + scanbounceindex + "").attr("style",
                            "color:#93312F !important");
                        $(".bouncestatus" + scanbounceindex + "").text("Unable to connect");
                        if (analyzeCount == $('.eachTR').length || $('.eachTR').length ==
                            1) {
                            $(".loader").addClass("d-none");
                            swal({
                                title: "Complete!",
                                text: "Analyzing " + $("#totalEmail").text() +
                                    " Emails Complete!",
                                icon: "success",
                                button: "Ok!",
                                closeOnEsc: false,
                                closeOnClickOutside: false
                            });

                        }
                    }
                }
            });

        });
    }
});

///Checker

$(document).ready(function() {
    $("#checkNowbtn").on("click", function() {
        $("#email-Setup").addClass("d-none");
        $(".loader").removeClass("d-none");
        var select_imap_server = $("#stmpserverselection").val();
        alertify.dismissAll();
        var completeinput = true;
        SubjectTitle = "_";
        EmailMessage = " _";

        $("#loading-status-text").removeAttr('style');
        $("#loading-status-text").attr("style", "margin-left:-50%;");
        $("#loading-status-text").text("Analyzing...");

        var sendCount = 1;
        $(".eachTR").each(function(index) {
            var postionIng = index + 1;
            finalMessage = EmailMessage
            finalSubjectTitle = SubjectTitle;
            $(".sendingstatus" + postionIng + "").attr("style", "color:#FE981F !important");
            $(".sendingstatus" + postionIng + "").text("Analyzing ...");

            $.ajax({
                url: select_imap_server,
                type: "POST",
                data: {
                    emailReceiver: $(".sendingEmail" + postionIng + "").text(),
                    emailSubject: finalSubjectTitle,
                    emailHtmlMessage: finalMessage,
                    senderEmail: EmialSender,
                    senderPassword: EmailPassword,
                },
                cache: false,
                success: function(Result) {
                    sendCount++;
                    console.log("Rturn Val: " + Result + " " + $(".sendingFname" +
                        postionIng + "").text());
                    if (Result == "Send") {

                        if (sendCount == $('.eachTR').length || $('.eachTR')
                            .length == 1) {

                            setTimeout(checkbounce(select_imap_server, EmialSender,
                                EmailPassword), 20000);

                        }

                    } else if (Result == "Error") {
                        if (sendCount == $('.eachTR').length || $('.eachTR')
                            .length == 1) {
                            setTimeout(checkbounce(select_imap_server, EmialSender,
                                EmailPassword), 20000);
                        }

                    } else {
                        swal({
                            title: "Server Error!",
                            text: "Contact webmaster @thinklogicmediagroup.com!",
                            icon: "error",
                            button: "Ok!",
                            closeOnEsc: false,
                            closeOnClickOutside: false
                        });
                    }
                }
            });


        });





    });
    $("#Credential-Checker").on("click", function() {
        alertify.dismissAll();
        SMTPServer = $("#stmpserverselection").val();
        EmialSender = $('#SenderE').val();
        EmailPassword = $('#SenderP').val();
        var validatesmtp = true;
        if (SMTPServer == "Emptyvalue") {
            alertify.error('Please select an smtp server.');
            validatesmtp = false;
        }
        const re =
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var emailValid = re.test(String(EmialSender).toLowerCase());

        if (EmialSender.trim() === "" || EmialSender.length < 3 || !emailValid) {
            alertify.error('Please check your email.');
            validatesmtp = false;
        }
        if (EmailPassword.trim() === "" || EmailPassword.length < 3) {
            alertify.error('Please check your password.');
            validatesmtp = false;
        }

        if (validatesmtp) {
            $("#loading-status-text").removeAttr('style');
            $("#loading-status-text").attr("style", "margin-left:-150%;");
            $("#loading-status-text").text("Connecting to Server...");
            $("#stmpserverselection").attr("disabled", "disabled");
            $("#SenderE").attr("disabled", "disabled");
            $("#SenderP").attr("disabled", "disabled");
            $("#Credential-Checker").attr("disabled", "disabled");
            $(".loader").removeClass("d-none");
            $.ajax({
                url: "emailCreditialsCheck",
                type: "POST",
                data: {
                    smtpserver: SMTPServer,
                    smatpemail: EmialSender,
                    smatppassword: EmailPassword,
                },
                cache: false,
                success: function(Result) {
                    console.log(Result);
                    if (Result == 'Valid') {
                        $(".loader").addClass("d-none");
                        swal({
                                title: "Valid account!",
                                icon: "success",
                                closeOnEsc: false,
                                closeOnClickOutside: false
                            })
                            .then((willOk) => {
                                if (willOk) {

                                    $("#smtp-Setup").addClass("d-none");
                                    $("#email-Setup").removeClass("d-none");
                                }
                            });
                    } else if (Result == "Invalid SMTP Server") {
                        $("#Credential-Checker").removeAttr("disabled");
                        $("#stmpserverselection").removeAttr("disabled");
                        $("#SenderE").removeAttr("disabled");
                        $("#SenderP").removeAttr("disabled");
                        $(".loader").addClass("d-none");
                        swal({
                            title: "Error!",
                            text: "Invalid SMTP Server!",
                            icon: "error",
                            button: "Ok!",
                            closeOnEsc: false,
                            closeOnClickOutside: false
                        });
                    } else if (Result == "Invalid account") {
                        $(".loader").addClass("d-none");
                        $("#Credential-Checker").removeAttr("disabled");
                        $("#stmpserverselection").removeAttr("disabled");
                        $("#SenderE").removeAttr("disabled");
                        $("#SenderP").removeAttr("disabled");
                        swal({
                            title: "Error!",
                            text: "Invalid account!",
                            icon: "error",
                            button: "Ok!",
                            closeOnEsc: false,
                            closeOnClickOutside: false
                        });
                    }

                }

            })
        }

    });

    function checkbounce(imap_server, server_email_imap, server_password_imap) {

        var analyzeCount = 1;
        $(".eachTR").each(function(index) {
            var scanbounceindex = index + 1;
            $.ajax({
                url: 'scanbounce',
                type: "POST",
                data: {
                    gmail_or_ops: imap_server,
                    server_password_imap: server_password_imap,
                    server_email_imap: server_email_imap,
                    email_scan: $(".sendingEmail" + scanbounceindex + "").text(),
                    email_subject: finalSubjectTitle
                },
                cache: false,
                success: function(Result) {
                    console.log("1st check");
                    analyzeCount++;

                    if (Result == '200') {
                        if (analyzeCount == $('.eachTR').length || $('.eachTR').length ==
                            1) {
                            checkbounce_checkagain(imap_server, server_email_imap,
                                server_password_imap);
                        }

                    } else if (Result == '550') {
                        if (analyzeCount == $('.eachTR').length || $('.eachTR').length ==
                            1) {
                            checkbounce_checkagain(imap_server, server_email_imap,
                                server_password_imap);
                        }
                    }
                }
            });

        });
    }

    function checkbounce_checkagain(imap_server, server_email_imap, server_password_imap) {

        var analyzeCount2 = 1;
        $(".eachTR").each(function(index) {
            var scanbounceindex2 = index + 1;
            $.ajax({
                url: 'scanbounce',
                type: "POST",
                data: {
                    gmail_or_ops: imap_server,
                    server_password_imap: server_password_imap,
                    server_email_imap: server_email_imap,
                    email_scan: $(".sendingEmail" + scanbounceindex2 + "").text(),
                    email_subject: finalSubjectTitle
                },
                cache: false,
                success: function(Result) {
                    analyzeCount2++;
                    console.log("2nd check");


                    if (Result == '200') {
                        $(".sendingstatus" + scanbounceindex2 + "").removeAttr("style");
                        $(".sendingstatus" + scanbounceindex2 + "").attr("style",
                            "color:green !important");
                        $(".sendingstatus" + scanbounceindex2 + "").text("Valid");
                        if (analyzeCount2 == $('.eachTR').length || $('.eachTR').length ==
                            1) {
                            $(".loader").addClass("d-none");
                            swal({
                                title: "Complete!",
                                text: "Analyzing " + $("#totalEmail").text() +
                                    " Emails Complete!",
                                icon: "success",
                                button: "Ok!",
                                closeOnEsc: false,
                                closeOnClickOutside: false
                            });

                        }

                    } else if (Result == '550') {
                        $(".sendingstatus" + scanbounceindex2 + "").removeAttr("style");
                        $(".sendingstatus" + scanbounceindex2 + "").attr("style",
                            "color:#93312F !important");
                        $(".sendingstatus" + scanbounceindex2 + "").text("Bounce");
                        if (analyzeCount2 == $('.eachTR').length || $('.eachTR').length ==
                            1) {
                            $(".loader").addClass("d-none");
                            swal({
                                title: "Complete!",
                                text: "Analyzing " + $("#totalEmail").text() +
                                    " Emails Complete!",
                                icon: "success",
                                button: "Ok!",
                                closeOnEsc: false,
                                closeOnClickOutside: false
                            });

                        }
                    }
                }
            });

        });
    }
});
</script>

</html>