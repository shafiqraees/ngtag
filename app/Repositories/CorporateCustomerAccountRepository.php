<?php

namespace App\Repositories;

use App\Filters\QueryFilterBase;
use App\Models\AdminPortal;
use App\Models\CorpCustomerAccount;
use App\Models\CorpReserveTag;
use App\Models\CorpTagList;
use App\Traits\FilesTrait;
use App\Traits\StorageTrait;
use App\Traits\TaggedCache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class CorporateCustomerAccountRepository
{
    use FilesTrait, StorageTrait,TaggedCache;
    public $cache_tag = 'CorpCustomerAccount';
    /**
     * @param array $data
     * @return mixed
     * @throws \Throwable
     */
    public function uodateCorporateCustomer(array $data, CorpCustomerAccount $account) {

        try {
            // Get the base64 encoded file from the request
            $file_stored = null;
            $comp_doc_name2 = null;
            $comp_logo_file_name = null;
            if( !empty($data['document_name1']) ){
                $file_stored = $this->saveFile($data['document_name1'], 'msisnd/'.$data['msisdn'], $data['document_file_name1']);
            }if( !empty($data['document_name2']) ){
                $comp_doc_name2 = $this->saveFile($data['document_name2'], 'msisnd/'.$data['msisdn'], $data['document_file_name2']);
            }if( !empty($data['comp_logo_file_name']) ){
                $comp_logo_file_name = $this->saveFile($data['comp_logo_file_name'], 'msisnd/'.$data['msisdn'], 'comp_logo');
            }
            $account->phone_number = $data['msisdn'] ?? $account->phone_number;
            $account->comp_name = $data['company_name'] ?? $account->comp_name;
            $account->comp_brand = $data['comp_brand'] ?? $account->comp_brand;
            $account->comp_industry = $data['comp_industry'] ?? $account->comp_industry;
            $account->comp_country = $data['comp_country'] ?? $account->comp_country;
            $account->comp_state = $data['comp_state'] ?? $account->comp_state;
            $account->comp_city = $data['comp_city'] ?? $account->comp_city;
            $account->comp_addr = $data['comp_addr'] ?? $account->comp_addr;
            $account->comp_reg_no = $data['comp_reg_no'] ?? $account->comp_reg_no;
            $account->website = $data['website'] ?? $account->website;
            $account->website = $data['website'] ?? $account->website;
            $account->contact_fname = $data['contactf_name'] ?? $account->contact_fname;
            $account->contact_lname = $data['contactl_name'] ?? $account->contact_lname;
            $account->contact_no = $data['contact_no'] ?? $account->contact_no;
            $account->email = $data['email'] ?? $account->email;
            $account->ntn = $data['ntn'] ?? $account->ntn;
            $account->ntn = $data['ntn'] ?? $account->ntn;
            $account->comp_logo_file_name = $comp_logo_file_name ?? $account->comp_logo_file_name;
            $account->comp_doc_name1 = $file_stored ?? $account->comp_doc_name1;
            $account->file_doc_name1 = $data['document_file_name1'] ?? $account->file_doc_name1;
            $account->comp_doc_name2 = $comp_doc_name2 ?? $account->comp_doc_name2;
            $account->file_doc_name2 = $data['document_file_name2'] ?? $account->comp_doc_name2;
            $account->docs_upload_date = !empty($file_stored) ? Carbon::now() : $account->docs_upload_date;
            $account->doc_approval_status = $data['doc_approval_status'] ?? $account->doc_approval_status;
            $account->doc_approval_date = (isset($data['doc_approval_status']) && $data['doc_approval_status'] == 1)
                ? Carbon::now() : $account->doc_approval_date;
            $account->doc_approval_status = (isset($data['doc_approval_status']) && $data['doc_approval_status'] == 1)
                ? Auth::guard('admin')->user()->id : $account->doc_approval_status;
            $account->doc_approval_comments = $data['doc_approval_comments'] ?? $account->doc_approval_comments;
            $account->username = $data['username'] ?? $account->username;
            $account->password = Hash::make($data['password']) ?? $account->password;
            $account->passwd_change_date = isset($data['password']) ? Carbon::now() : $account->passwd_change_date;
            $account->passwd_change_date = isset($data['password']) ? Carbon::now() : $account->passwd_change_date;
            $account->user_lang = $data['user_lang'] ?? $account->user_lang;
            $account->channel = $data['channel'] ?? $account->channel;
            $account->status = $data['status'] ?? $account->status;
            $account->status = $data['status'] ?? $account->status;
            $account->status_update_date = isset($data['password']) ? Carbon::now() : $account->status_update_date;
            $account->save();
            return $account;
        } catch (\Exception $exception ) {
            report($exception);
            throw $exception;
        }
    }
    public function buyOrReserveTagNumber(array $data) {

        try {
            DB::beginTransaction();
            $corp_rserve_buy_tags =  CorpReserveTag::create([
                'corp_customer_account_id' => Auth::guard('corp_customer_accounts')->user()->getAuthIdentifier()
                    ?? CorpCustomerAccount::where('customer_account_id',$data['account_id'])->first()->id,
                'phone_number' => $data['mobile_no'] ?? null,
                'msisdn' => $data['mobile_no'] ?? null,
                'corp_tag_list_id' => $data['customer_tag_id'],
                'payment_method' => $data['payment_method'] ?? null,
                //'payment_status' => $data['payment_status'] ?? null,
                //'payment_date' => $data['comp_state'] ?? null,
                'expiry_date' => Carbon::now()->addHours(24) ?? null,
                'status' => isset($data['payment_method']) ? 3 : 0, //0=resver due to documents, 2=buy, 1=active, 3=pending for payment, 4=expired_docs, 5=expird_payment, 6= blockby admin
                //'status_update_date' => $data['comp_addr'] ?? null,
            ]);
            $corp_tag_list = CorpTagList::find($data['customer_tag_id']);
            $corp_tag_list->status = 3; //1=available, 2=sold, 3=reserved, 4=
            $corp_tag_list->save();

            DB::commit();
            return $corp_rserve_buy_tags;
        } catch (\Exception $exception ) {
            DB::rollBack();
            report($exception);
            throw $exception;
        }
    }
    public function documentProcess(array $data, CorpCustomerAccount $account) {
        try {
            $account->doc_approval_status = $data['doc_approval_status'] ?? $account->doc_approval_status;
            $account->doc_approval_date = (isset($data['doc_approval_status']) && $data['doc_approval_status'] == 1)
                ? Carbon::now() : $account->doc_approval_status;
            $account->doc_approval_admin_id = Auth::guard('admin')->user()->getAuthIdentifier()
                ?? AdminPortal::where('id',$data['account_id'])->first()->id;
            $account->doc_approval_comments = $data['description'] ?? $account->doc_approval_status;
            $account->save();
            if ($account->isDirty('status')) {
                $account->status_update_date = Carbon::now();
                $account->save();
            }
            return $account;
        } catch (\Exception $exception ) {
            report($exception);
            throw $exception;
        }
    }

    public function allCorporateCustomer(QueryFilterBase $filters = null) {
        try {

            $limit = request()->get('limit', config('cache.per_page'));
            return CorpCustomerAccount::paginate($limit);
            if (!empty($filters) && $filters->hasFilters()) {
                return CorpTagList::paginate($limit);
            }
            $key = "{$this->cache_tag}";
            if ($this->hasCache($this->cache_tag, $key)) {
                return $this->getCache($this->cache_tag, $key);
            } else {
                $results = CorpCustomerAccount::with('reserveTags')->filter($filters)->paginate($limit);
                return $this->storeCache($this->cache_tag, $key, $results, config('cache.duration'));
            }
        } catch (Throwable $exception) {

            report($exception);
            throw $exception;
        }
    }
}
