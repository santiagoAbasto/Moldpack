<?php

namespace App\Http\Controllers\adm;
use App\Http\Controllers\Controller;
use App\Models\Subcriptores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubcriptoresController extends Controller
{
    public function subscribirse(Request $request){
        $subscriptor= new Subcriptores($request->all());
        $subscriptor->save();
    }
    public function verSubcriptores(){
        $users=Subcriptores::all();
        return view('adm.subscriptores.editarsubcriptores',compact('users'));
    }
    public function create(){
        return view('adm.subscriptores.nuevo');
    }
    public function store(Request $request){
        $users=Subcriptores::all();
        $asunto = $request->asunto;
        $body = $request->texto;
        $from='noresponder@siwo.com.ar';
        foreach($users as $user)
        {

        $to = $user->email;

        Mail::send('emails.masivo',
        array(  
            'texto' => $body,
        ), function($message) use ($from, $to, $asunto)
        {
        $message->from($from);
        $message->to($to)->subject($asunto);
        });


        } 
        return back()->with('success', ' Mensaje enviado!. ');
    }
}
