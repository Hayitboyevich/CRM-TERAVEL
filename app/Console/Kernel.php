<?php

namespace App\Console;

use App\Console\Commands\CallbackLeadCommand;
use App\Console\Commands\ClearNullSessions;
use App\Console\Commands\IntegrationCommand;
use App\Console\Commands\RemoveSeenNotification;
use App\Console\Commands\SendMessageCommand;
use App\Console\Commands\UpdateExchangeRates;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CallbackLeadCommand::class,
        IntegrationCommand::class,
//        IntegrationSync::class,
//        DataHelpCommand::class,
        UpdateExchangeRates::class,
//        AutoStopTimer::class,
//        SendEventReminder::class,
//        SendProjectReminder::class,
//        HideCronJobMessage::class,
//        SendAutoTaskReminder::class,
//        CreateTranslations::class,
//        AutoCreateRecurringInvoices::class,
//        AutoCreateRecurringExpenses::class,
        ClearNullSessions::class,
//        SendInvoiceReminder::class,
        RemoveSeenNotification::class,
//        SendAttendanceReminder::class,
//        AutoCreateRecurringTasks::class,
//        SyncUserPermissions::class,
//        SendAutoFollowUpReminder::class,
//        FetchTicketEmails::class,
//        AddMissingRolePermission::class,
//        BirthdayReminderCommand::class,
//        SendTimeTracker::class,
//        SendMonthlyAttendanceReport::class,
//        SendDailyTimelogReport::class,
        SendMessageCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Get the timezone from the configuration
        $timezone = config('app.cron_timezone');

        $schedule->command('mailing:send')->everyTenMinutes();
        $schedule->command('callback:lead-command')->everyTenMinutes();

        // Schedule the queue:work command to run without overlapping and with 3 tries
        $schedule->command('queue:work --tries=3 --stop-when-empty')->withoutOverlapping();
//        $schedule->command('recurring-task-create')->dailyAt('23:59')->timezone($timezone);
//        $schedule->command('auto-stop-timer')->dailyAt('23:30')->timezone($timezone);
//        $schedule->command('birthday-notification')->dailyAt('09:00')->timezone($timezone);

        // Every Minute
//        $schedule->command('send-event-reminder')->everyMinute();
//        $schedule->command('hide-cron-message')->everyMinute();
//        $schedule->command('send-attendance-reminder')->everyMinute();
//        $schedule->command('sync-user-permissions')->everyMinute();
//        $schedule->command('fetch-ticket-emails')->everyMinute();
//        $schedule->command('send-auto-followup-reminder')->everyMinute();
//        $schedule->command('send-time-tracker')->everyMinute();

        // Daily
//        $schedule->command('send-project-reminder')->daily()->timezone($timezone);
//        $schedule->command('send-auto-task-reminder')->daily()->timezone($timezone);
//        $schedule->command('recurring-invoice-create')->daily()->timezone($timezone);
//        $schedule->command('recurring-expenses-create')->daily()->timezone($timezone);
//        $schedule->command('send-invoice-reminder')->daily()->timezone($timezone);
        $schedule->command('delete-seen-notification')->daily()->timezone($timezone);
//        $schedule->command('update-exchange-rate')->daily()->timezone($timezone);
//        $schedule->command('send-daily-timelog-report')->daily()->timezone($timezone);
        $schedule->command('log:clear --keep-last')->daily()->timezone($timezone);

        // Hourly
        $schedule->command('clear-null-session')->hourly();
        $schedule->command('create-database-backup')->hourly();
        $schedule->command('delete-database-backup')->hourly();
//        $schedule->command('add-missing-permissions')->everyThirtyMinutes();
//        $schedule->command('send-monthly-attendance-report')->monthlyOn();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }

}
