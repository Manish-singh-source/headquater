<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

     public $email;
    public $password;
    public $fname;
    public $lname;

    /**
     * Create a new message instance.
     */
    public function __construct($email, $password, $fname = null, $lname = null)
    {
        $this->fname = $fname;
        $this->lname = $lname;  
         $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Your Staff Account Credentials')
            ->view('emails.staff_credentials');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Headquaters Staff Credentials Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.staff_credentials',
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
