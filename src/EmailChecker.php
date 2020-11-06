<?php

namespace Mailer;

class EmailChecker
{
    public function isEmailHostActive($email)
    {
        /*
        There's a myriad of ways to check a host, but for emails the best one
        is to check its DNS MX records.
        Sometimes the host won't have an MX record though, and will use
        a fallback to the A record.
        In this case, according to RFC 5321, a client may attempt to send
        the email to the IP provided by the A record.
        Since we're not a mail client, a simple fsockopen() on the standard
        http 80 port is enough to confirm the server is up and running.
        There's no guarantee that the client exists though,
        and most SMTP servers won't respons properly to VRFY commands,
        so that's about all we can do.
        */

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        $host = substr($email, strrpos($email, '@') + 1);

        if (checkdnsrr($host, 'MX')) {
            return true;
        } elseif (checkdnsrr($host, 'A')) {
            // fsockopen() will throw a warning on failure, but we don't care, so silence it
            $active = @fsockopen($host, 80);
            return $active !== false;
        } else {
            return false;
        }
    }
}