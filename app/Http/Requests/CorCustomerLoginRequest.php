<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CorCustomerLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'channel' => 'required|string|max:10',
            'username' => 'required|string|exists:corp_customer_accounts,username',
            'password' => 'required|string',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        if ($validator->fails()) {

            throw new HttpResponseException(response()->json([
                'response_status' => "error",
                'response_code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'success' => false,
                'message' => $this->validationErrorsToString($validator->errors()),
            ]));
        }
    }

    /**
     * @param array $errors
     * @return JsonResponse|void
     */
    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            return $this->apiResponse('error',JsonResponse::HTTP_UNPROCESSABLE_ENTITY,false, $errors);
        }
    }

    /**
     *
     * @param $errArray
     * @return string
     */
    public function validationErrorsToString($errArray) {
        $valArr = array();
        foreach ($errArray->toArray() as $key => $value) {
            $errStr = $key.' '.$value[0];
            array_push($valArr, $errStr);
        }
        if(!empty($valArr)){
            $errStrFinal = implode(',', $valArr);
        }
        return $errStrFinal;
    }

}
