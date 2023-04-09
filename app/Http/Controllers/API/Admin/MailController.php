<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\AdminMailRequest;
use App\Jobs\SendEmailJob;
use App\Mail\SendMail;
use App\Models\User;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MailController extends AdminController
{
    use ImageUploadTrait;

    public function store(AdminMailRequest $request)
    {
        $data = $request->only('subject', 'message', 'to');

        if ($request->hasFile('attachment')) {
            foreach ($request->file('attachment') as $file) {
                $img = $file->storeAs('/mail', $this->imageName($file));
                $path = public_path('storage/'. $img);

                $data['attachment'][$path] =  [
                    'as' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                ];
            }
        }

        SendEmailJob::dispatch($data, $request['select']);

        return response()->json('ok');
    }

    public function search(Request $request) {
        $query = $request->query('query');
        $search = User::where('email', 'like', "%$query%")
            ->paginate(5, ['email']);

        return $search;

    }

}
