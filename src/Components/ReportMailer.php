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


class ReportMailer
{
    /**
     * send email using GmailSmtpTransport
     * @param $sender
     * @param $receiver
     * @param $subject
     * @param $start_date
     * @param $end_date
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendEmail($sender, $receiver, $subject, $start_date, $end_date)
    {
        $transport = new GmailSmtpTransport($_ENV['SENDER_MAIL_USERNAME'], $_ENV['SENDER_MAIL_PASSWORD']);
        $mailer = new Mailer($transport);
        $email = (new Email())
            ->from($sender)
            ->to($receiver)
            ->subject($subject)
            ->text('From ' . $start_date . ' to ' . $end_date);
        $mailer->send($email);
    }

}