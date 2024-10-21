<?php

namespace App\Models\Mail;

use App\Models\Utility;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommonEmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $store;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($template, $store)
    {
        $this->template = $template;
        $this->store = $store;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = \Auth::user();
        $settings = Utility::settings(1);
        if($user == null || $user->type == "super admin"){
            $from = isset($settings['mail_from_address']) ? $settings['mail_from_address'] : $this->template->from;
            return $this->from(isset($settings['mail_from_address']) ? $settings['mail_from_address'] : '', $from)->markdown('emails.common_email_template')->subject($this->template->subject)->with(
                [
                    'content' => $this->template->content,
                    'mail_header' => env('APP_NAME'),
                ]
            );
        }else{
            $from = !empty($this->store['mail_from_address']) ? $this->store['mail_from_address'] : $this->template->from;
            return $this->from($this->store['email'], $from)->markdown('emails.common_email_template')->subject($this->template->subject)->with(
                [
                    'content' => $this->template->content,
                    'mail_header' => $this->store['name'],
                ]
            );
        }
    }
}
