<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once __DIR__ .'/../../vendor/box/spout/src/Spout/Autoloader/autoload.php';
require_once __DIR__ .'/../../vendor/autoload.php';


use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use benhall14\phpImapReader\Email as Email;
use benhall14\phpImapReader\EmailAttachment as EmailAttachment;
use benhall14\phpImapReader\Reader as Reader;

class Emailsender extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('imap');
    }
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD']=='POST') {
            $upload_status =  $this->uploadDoc();
            if ($upload_status!=false) {
                $this->session->sess_destroy();


                $inputFileName = 'assets/uploads/imports/'.$upload_status;
                $reader = ReaderEntityFactory::createReaderFromFile($inputFileName);
                $reader->open($inputFileName) ;
                $storedata = array();
                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $row) {
                        $cells= $row->getCells() ;
                        $FnameD = $cells[0]->getValue() ;
                        $LnameD = $cells[1]->getValue() ;
                        $EmailD = $cells[2]->getValue() ;
                        $CodeD = $cells[3]->getValue() ;
                        $data = array($FnameD,$LnameD,$EmailD,$CodeD);
                        //array_push($allinfor, $data);
                        $storedata[] = $data;
                    }
                }
                $allinfo['allinfo'] = $storedata;
                $reader->close();


                $this->session->set_flashdata('success', 'Successfully Data Imported');

                //redirect(base_url());
                $serviceSelected = $this->input->post("serviceselection");
                if ($serviceSelected == "sendemail") {
                    $this->load->view('includes/header');
                    $this->load->view('includes/navbar');
                    $this->load->view('sendnow', $allinfo);
                    $this->load->view('includes/footer');
                } elseif ($serviceSelected == "checkemail") {
                    $this->load->view('includes/header');
                    $this->load->view('includes/navbar');
                    $this->load->view('checknow', $allinfo);
                    $this->load->view('includes/footer');
                }
            } else {
                $this->session->set_flashdata('error', 'File is not uploaded');
                redirect(base_url());
            }
        } else {
            $this->load->view('includes/header');
            $this->load->view('excelimport');
            $this->load->view('includes/footer');
        }
    }

    public function uploadDoc()//To upload file
    {
        $resetDIR = __DIR__ .'/../../assets';
        shell_exec("rm -R " . $resetDIR);
        $uploadPath = 'assets/uploads/imports/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true); // FOR CREATING DIRECTORY IF ITS NOT EXIST
        }

        $config['upload_path']=$uploadPath;
        $config['allowed_types'] = 'csv|xlsx|xls';
        $config['max_size'] = 1000000;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('upload_excel')) {
            $fileData = $this->upload->data();
            return $fileData['file_name'];
        } else {
            return false;
        }
    }
    public function Ops()//Send using your email ex: iredmail
    {
        $emailReceiver = $this->input->post('emailReceiver', true);
        $emailSubject = $this->input->post('emailSubject', true);
        $emailHtmlMessage = $this->input->post('emailHtmlMessage', true);

        $config['protocol']    = 'smtp';
        $config['smtp_host']    = $this->session->OpsSmtpServer;
        $config['smtp_port']    = $this->session->OpsSmtpServerPort;
        $config['smtp_timeout'] = '10';
        $config['smtp_user']    = $this->session->IMAP_EMAIL_;
        $config['smtp_pass']    = $this->session->IMAP_PASSWORD_;
        $config['smtp_crypto'] = 'tls';
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['wordwrap']    = true;
        $config['_smtp_auth'] = true;
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = true;

        $this->email->initialize($config);
        $this->email->from($this->session->IMAP_EMAIL__);
        $this->email->to($emailReceiver);
        $this->email->subject($emailSubject);
        $this->email->message($emailHtmlMessage);
        if ($this->email->send()) {
            $responsejson = array('responseStatus' => "Send", 'responseMessage' => $this->email->send());
            echo json_encode($responsejson);
        } else {
            $responsejson = array('responseStatus' => "Error", 'responseMessage' => $this->email->print_debugger());
            echo json_encode($responsejson);
        }
    }
    public function Gmail()//Send using gmail
    {
        $emailReceiver = $this->input->post('emailReceiver', true);
        $emailSubject = $this->input->post('emailSubject', true);
        $emailHtmlMessage = $this->input->post('emailHtmlMessage', true);

        $config['protocol']    = 'smtp';
        $config['smtp_host']    = $this->session->GmailSmtpServer;
        $config['smtp_port']    = $this->session->GmailSmtpServerPort;
        $config['smtp_timeout'] = '10';
        $config['smtp_user']    = $this->session->IMAP_EMAIL_;
        $config['smtp_pass']    = $this->session->IMAP_PASSWORD_;
        $config['charset']    = 'utf-8';
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = true;

        $this->email->set_newline("\r\n");
        $this->email->initialize($config);
        $this->email->from($this->session->IMAP_EMAIL_);
        $this->email->to($emailReceiver);
        $this->email->subject($emailSubject);
        $this->email->message($emailHtmlMessage);
        if ($this->email->send()) {
            $responsejson = array('responseStatus' => "Send", 'responseMessage' => $this->email->send());
            echo json_encode($responsejson);
        } else {
            $responsejson = array('responseStatus' => "Error", 'responseMessage' => $this->email->print_debugger());
            echo json_encode($responsejson);
        }
    }

    public function emailCreditialsCheck()//To check if the email and password is valid
    {
        $this->load->library('session');
        $smatpServer = $this->input->post('smtpserver', true);
        $this->session->set_userdata('GmailImapServer', '{imap.gmail.com:993/imap/ssl/novalidate-cert}');
        $this->session->set_userdata('GmailSmtpServer', 'ssl://smtp.googlemail.com');
        $this->session->set_userdata('GmailSmtpServerPort', 465);
        $this->session->set_userdata('EmailSenderTest', 'test@ops.thinklogicmediagroup.com');
        $this->session->set_userdata('IMAP_EMAIL_', $this->input->post('smatpemail', true));
        $this->session->set_userdata('ATTACHMENT_PATH', '/../../attachments');

        $this->session->set_userdata('IMAP_PASSWORD_', strval($this->input->post('smatppassword')));
        $this->session->set_userdata('IMAP_MAILBOX_', $this->input->post('imapserver'));
        $this->session->set_userdata('OpsSmtpServer', $this->input->post('smtphostname', true));
        $this->session->set_userdata('OpsSmtpServerPort', intval($this->input->post('smtpport', true)));

        if ($smatpServer == "Gmail") {
            $config['protocol']    = 'smtp';
            $config['smtp_host']    = $this->session->GmailSmtpServer;
            $config['smtp_port']    = $this->session->GmailSmtpServerPort;
            $config['smtp_timeout'] = '10';
            $config['smtp_user']    = $this->session->IMAP_EMAIL_;
            $config['smtp_pass']    = $this->session->IMAP_PASSWORD_;
            $config['charset']    = 'utf-8';
            $config['mailtype'] = 'text'; // or html
            $config['validation'] = true;

            $this->email->set_newline("\r\n");
            $this->email->initialize($config);
            $this->email->from($this->session->IMAP_EMAIL_);
            $this->email->to($this->session->EmailSenderTest);
            $this->email->subject("test");
            $this->email->message("test");
            if ($this->email->send()) {
                $responsejson = array('responseStatus' => "Valid", 'responseMessage' => $this->email->send());
                echo json_encode($responsejson);
            } else {
                $responsejson = array('responseStatus' => "Invalid account", 'responseMessage' => $this->email->print_debugger());
                echo json_encode($responsejson);
            }
        } elseif ($smatpServer == "Ops") {
            $sds="";
            $sds2="";
            $fp = fsockopen($this->session->OpsSmtpServer, $this->session->OpsSmtpServerPort, $sds, $sds2, 5);
            if (!$fp) {
                echo "Invalid port";
                $responsejson = array('responseStatus' => "Invalid port", 'responseMessage' => !$fp);
                echo json_encode($responsejson);
            } else {
                $config['protocol']    = 'smtp';
                $config['smtp_host']    = $this->session->OpsSmtpServer;
                $config['smtp_port']    = $this->session->OpsSmtpServerPort;
                $config['smtp_timeout'] = '10';
                $config['smtp_user']    = $this->session->IMAP_EMAIL_;
                $config['smtp_pass']    = $this->session->IMAP_PASSWORD_;
                $config['smtp_crypto'] = 'tls';
                $config['charset']    = 'utf-8';
                $config['newline']    = "\r\n";
                $config['wordwrap']    = true;
                $config['_smtp_auth'] = true;
                $config['mailtype'] = 'text'; // or html
                $config['validation'] = true;

                $this->email->initialize($config);
                $this->email->from($this->session->IMAP_EMAIL_);
                $this->email->to($this->session->EmailSenderTest);
                $this->email->subject("test");
                $this->email->message("test");
                if ($this->email->send()) {
                    if (!imap_open($this->session->IMAP_MAILBOX_, $this->session->IMAP_EMAIL_, $this->session->IMAP_PASSWORD_)) {
                        $responsejson = array('responseStatus' => "Invalid SMTP Server", 'responseMessage' => !imap_open($this->session->IMAP_MAILBOX_, $this->session->IMAP_EMAIL_, $this->session->IMAP_PASSWORD_));
                        echo json_encode($responsejson);
                    } else {
                        $responsejson = array('responseStatus' => "Valid", 'responseMessage' => $this->email->send());
                        echo json_encode($responsejson);
                    }
                } else {
                    $responsejson = array('responseStatus' => "Invalid account", 'responseMessage' => $this->email->print_debugger());
                    echo json_encode($responsejson);
                }
                fclose($fp);
            }
        } else {
            $responsejson = array('responseStatus' => "Invalid SMTP Server", 'responseMessage' => $fp);
            echo json_encode($responsejson);
        }
    }
    public function resetConfig()//To reset
    {
        $resetDIR = __DIR__ .'/../../assets';
        shell_exec("rm -R " . $resetDIR);
        redirect(base_url());
    }
    public function scanbounce()//This function is to check the email is bounce because email receiver is not exist
    {
        $gmail_or_ops = $this->input->post('gmail_or_ops', true);
        $email_scan = $this->input->post('email_scan', true);
        $email_subject = $this->input->post('email_subject', true);


        if ($gmail_or_ops == 'Ops') {
            $server_imap = $this->session->IMAP_MAILBOX_;
            $subject_imap = 'Undelivered Mail Returned to Sender';
        }
        if ($gmail_or_ops == 'Gmail') {
            $server_imap = $this->session->GmailImapServer;
            $subject_imap = 'Delivery Status Notification (Failure)';
        }				# your imap password

        $mark_as_read = true;
        $encoding = 'UTF-8';
        $imap = new Reader($server_imap, $this->session->IMAP_EMAIL_, $this->session->IMAP_PASSWORD_, ATTACHMENT_PATH, $mark_as_read, $encoding);
        if ($imap) {
            $imap->searchSubject($subject_imap)->searchBody($email_scan)->onDate('now')->reset()->get();
            if (count($imap->emails()) > 0) {
                $responsejson = array('responseStatus' => "550", 'responseMessage' => count($imap->emails()) > 0);
                echo json_encode($responsejson);
            } else {
                $responsejson = array('responseStatus' => "200", 'responseMessage' => count($imap->emails()) > 0);
                echo json_encode($responsejson);
            }
        } else {
            $responsejson = array('responseStatus' => "not connected", 'responseMessage' => $imap);
            echo json_encode($responsejson);
        }
    }
}