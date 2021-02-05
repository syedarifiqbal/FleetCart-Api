<?php


namespace Arif\FleetCartApi\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactEmail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct()
    {
        //
    }

    public function build()
    {
        return $this
            ->from(config('fleetcart_api.contact.from_email'))
            ->subject(config('fleetcart_api.contact.subject'))
            ->markdown('fleetcart_api::emails.contact_email');
    }

}
