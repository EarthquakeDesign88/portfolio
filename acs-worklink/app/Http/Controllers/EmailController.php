<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormCreate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class EmailController extends Controller
{
    public function index() {
        $mails = FormCreate::orderBy('form_id', 'DESC')->paginate(10);;
        $mailCount = FormCreate::where('mail_status', '0')->count();

        return view('email.inbox', compact('mails', 'mailCount'));

    }

    public function read($id) {

        try{
            $mail = FormCreate::where('form_id', $id)->first();

            if ($mail) {

                if($mail->mail_status != '1'){
                    $mail->update([
                        'mail_status' => '1'
                    ]);
                }

                $mailCount = FormCreate::where('mail_status', '0')->count();
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $mail->created_at);
                $formattedDate = $date->format('d F Y');
        
                return view('email.read', compact('mail', 'formattedDate', 'mailCount'));
    
            } else {
                return redirect()->back();
            }
    
        }catch(\Exception $e){

            return redirect()->back();
        }

    }
}
