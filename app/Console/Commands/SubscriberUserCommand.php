<?php

namespace App\Console\Commands;

use App\Enums\CorpCustomerAccountStatusEnum;
use App\Models\CorpCustomerAccount;
use App\Models\CorpSubscriber;
use App\Models\Subscriber;
use Illuminate\Console\Command;

class SubscriberUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:subscriber-user-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $users = CorpCustomerAccount::where('doc_approval_status',1)
                ->where('status', CorpCustomerAccountStatusEnum::APPROVED->value)
                ->whereHas('corpReserveTag', function ($query) {
                    $query->where('payment_status',1);
                })->with('corpReserveTag.corpTagList')->get();
            $bar = $this->output->createProgressBar(count($users));
            $bar->start();
            foreach ($users as $user) {
                foreach ($user->corpReserveTag as $tag) {
                    CorpSubscriber::updateOrCreate(
                        [
                            'account_id' => $user->id,
                            'tag_id' => $tag->corp_tag_list_id,
                        ],
                        ['msisdn' => $tag->msisdn ?? null,
                            'name_tag' => $tag->corpTagList->tag_name ?? null,
                            'tag_no' => $tag->corpTagList->tag_no ?? null,
                            'tag_type' => $tag->corpTagList->tag_type ?? null,
                            'tag_length' => $tag->corpTagList->tag_digits ?? null,
                            'tag_no_price' => $tag->corpTagList->tag_price ?? null,
                            'payment_method' => $tag->payment_method ?? null,
                            'payment_status' => $tag->payment_status ?? null,
                            'payment_date' => $tag->payment_date ?? null,
                            'expiry_date' => $tag->expiry_date ?? null,
                            'service_fee' => $tag->corpTagList->service_fee ?? null,
                            'sub_date' => $tag->created_date ?? null,
                            'unsub_date' => $user->created_at,
                            //'unsub_channel' => $user->created_at,
                            'charge_dt' => $tag->payment_date ?? null,
                            'next_charge_dt' => $tag->expiry_date ?? null,
                            'status' => $tag->status ?? null,
                            'status_update_date' => $user->status_update_date ?? null,
                            //'service_id' => $user->created_at,
                        ]);
                }
                $bar->advance();
            }
            $bar->finish();
            return true;
        } catch (\Exception $exception) {
            report($exception);
        }
    }
}
