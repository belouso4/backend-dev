<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\AdminMailRequest;
use App\Mail\SendMail;
use App\Models\User;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends AdminController
{
    use ImageUploadTrait;

    public function store(AdminMailRequest $request)
    {
        $data = $request->only('subject', 'message');

        if ($request->hasFile('attachment')) {
            foreach ($request->file('attachment') as $file) {
                $data['attachment'][] = $file->storeAs('/mail', $this->imageName($file));
            }
        }

        if ((int)$request['select'] === 1) {
            $users = User::all('email');

            foreach ($users as $user) {
                Mail::to($user->email)
                    ->send(new SendMail(...$data));
            }

            return response()->json('ok2');
        }

        Mail::to($request['to'])
            ->send(new SendMail(...$data));

        return response()->json('ok');
    }

    public function search(Request $request) {
        $query = $request->query('query');
        $search = User::where('email', 'like', "%$query%")
            ->paginate(5, ['email']);

        return $search;

    }

}
