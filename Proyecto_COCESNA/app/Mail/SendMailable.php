<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $noEmp, $parametros)
    {
        $this->name = $name;
        $this->noEmp = $noEmp;
        $this->parametros = $parametros;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Resultados de encuesta')
                    ->from($address = env('MAIL_USERNAME'), $name = 'Recursos Humanos')
                    ->view('email.reporte',[
                        "nombre" => $this->name,
                        "noEmp" => $this->noEmp,
                        'parametros' => $this->parametros,
                    ]);
    }
}
