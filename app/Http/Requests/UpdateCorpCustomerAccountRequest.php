<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UpdateCorpCustomerAccountRequest extends FormRequest
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
        $user_id = $this->route('customer_account_id');
        //dd($user_id);
        return [
            'channel' => 'sometimes|string|max:10',
            'msisdn' => 'sometimes|string|unique:corp_customer_accounts,phone_number,' .$user_id->id,
            'account_id' => 'sometimes|string|unique:corp_customer_accounts,username,' .$user_id->id,
            'company_name' => 'sometimes|string|unique:corp_customer_accounts,comp_name,' .$user_id->id,
            'comp_industry' => 'nullable|string',
            'comp_state' => 'nullable|string',
            'comp_brand' => 'nullable|string',
            'comp_city' => 'nullable|string',
            'comp_country' => 'nullable|string',
            'comp_addr' => 'nullable|string',
            'comp_logo_file_name' => 'nullable|string',
            //'comp_doc_name2' => 'nullable|string', // contain file
            'comp_reg_no' => 'sometimes|string|unique:corp_customer_accounts,comp_reg_no,' .$user_id->id,
            //'ntn' => 'required_unless:comp_reg_no,null|digits_between:5,30|unique:corp_customer_accounts,ntn',
            'ntn' => 'sometimes|string|unique:corp_customer_accounts,ntn,' .$user_id->id,
            'website' => 'nullable|string',
            'contactf_name' => 'nullable|string',
            'contactl_name' => 'nullable|string',
            'email' => 'nullable|string',
            'contact_no' => 'nullable|string',
            'other_info' => 'nullable|string',
            'document_name1' => 'nullable|string',
            'document_file_name1' => 'nullable|string',
            'document_name2' => 'nullable|string', // contain company file
            'document_file_name2' => 'nullable|string', // contain company file name
            'doc_approval_comments' => 'nullable|string',
            'user_lang' => 'nullable|string',
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
