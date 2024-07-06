<?php

namespace App\Http\Requests;

use App\Rules\OTPType;
use App\Rules\OTPVerificationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class StoreCorpCustomerAccountRequest extends FormRequest
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
            'phone_number' => 'sometimes|string|unique:corp_customer_accounts,phone_number',
            'username' => 'sometimes|string|unique:corp_customer_accounts,username',
            'email' => 'sometimes|string|unique:corp_customer_accounts,email',
            'company_name' => 'sometimes|string|unique:corp_customer_accounts,comp_name',
            'comp_reg_no' => 'sometimes|string|unique:corp_customer_accounts,comp_reg_no',
            //'ntn' => 'required_unless:comp_reg_no,null|digits_between:5,30|unique:corp_customer_accounts,ntn',
            'ntn' => 'sometimes|string|unique:corp_customer_accounts,ntn',
            'otp_id' => 'required|string|exists:otp_processes,otp_id',
            'otp_code' => ['required', 'integer', 'digits:4', 'exists:otp_processes,otp_code', new OTPVerificationRule()],
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
