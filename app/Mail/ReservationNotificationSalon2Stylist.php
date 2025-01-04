<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationNotificationSalon2Stylist extends Mailable
{
    use Queueable, SerializesModels;

    public $validatedData;
    public $reservedServiceName;
    public $reservedUserName;
    public $stylistName;

    /**
     * Create a new message instance.
     */
    public function __construct($validatedData, $reservedServiceName, $reservedUserName, $stylistName)
    {
        $this->validatedData = $validatedData;
        $this->reservedServiceName = $reservedServiceName;
        $this->reservedUserName = $reservedUserName;
        $this->stylistName = $stylistName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '新規予約のお知らせ',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.salons.reservation_notification2stylist',
            with: [
                'validatedData' => $this->validatedData,
                'reservedServiceName' => $this->reservedServiceName,
                'reservedUserName' => $this->reservedUserName,
                'stylistName' => $this->stylistName,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
