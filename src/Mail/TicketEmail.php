<?php

namespace glorifiedking\BusTravel\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $address = 'tickets@palmkash.com';
        $subject = 'Bus Travel Ticket';
        $name = 'Palm Kash';

        return $this->view('bustravel::backend.notifications.email_ticket')
                    ->from($address, $name)
                    ->cc($address, $name)
                    ->bcc($address, $name)
                    ->replyTo($address, $name)
                    ->subject($subject)
                    ->with([ 'email_message' => $this->data['message'] ]);
    }
}