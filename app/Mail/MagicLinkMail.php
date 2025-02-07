<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MagicLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $magicLink; // This will hold the magic link

    /**
     * Create a new message instance.
     *
     * @param string $magicLink
     * @return void
     */
    public function __construct($magicLink)
    {
        $this->magicLink = $magicLink; // Pass the magic link to the Mailable
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Send a plain text email or simple HTML email
        return $this->subject('Your Magic Login Link')
            ->html(
                '<p>Click the link below to log in:</p>' .
                '<a href="' . $this->magicLink . '">' . $this->magicLink . '</a>' .
                '<p>This link will expire in 30 minutes.</p>'
            );
    }
}
