<?php

namespace App\Jobs;

use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $selectTo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $selectTo)
    {
        $this->selectTo = $selectTo;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = collect($this->data)->except('to');

        if ((int)$this->selectTo === 1) {
            $users = User::all('email');

            foreach ($users as $user) {
                Mail::to($user->email)
                    ->send(new SendMail(...$data));
            }
        } else {
            Mail::to($this->data['to'])
                ->send(new SendMail(...$data));
        }
    }
}
