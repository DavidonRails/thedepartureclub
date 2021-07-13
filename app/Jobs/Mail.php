<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use SendGrid\Email;

class Mail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }


    const ACCEPTED_FLIGHT = '9c95a054-5aa4-45c2-aa9a-842053dddeef';
    const DECLINED_FLIGHT = '2619b6c1-71c2-45c9-8225-cd851fbcc3ed';
    const PASSWORD_RESET = '8617e3ac-43ce-4d33-ad10-6fb4408e0abc';
    const WELCOME_FACEBOOK  = '5ca066f3-d701-42fc-bcdf-a776d9abbbe0';
    const WELCOME = '1b9eca2a-18ad-4588-a084-ca754c48bc97';
    const EMAIL_CHANGE = 'eab165df-39a1-438c-b631-fd18fb81a87d';
    const FLIGHT_ALERT = 'd8bd2bec-d324-4098-a16a-10d209af67fc';
    
    public function handle()
    {
        $environment = \App::environment();

        if($environment == 'testing')
            return TRUE;
        

        $data = $this->data;

        $template_id = NULL;
        $vars = [];

        $subject = ' ';
        if(isset($data['subject']))
            $subject = $data['subject'];

        foreach ($data['data'] as $key => $var)
        {
            $key = ':' . strtoupper($key) . ':';
            $vars[$key] = [$var];
        }

        $vars[':DATE_SEND:'] = [date('F j, Y')];
        $vars[':URL:'] = [env('APP_URL')];

        $sendgrid = new \SendGrid(env('SENDGRID_API'));
        $mail = new \SendGrid\Email();

        $mail
            ->addTo($data['user']['email'], $data['user']['first_name'] . ' ' . $data['user']['last_name'])
            ->setFromName('Departure Club')
            ->setFrom('noreplay@thedepartureclub.com')
            ->setSubject($subject)
            ->setHtml(' ')
            ->setTemplateId($data['type'])
            ->setSubstitutions($vars);

        $response = $sendgrid->send($mail);

        \Log::useDailyFiles(storage_path('logs/sendgrid.log'));
        \Log::info($response->raw_body);
        
        if($response->code == 200)
            return TRUE;
        else
            return FALSE;

    }
}
