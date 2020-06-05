<?php

namespace App\Mail;

use App\User;
use SendGrid;
use SendGrid\Mail\Mail;

class Mails {

    private  $email;
    private  $user;

    public function __construct($user_id) {
        $this->user     = User::find($user_id);
        $this->email    = new Mail();
    }

    public function mail_balace($balace) {

        $this->email->setFrom($this->user->email, $this->user->name);

        $this->email->setSubject("Thanks for the deposit, see your new balance");
        $this->email->addTo($this->user->email, $this->user->name);

        $data["name"]   = $this->user->name;
        $data["balace"] = $balace;
        $content_mail   = view("mails.balace", $data)->render();

        $this->email->addContent("text/html", $content_mail);
        $sendgrid = new SendGrid(env("SENDGRID_API_KEY"));

        try {
            $sendgrid->send($this->email);
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    }

    public function mail_purchase($real,$btc) {

        $this->email->setFrom($this->user->email, $this->user->name);

        $this->email->setSubject("Thank you for your purchase.");
        $this->email->addTo($this->user->email, $this->user->name);

        $data["name"]   = $this->user->name;
        $data["real"]   = $real;
        $data["btc"]    = $btc;
        $content_mail   = view("mails.purchase", $data)->render();

        $this->email->addContent("text/html", $content_mail);
        $sendgrid = new SendGrid(env("SENDGRID_API_KEY"));

        try {
            $sendgrid->send($this->email);
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    }

    public function mail_sales($real,$btc) {

        $this->email->setFrom($this->user->email, $this->user->name);

        $this->email->setSubject("Your Bitcoin sales order has been placed.");
        $this->email->addTo($this->user->email, $this->user->name);

        $data["name"]   = $this->user->name;
        $data["real"]   = $real;
        $data["btc"]    = $btc;
        $content_mail   = view("mails.sales", $data)->render();

        $this->email->addContent("text/html", $content_mail);
        $sendgrid = new SendGrid(env("SENDGRID_API_KEY"));

        try {
            $sendgrid->send($this->email);
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    }
}
