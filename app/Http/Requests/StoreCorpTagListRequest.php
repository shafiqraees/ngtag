<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

class StoreCorpTagListRequest extends FormRequest
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
        if ($this->has('customer_tag_id')) {
            $this->merge([
                'id' => $this->input('customer_tag_id'),
            ]);
        }
        return [
            'channel' => 'sometimes|string',
            'transaction_type' => 'sometimes|string',
            'transaction_id' => 'sometimes|string',
            'transmission_date_time' => 'sometimes|string',
            'phone_number' => 'required|string|exists:corp_customer_accounts,phone_number',
            //'msisdn' => 'required|string|exists:corp_customer_accounts,phone_number',
            'account_id' => 'required|string|exists:corp_customer_accounts,customer_account_id',
            //'customer_tag_id' => 'required|string|exists:corp_tag_lists,customer_tag_id',
            'id' => [
                'required',
                Rule::exists('corp_tag_lists')->where(function (Builder $query) {
                    return $query->where('status', 1);
                }),
            ],
            'msisdn' => 'required|string|unique:corp_subscribers,msisdn',
            'reserve_type' => 'required|string',
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
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.required' => 'The customer tag ID is required.',
            'id.exists' => 'The selected customer tag ID is invalid.',
            // Add other custom messages here
        ];
    }
}
