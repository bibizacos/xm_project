<?php
/**
 * Created by PhpStorm.
 * User: bibiz
 * Date: 02-May-20
 * Time: 2:02 AM
 */

namespace App\Components;


use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;


class SwiftMailer
{

    public function sendEmail()
    {
        $transport = new GmailSmtpTransport('ibi.elton@gmail.com', '.!mynameisrex!.');
        $mailer = new Mailer($transport);
        $email = (new Email())
            ->from('ibi.elton@gmail.com')
            ->to('bibizacos@hotmail.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

    }

}