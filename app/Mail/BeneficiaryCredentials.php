<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class BeneficiaryCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject(' FEER | Twoje dane logowania do panelu eduk@acja')
            ->markdown('emails.beneficiary.credentials')
            ->with([
                'user' => $this->user,
                'password' => $this->password,
            ]);
    }


}
