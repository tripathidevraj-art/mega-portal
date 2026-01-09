<?php

namespace App\Mail;

use App\Models\News;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsPublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public News $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Announcement: ' . $this->news->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.news.published', // ← We'll use raw HTML version
        );
    }
}