<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mikehaertl\pdftk\Pdf;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function admin(Request $request){
        if($request->ajax()) {
            if (Auth::check() && Auth::user()->authorizeRoles(['admin'])) {
                return response('true');
            }else {
                return response('false');
            }

        }

    }

    public function storePdf(Request $request)
    {
        return view("EXAMPLE2");
        $_SESSION['state'] = bin2hex(random_bytes(5));

        $authorize_url = 'https://appleid.apple.com/auth/authorize'.'?'.http_build_query([
                'response_type' => 'code',
                'response_mode' => 'form_post',
                'client_id' => 'lol.avocado.client',
                'redirect_uri' => 'https://example-app.com/redirect',
                'state' => $_SESSION['state'],
                'scope' => 'name email',
            ]);
        echo '<a href="'.$authorize_url.'">Sign In with Apple</a>';
//        $pdf = new Pdf('/home/developer/Documents/form.pdf');
//        $pdf->tempDir = '/home/developer/tmp';
////        $data = $pdf->getDataFields();
////        echo '<pre>';
////        print_r($data);
////        $pdf->generateFdfFile('/home/developer/Documents/data.fdf');
//        $pdf->fillForm([
//        'form1[0].#subform[0].P1_Line1c_Checkbox[0]' => 'Y',
//        'form1[0].#subform[0].P1_Line1a_Checkbox[0]' => 'Y',
//            'form1[0].#subform[0].P1_Line1e1_Checkbox[1]' => '2',
//            'form1[0].#subform[0].P2_Line2e_State[0]' => 'FL',
//            'form1[0].#subform[0].P1_Line1_Name[0]'=>'ZZTop'
//        ])
//            ->needAppearances()
//            ->saveAs('/home/developer/Documents/filled.pdf');
//        dd($pdf);

    }
}
