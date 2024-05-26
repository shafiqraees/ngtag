<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

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
            'channel' => 'required|string|max:10',
            'msisdn' => 'required|string|unique:corp_customer_accounts,phone_number',
            'account_id' => 'required|string|unique:corp_customer_accounts,username',
            'company_name' => 'required|string|unique:corp_customer_accounts,comp_name',
            'comp_industry' => 'nullable|string',
            'comp_state' => 'nullable|string',
            'comp_city' => 'nullable|string',
            'comp_country' => 'nullable|string',
            'comp_addr' => 'nullable|string',
            'comp_reg_no' => 'nullable|string|unique:corp_customer_accounts,comp_reg_no',
            //'ntn' => 'required_unless:comp_reg_no,null|digits_between:5,30|unique:corp_customer_accounts,ntn',
            'ntn' => 'nullable|string|unique:corp_customer_accounts,ntn',
            'contactf_name' => 'nullable|string',
            'contactl_name' => 'nullable|string',
            'email' => 'nullable|string',
            'contact_no' => 'nullable|numeric',
            'other_info' => 'nullable|string',
            'document_name1' => 'nullable|string',
            'document_file_name1' => 'nullable|string',
            'document_name2' => 'nullable|string',
            'document_file_name2' => 'nullable|string',
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
