<?php

namespace App\Repositories;

use App\Enums\CorpCustomerAccountDocumentStatusEnum;
use App\Enums\CorpCustomerAccountStatusEnum;
use App\Enums\CorpReserveTagPaymentStatusEnum;
use App\Enums\CorpReserveTagStatusEnum;
use App\Filters\QueryFilterBase;
use App\Models\AdminPortal;
use App\Models\CorpCustomerAccount;
use App\Models\CorpReserveTag;
use App\Models\CorpSubscriber;
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
                $file_stored = $this->saveFile($data['document_name1'], 'msisnd/'.$account->phone_number, $data['document_file_name1']);
            }if( !empty($data['document_name2']) ){
                $comp_doc_name2 = $this->saveFile($data['document_name2'], 'msisnd/'.$account->phone_number, $data['document_file_name2']);
            }if( !empty($data['comp_logo_file_name']) ){
                $comp_logo_file_name = $this->saveFile($data['comp_logo_file_name'], 'msisnd/'.$account->phone_number, 'comp_logo');
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
            $account->contact_fname = $data['contactf_name'] ?? $account->contact_fname;
            $account->contact_lname = $data['contactl_name'] ?? $account->contact_lname;
            $account->contact_no = $data['contact_no'] ?? $account->contact_no;
            $account->email = $data['email'] ?? $account->email;
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
            $account->password = isset($data['password']) ? Hash::make($data['password']) : $account->password;
            $account->passwd_change_date = isset($data['password']) ? Carbon::now() : $account->passwd_change_date;
            $account->user_lang = $data['user_lang'] ?? $account->user_lang;
            $account->channel = $data['channel'] ?? $account->channel;
            $account->status = $data['status'] ?? $account->status;
            if ($account->isDirty('status')) {
                $account->status_update_date = Carbon::now();
            }
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
            $cor_account = CorpCustomerAccount::where('customer_account_id',$data['account_id'])->first();
            $status = CorpReserveTagStatusEnum::RESERVE_DUE_TO_DOCUMENTS->value;
            if ($cor_account->doc_approval_status == 1 && isset($data['payment_method']) && $cor_account->status == 1)  {
                $status = CorpReserveTagStatusEnum::BUY->value;
            } elseif ($cor_account->doc_approval_status != 1 && isset($data['payment_method']) && $cor_account->status == 1) {
                $status = CorpReserveTagStatusEnum::RESERVE_DUE_TO_DOCUMENTS->value;
            }elseif ($cor_account->doc_approval_status != 1 && isset($data['payment_method']) && $cor_account->status != 1) {
                $status = CorpReserveTagStatusEnum::BLOCKED_BY_ADMIN->value;
            }
            //TODO add Payment Service here which return payment details for now assume payment is done
            $corp_rserve_buy_tags =  CorpReserveTag::create([
                'corp_customer_account_id' => $cor_account->id,
                'phone_number' => $data['phone_number'] ?? null,
                'msisdn' => $data['msisdn'] ?? null,
                'corp_tag_list_id' => $data['customer_tag_id'],
                'payment_method' => $data['payment_method'] ?? null,
                'payment_status' => $data['payment_method'] ? 1 : 0, //0=pending for payment, 1=payment success, 2=Payment Failed, 3=expired payment timeline
                'payment_date' => isset($data['payment_method']) ? Carbon::now() : null ,
                'expiry_date' => Carbon::now()->addHours(config('app.reserve_number_expiry_date')) ?? Carbon::now()->addHours(2),
                'status' => $status, //0=reserve due to documents, 2=buy, 1=active, 3=pending for payment, 4=expired_docs, 5=expired_payment, 6= blockby admin
                //'status_update_date' => $data['comp_addr'] ?? null,
            ]);
            $corp_tag_list = CorpTagList::find($data['customer_tag_id']);
            $corp_tag_list->status = 3; //1=available, 2=sold, 3=reserved, 4=
            $corp_tag_list->save();
            if ($cor_account->status == CorpCustomerAccountStatusEnum::APPROVED->value
                && $cor_account->doc_approval_status == CorpCustomerAccountDocumentStatusEnum::APPROVED->value
                && $corp_rserve_buy_tags->payment_status == CorpReserveTagPaymentStatusEnum::PAYMENT_SUCCESS->value
            ) {
                $Corp_Subscriber = CorpSubscriber::where('account_id',$cor_account->id)
                    ->where('tag_id',$corp_rserve_buy_tags->id)->first() ?? new CorpSubscriber();
                $Corp_Subscriber->account_id = $cor_account->id;
                $Corp_Subscriber->tag_id = $corp_rserve_buy_tags->id;
                $Corp_Subscriber->msisdn = $Corp_Subscriber->msisdn ?? $corp_rserve_buy_tags->msisdn ?? $corp_rserve_buy_tags->phone_number;
                $Corp_Subscriber->name_tag = $Corp_Subscriber->name_tag ?? $corp_tag_list->tag_name;
                $Corp_Subscriber->tag_no = $Corp_Subscriber->tag_no ?? $corp_tag_list->tag_no;
                $Corp_Subscriber->tag_type = $Corp_Subscriber->tag_type ?? $corp_tag_list->tag_type;
                $Corp_Subscriber->tag_length = $Corp_Subscriber->tag_length ?? $corp_tag_list->tag_digits;
                $Corp_Subscriber->tag_no_price = $Corp_Subscriber->tag_no_price ?? $corp_tag_list->tag_price;
                $Corp_Subscriber->payment_method = $corp_rserve_buy_tags->payment_method ?? $Corp_Subscriber->payment_method;
                $Corp_Subscriber->payment_status = $corp_rserve_buy_tags->payment_status ?? $Corp_Subscriber->payment_status;
                $Corp_Subscriber->payment_date = $corp_rserve_buy_tags->payment_date ?? $Corp_Subscriber->payment_date;
                $Corp_Subscriber->expiry_date = $corp_rserve_buy_tags->expiry_date ?? $Corp_Subscriber->expiry_date ?? Carbon::now()->addHours(config('app.reserve_number_expiry_date'));
                $Corp_Subscriber->service_fee = $corp_tag_list->service_fee ?? $Corp_Subscriber->service_fee;
                $Corp_Subscriber->sub_date = $corp_rserve_buy_tags->created_date ?? $Corp_Subscriber->sub_date;
                $Corp_Subscriber->sub_date = $corp_rserve_buy_tags->created_date ?? $Corp_Subscriber->sub_date ?? Carbon::now();
                $Corp_Subscriber->charge_dt = $corp_rserve_buy_tags->payment_date ?? $Corp_Subscriber->charge_dt ?? Carbon::now();
                $Corp_Subscriber->next_charge_dt = $Corp_Subscriber->next_charge_dt ?? Carbon::now()->addHours(config('app.reserve_number_next_charge_date')) ;
                $Corp_Subscriber->status = $corp_rserve_buy_tags->status ?? $Corp_Subscriber->status;
                $Corp_Subscriber->corp_subscriber_id = $Corp_Subscriber->corp_subscriber_id ?? Str::uuid()->toString();
                if ($Corp_Subscriber->isDirty('status')) {
                    $Corp_Subscriber->status_update_date = Carbon::now();
                }
                $Corp_Subscriber->save();
            }
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
            return CorpCustomerAccount::filter($filters)->paginate($limit);
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

    public function reserveTagList(CorpCustomerAccount $customer_account_id,QueryFilterBase $filters = null) {
        try {
            $limit = request()->get('limit', config('cache.per_page'));
            return CorpReserveTag::where('corp_customer_account_id',$customer_account_id->id)->filter($filters)->paginate($limit);
        } catch (Throwable $exception) {
            report($exception);
            throw $exception;
        }
    }
}
