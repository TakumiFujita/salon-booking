<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmReservationSalon2User extends Mailable
{
    use Queueable, SerializesModels;

    public $validatedData;
    public $reservedServiceName;
    public $userName;

    /**
     * Create a new message instance.
     *
     * @param $validatedData
     * @param $reservedServiceName
     * @param $userName
     */
    public function __construct($validatedData, $reservedServiceName, $userName)
    {
        $this->validatedData = $validatedData;
        $this->reservedServiceName = $reservedServiceName;
        $this->userName = $userName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '予約完了のお知らせ',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.salons.reservation_completed',
            with: [
                'validatedData' => $this->validatedData,
                'reservedServiceName' => $this->reservedServiceName,
                'userName' => $this->userName,
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
