<?php

namespace App\Console\Commands;

use App\Models\BankAccount;
use App\Models\Lead;
use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\TelegramHelper\Services\Telegram;

class SendStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle()
    {
        //todo: daily leads count, and their statuses
        //todo: daily new user count and all user count
        //todo: doaily order count and their statuses
        //todo: payment and debt statistics
        //todo:

        $bankAccounts = BankAccount::query()
            ->select(
                DB::raw('bank_accounts.*, SUM(payments.amount) as total_amount'),
                DB::raw('SUM(CASE WHEN payments.created_at = CURDATE() THEN payments.amount ELSE 0 END) as today_amount'),
            )
            ->leftJoin('payments', 'payments.bank_account_id', '=', 'bank_accounts.id')
            ->groupBy('bank_accounts.id')
            ->get();

        $result = '';
        foreach ($bankAccounts as $account) {
            $result .= '<b>' . $account->account_name . '</b>: ' . ($account->total_amount ?? 0) . ' / ' . $account->today_amount . PHP_EOL;
        }

        //Amaliyot:
        //Lidlar: Bugungi tushgan lidlar soni / shu oydagi jami / o'tgan oyga nisbatan farqi
        //Mijozlar: Bugungi mijozlar soni  / shu oydagi jami / o'tgan oyga nisbatan farqi
        //Sifatsiz lidlar: Bugungi soni  / shu oydagi jami / o'tgan oyga nisbatan farqi

        $lead_count = Lead::query()
            ->select(
                DB::raw('SUM(CASE WHEN leads.created_at = CURDATE() THEN 1 ELSE 0 END) as today_created'),
                DB::raw('SUM(CASE WHEN leads.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) THEN 1 ELSE 0 END) as month_before'),
                DB::raw('COUNT(id) as all_count'),
            )
            ->first();

        $canceledLeadCount = Lead::query()
            ->select(
                DB::raw('SUM(CASE WHEN leads.created_at = CURDATE() THEN 1 ELSE 0 END) as today_created'),
                DB::raw('SUM(CASE WHEN leads.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) THEN 1 ELSE 0 END) as month_before'),
                DB::raw('COUNT(leads.id) as all_count'),
            )
            ->join('lead_status', 'lead_status.id', '=', 'leads.status_id')
            ->where('lead_status.type', 'Не качественный ЛИД')
            ->first();

        $users_count = User::query()
            ->select(
                DB::raw('SUM(CASE WHEN users.created_at = CURDATE() THEN 1 ELSE 0 END) as today_created'),
                DB::raw('SUM(CASE WHEN users.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) THEN 1 ELSE 0 END) as month_before'),
                DB::raw('COUNT(id) as all_count'),

            )
            ->first();

        $debts = Order::query()
            ->select(
                DB::raw('(SUM(orders.total)-SUM(orders.total_paid)) as remind_amount'),
                DB::raw('COUNT(client_id) as all_count'),

            )
            ->first();

        $overdeadlineDebts = Order::query()
            ->select(
                DB::raw('(SUM(orders.total)-SUM(orders.total_paid)) as remind_amount'),
            )
//            ->where('remind_amount', '>', '0')
            ->where('created_at', '>', DB::raw('(DATE_ADD(CURDATE(), INTERVAL 3 DAY))'))
            ->groupBy('client_id')
            ->first();
        dd($overdeadlineDebts);
        $order_count = Order::query()
            ->select(['id', DB::raw('COUNT(*) as qt'), DB::raw('SUM(total) as total')])
//            ->where('created_at', '>=', now()->startOfDay())
            ->first();

        $message = "Bank: 
" . $result . '
<b>Jami qarzdor mijozlar:</b> ' . $debts->all_count . ' / ' . currency_format($debts->remind_amount, 1) . '
<b>Mudati otgan qarzdor mijozlar:</b> Soni / ' . currency_format($overdeadlineDebts->remind_amount, 1) . '
<b>Partnyorlardan jami qarz:</b> 0

<b>Lidlar:</b> ' . $lead_count->today_created . ' / ' . $lead_count->all_count . ' / ' . $lead_count->month_before . '
<b>Mijozlar:</b> ' . $users_count->today_created . ' / ' . $users_count->all_count . ' / ' . $users_count->month_before . '
<b>Sifatsiz lidlar:</b> ' . $canceledLeadCount->today_created . ' / ' . $canceledLeadCount->all_count . ' / ' . $canceledLeadCount->month_before . '

<b>Quantity of orders:</b> ' . $order_count->qt . ' / ' . currency_format($order_count->total, 1) . '

';
        Telegram::sendMessage($message);
    }

    /**
     * Execute the console command.
     */
    protected function withKeyMap($collection, $key1, $key2): array
    {
        $item = [];
        foreach ($collection as $index => $collect) {
            $item[$collect[$key1]][$collect[$key2]] = $collect;
        }
        return $item;
    }
}
