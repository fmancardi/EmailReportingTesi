EmailReporting Tesi
====================

Customization on latest stable release 0.8.4

* How this work
Main process is done on pages/bug_report_mail.php
Then process_mailbox() (mail_api.php) is called.
We use imap => process_imap_mailbox()