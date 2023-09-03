# PHP-Imap

Send Bulk Email(HTML) and check email if exist using Codeigniter 3
from CSV,xlsl and xls file format
example format:

| First name | Last name | Email | Code |
| :--------: | :-------: | :---: | :--: |

## Compatibility

Using Gmail Gsuite only
Note: Turn on Less secure app access in your gsuite account
https://myaccount.google.com/lesssecureapps

Using Own email Server
Note: make use imap your hostname is correct.
https://www.php.net/manual/en/function.imap-open.php

## Error

error 504

- its happen if you check/send more than 50 emails
  How to fixed to prevent 504 time out error in more that 50 email
  Using apache/nginx
  increase max-execution-time.
