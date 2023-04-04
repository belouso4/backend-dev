<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\AdminMailRequest;
use App\Mail\SendMail;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends AdminController
{
    use ImageUploadTrait;

    public function store(AdminMailRequest $request)
    {
        $data = $request->only('to', 'subject', 'message' );

        if ($request->hasFile('attachment')) {
            foreach ($request->file('attachment') as $file) {
                $data['attachment'][] = $file->storeAs('/mail', $this->imageName($file));
            }
        }

        Mail::to($data['to'])
            ->send(new SendMail($data));

        return response()->json('ok');
    }

}
