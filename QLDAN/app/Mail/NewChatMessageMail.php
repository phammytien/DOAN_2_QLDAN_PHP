<?php

namespace App\Mail;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewChatMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $conversation;
    public $senderName;
    public $recipientName;

    /**
     * Create a new message instance.
     */
    public function __construct(Message $message, Conversation $conversation)
    {
        $this->message = $message;
        $this->conversation = $conversation;
        
        // Get sender and recipient names
        if ($message->sender_type === 'SinhVien') {
            $this->senderName = $conversation->sinhVien->TenSV ?? 'Sinh viên';
            $this->recipientName = $conversation->giangVien->TenGV ?? 'Giảng viên';
        } else {
            $this->senderName = $conversation->giangVien->TenGV ?? 'Giảng viên';
            $this->recipientName = $conversation->sinhVien->TenSV ?? 'Sinh viên';
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tin nhắn mới từ ' . $this->senderName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-chat-message',
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
