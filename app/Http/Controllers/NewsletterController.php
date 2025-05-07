<?php

namespace App\Http\Controllers;

use App\Models\Logo;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class NewsletterController extends Controller
{
    public function index()
    {
        $mails = Newsletter::all();
        $logo = Logo::where('seccion', 'dashboard')->first();
        return Inertia::render('Admin/Newsletter', [
            'mails' => $mails,
            'logo' => $logo
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletters,email',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }
        $newsletter = new Newsletter();
        $newsletter->email = $request->email;
        $newsletter->save();

        
        return $this->success_response('Email guardado exitosamente.');
    }
    public function destroy($id)
    {
        $mail = Newsletter::findOrFail($id);
        $mail->delete();

        
        return $this->success_response('Email eliminado exitosamente.');
    }
}
