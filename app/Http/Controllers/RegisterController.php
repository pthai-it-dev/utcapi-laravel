<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidFormRequestException;
use App\Http\FormRequest\RegisterForm1;
use App\Http\FormRequest\RegisterForm2;
use App\Services\Contracts\RegisterServiceContract;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    private RegisterForm1 $form1;
    private RegisterForm2 $form2;
    private RegisterServiceContract $registerService;

    /**
     * @param RegisterForm1           $form1
     * @param RegisterForm2           $form2
     * @param RegisterServiceContract $registerService
     */
    public function __construct (RegisterForm1           $form1, RegisterForm2 $form2,
                                 RegisterServiceContract $registerService)
    {
        $this->form1           = $form1;
        $this->form2           = $form2;
        $this->registerService = $registerService;
    }

    /**
     * @throws InvalidFormRequestException
     */
    public function process1 (Request $request)
    {
        $this->form1->validate($request);
        if (!$this->registerService->process1($request->id_student, $request->qldt_password))
        {
            return response('Account available', 406);
        }
        return $this->process2($request);
    }

    /**
     * @throws InvalidFormRequestException
     */
    public function process2 (Request $request)
    {
        $this->form2->validate($request);
        return $this->registerService->process2($request->all());
    }
}
