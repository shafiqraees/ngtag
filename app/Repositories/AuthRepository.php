<?php

namespace App\Repositories;

use App\Http\Requests\CorCustomerLoginRequest;
use App\Models\AdminPortal;
use App\Models\CorpCustomerAccount;
use App\Traits\FilesTrait;
use App\Traits\StorageTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class AuthRepository
{
    use FilesTrait, StorageTrait;
    /**
     * @param array $data
     * @return mixed
     * @throws \Throwable
     */
    public function register(array $data) {
        try {

            // Get the base64 encoded file from the request
            $file_stored = null;
            $comp_doc_name2 = null;
            if( !empty($data['document_name1']) ){
                $file_stored = $this->saveFile($data['document_name1'], 'msisnd/'.$data['msisdn'], $data['document_file_name1']);
            }if( !empty($data['document_name2']) ){
                $comp_doc_name2 = $this->saveFile($data['document_name2'], 'msisnd/'.$data['msisdn'], $data['document_file_name2']);
            }
            $user =  CorpCustomerAccount::create([
                'customer_account_id' => Str::uuid()->toString(),
                'phone_number' => $data['phone_number'],
                'password' => isset($data['password'])
                    ? Hash::make($data['password'])
                    : Hash::make(config('app.default_password')),
                'username' => $data['account_id'],
                'comp_name' => $data['company_name'] ?? null,
                'comp_industry' => $data['comp_industry'] ?? null,
                'comp_state' => $data['comp_state'] ?? null,
                'comp_city' => $data['comp_city'] ?? null,
                'comp_country' => $data['comp_country'] ?? null,
                'comp_addr' => $data['comp_addr'] ?? null,
                'comp_reg_no' => $data['comp_reg_no'] ?? null,
                'ntn' => $data['ntn'] ?? null,
                'contact_fname' => $data['contactf_name'] ?? null,
                'contact_lname' => $data['contactl_name'] ?? null,
                'email' => $data['email'] ?? null,
                'contact_no' => $data['contact_no'] ?? null,
                'comp_doc_name1' => $file_stored ?? null,
                'file_doc_name1' => $data['document_file_name1'] ?? null,
                'comp_doc_name2' => $comp_doc_name2 ?? null,
                'file_doc_name2' => $data['document_file_name2'] ?? null,
                'docs_upload_date' => !empty($file_stored) ? Carbon::now() : null,
                'channel' => $data['channel'] ?? null,
            ]);
            //$token = $user->createToken('Customer', ['corporate-users'])->accessToken;
            $tokenResult = $user->createToken('corp_customer_accounts');
            $tokenResult->token->expires_at = now()->addHours(config('auth.token_expiration.corp_customer'));
            $tokenResult->token->save();
            $user->token = $tokenResult->accessToken;
            return $user;
        } catch (\Exception $exception ) {
            DB::rollBack();
            report($exception);
            throw $exception;
        }
    }

    public function login(array $data)
    {
        try {
            $user = CorpCustomerAccount::whereUsername($data['username'])->first();
            if ($user->login_attempts >= config('app.check_login_attempt')) {
                $user->status = 6;
                $user->save();
                throw new \Exception("Account block due to wrong password attempt");
            }
            if( $user && Hash::check($data['password'], $user->password) ) {
                $tokenResult = $user->createToken('corp_customer_accounts');
                $tokenResult->token->expires_at = now()->addHours(config('auth.token_expiration.corp_customer'));
                $tokenResult->token->save();
                $user->token = $tokenResult->accessToken;
                return $user;
            } else {
                $user->login_attempts +=1;
                $user->save();
            }
            return null;
        } catch (\Exception $exception ) {
            report($exception);
            throw $exception;
        }
    }
    public function adminLogin(array $data)
    {
        try {
            $admin = AdminPortal::whereUsername($data['username'])->first();
            if( $admin && Hash::check($data['password'], $admin->user_password) ) {
                $tokenResult = $admin->createToken('admin');
                $tokenResult->token->expires_at = now()->addHours(config('auth.token_expiration.admin'));
                $tokenResult->token->save();
                $admin->token = $tokenResult->accessToken;
                return $admin;
            }
            return null;
        } catch (\Exception $exception ) {
            report($exception);
            throw $exception;
        }
    }
}
