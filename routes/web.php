<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\Admin\AdminCustomFieldsController;
use App\Http\Controllers\Admin\AdminProfileSettingsController;
use App\Http\Controllers\Admin\AttendanceSettingController;
use App\Http\Controllers\Admin\CurrencySettingController;
use App\Http\Controllers\Admin\GoogleSettingController;
use App\Http\Controllers\Admin\InvoiceSettingController;
use App\Http\Controllers\Admin\LeadAgentSettingController;
use App\Http\Controllers\Admin\LeadSourceSettingController;
use App\Http\Controllers\Admin\LeadStatusSettingController;
use App\Http\Controllers\Admin\LeavesSettingController;
use App\Http\Controllers\Admin\LogTimeSettingsController;
use App\Http\Controllers\Admin\MessageSettingsController;
use App\Http\Controllers\Admin\ModuleSettingsController;
use App\Http\Controllers\Admin\PaymentGatewayCredentialController;
use App\Http\Controllers\Admin\ProjectSettingsController;
use App\Http\Controllers\Admin\PusherSettingsController;
use App\Http\Controllers\Admin\PushNotificationController;
use App\Http\Controllers\Admin\SlackSettingController;
use App\Http\Controllers\Admin\TaskSettingsController;
use App\Http\Controllers\Admin\ThemeSettingsController;
use App\Http\Controllers\Admin\TicketAgentsController;
use App\Http\Controllers\Admin\TicketChannelsController;
use App\Http\Controllers\Admin\TicketGroupsController;
use App\Http\Controllers\Admin\TicketReplyTemplatesController;
use App\Http\Controllers\Admin\TicketTypesController;
use App\Http\Controllers\Api\PassportScanController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Applications\ApplicationController;
use App\Http\Controllers\Applications\CustomClientController;
use App\Http\Controllers\Applications\CustomPaymentController;
use App\Http\Controllers\Applications\LocationSchemaController;
use App\Http\Controllers\Applications\PackageController;
use App\Http\Controllers\Applications\PartnerDebitsController;
use App\Http\Controllers\Applications\ServicesController;
use App\Http\Controllers\AppreciationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClientCategoryController;
use App\Http\Controllers\ClientContactController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientDocController;
use App\Http\Controllers\ClientNoteController;
use App\Http\Controllers\ClientSubCategoryController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractDiscussionController;
use App\Http\Controllers\ContractFileController;
use App\Http\Controllers\ContractRenewController;
use App\Http\Controllers\ContractTemplateController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeadlinePaymentController;
use App\Http\Controllers\DebitController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DiscussionCategoryController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\DiscussionFilesController;
use App\Http\Controllers\DiscussionReplyController;
use App\Http\Controllers\EmergencyContactController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocController;
use App\Http\Controllers\EmployeeShiftChangeRequestController;
use App\Http\Controllers\EmployeeShiftScheduleController;
use App\Http\Controllers\EmployeeVisaController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\EstimateTemplateController;
use App\Http\Controllers\EventCalendarController;
use App\Http\Controllers\EventFileController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseReportController;
use App\Http\Controllers\FinanceReportController;
use App\Http\Controllers\GdprController;
use App\Http\Controllers\GdprSettingsController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\IncomeVsExpenseReportController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceFilesController;
use App\Http\Controllers\KnowledgeBaseCategoryController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\KnowledgeBaseFileController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\LeadBoardController;
use App\Http\Controllers\LeadCategoyController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadCustomFormController;
use App\Http\Controllers\LeadFileController;
use App\Http\Controllers\LeadNoteController;
use App\Http\Controllers\LeadReportController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveFileController;
use App\Http\Controllers\LeaveReportController;
use App\Http\Controllers\LeavesQuotaController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessageFileController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\PassportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductFileController;
use App\Http\Controllers\ProductSettingController;
use App\Http\Controllers\ProductSubCategoryController;
use App\Http\Controllers\ProjectCalendarController;
use App\Http\Controllers\ProjectCategoryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\ProjectMilestoneController;
use App\Http\Controllers\ProjectNoteController;
use App\Http\Controllers\ProjectRatingController;
use App\Http\Controllers\ProjectTemplateController;
use App\Http\Controllers\ProjectTemplateMemberController;
use App\Http\Controllers\ProjectTemplateSubTaskController;
use App\Http\Controllers\ProjectTemplateTaskController;
use App\Http\Controllers\ProjectTimelogBreakController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ProposalTemplateController;
use App\Http\Controllers\QuickbookController;
use App\Http\Controllers\RecurringExpenseController;
use App\Http\Controllers\RecurringInvoiceController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\StickyNoteController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\SubTaskFileController;
use App\Http\Controllers\SuperAdmin\CtaSettingController;
use App\Http\Controllers\SuperAdmin\CustomFieldsController;
use App\Http\Controllers\SuperAdmin\CustomModuleController;
use App\Http\Controllers\SuperAdmin\FrontClientSettingController;
use App\Http\Controllers\SuperAdmin\FrontFaqSettingController;
use App\Http\Controllers\SuperAdmin\FrontFeatureSettingController;
use App\Http\Controllers\SuperAdmin\FrontMenuSettingController;
use App\Http\Controllers\SuperAdmin\FrontWidgetsController;
use App\Http\Controllers\SuperAdmin\GoogleCalendarSettingsController;
use App\Http\Controllers\SuperAdmin\OfflinePaymentSettingController;
use App\Http\Controllers\SuperAdmin\OfflinePlanChangeController;
use App\Http\Controllers\SuperAdmin\StorageSettingsController;
use App\Http\Controllers\SuperAdmin\SuperAdminCompanyController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\SuperAdminCurrencySettingController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminEmailSettingsController;
use App\Http\Controllers\SuperAdmin\SuperAdminFaqCategoryController;
use App\Http\Controllers\SuperAdmin\SuperAdminFaqController;
use App\Http\Controllers\SuperAdmin\SuperAdminFeatureSettingController;
use App\Http\Controllers\SuperAdmin\SuperAdminFooterSettingController;
use App\Http\Controllers\SuperAdmin\SuperAdminFrontSettingController;
use App\Http\Controllers\SuperAdmin\SuperAdminInvoiceController;
use App\Http\Controllers\SuperAdmin\SuperAdminLanguageSettingsController;
use App\Http\Controllers\SuperAdmin\SuperAdminPackageController;
use App\Http\Controllers\SuperAdmin\SuperAdminPackageSettingController;
use App\Http\Controllers\SuperAdmin\SuperAdminProfileController;
use App\Http\Controllers\SuperAdmin\SuperAdminPushSettingsController;
use App\Http\Controllers\SuperAdmin\SuperAdminSecuritySettingsController;
use App\Http\Controllers\SuperAdmin\SuperAdminSeoDetailController;
use App\Http\Controllers\SuperAdmin\SuperAdminSettingsController;
use App\Http\Controllers\SuperAdmin\SuperAdminSignUpController;
use App\Http\Controllers\SuperAdmin\SuperAdminSocialAuthSettingsController;
use App\Http\Controllers\SuperAdmin\SuperAdminStripeSettingsController;
use App\Http\Controllers\SuperAdmin\SuperAdminThemeSettingsController;
use App\Http\Controllers\SuperAdmin\SupportTicketFilesController;
use App\Http\Controllers\SuperAdmin\SupportTicketsController;
use App\Http\Controllers\SuperAdmin\SupportTicketTypesController;
use App\Http\Controllers\SuperAdmin\TestimonialSettingController;
use App\Http\Controllers\SuperAdmin\UpdateDatabaseController;
use App\Http\Controllers\TaskBoardController;
use App\Http\Controllers\TaskCalendarController;
use App\Http\Controllers\TaskCategoryController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskFileController;
use App\Http\Controllers\TaskLabelController;
use App\Http\Controllers\TaskNoteController;
use App\Http\Controllers\TaskReportController;
use App\Http\Controllers\TaxSettingController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketCustomFormController;
use App\Http\Controllers\TicketFileController;
use App\Http\Controllers\TicketReplyController;
use App\Http\Controllers\TimelogCalendarController;
use App\Http\Controllers\TimelogController;
use App\Http\Controllers\TimelogReportController;
use App\Http\Controllers\UserPermissionController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Modules\TravelAgency\Http\Controllers\CurrencyController;
use Modules\TravelAgency\Http\Controllers\TestController;

Route::get('testing', [TestController::class, 'test']);

Route::get('test', function () {
   dd(\Illuminate\Support\Str::slug('Нужно выйти на связь !!!'));
});

Route::post('test-post', [\App\Http\Controllers\MarketingController::class, 'post']);


Route::group(['middleware' => 'auth', 'prefix' => 'account'], function () {

    // Super admin routes
    Route::group(
        ['prefix' => 'super-admin', 'as' => 'super-admin.', 'middleware' => ['super-admin']],
        function () {
            Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('/dashboard/stripe-pop-up-close', [SuperAdminDashboardController::class, 'stripePopUpClose'])->name('dashboard.stripe-pop-up-close');
            Route::post('profile/updateOneSignalId', ['uses' => 'SuperAdminProfileController@updateOneSignalId'])->name('profile.updateOneSignalId');
            Route::resource('/profile', SuperAdminProfileController::class, ['only' => ['index', 'update']]);

            // Faq routes
            Route::post('faq/file-store', [SuperAdminFaqController::class, 'fileStore'])->name('faq.file-store');
            Route::post('faq/file-destroy/{id}', [SuperAdminFaqController::class, 'fileDelete'])->name('faq.file-destroy');
            Route::get('faq/download/{id}', [SuperAdminFaqController::class, 'download'])->name('faq.download');
            Route::get('faq/data', [SuperAdminFaqController::class, 'data'])->name('faq.data');
            Route::resource('/faq', SuperAdminFaqController::class);

            // Faq Category routes
            Route::get('faq-category/data', [SuperAdminFaqCategoryController::class, 'data'])->name('faq-category.data');
            Route::resource('/faq-category', SuperAdminFaqCategoryController::class);

            // Packages routes
            Route::get('packages/data', [SuperAdminPackageController::class, 'data'])->name('packages.data');
            Route::resource('/packages', SuperAdminPackageController::class);

            // Companies routes
            Route::get('companies/data', [SuperAdminCompanyController::class, 'data'])->name('companies.data');
            Route::get('companies/editPackage/{companyId}', [SuperAdminCompanyController::class, 'editPackage'])->name('companies.edit-package.get');
            Route::get('companies/default-language/', [SuperAdminCompanyController::class, 'defaultLanguage'])->name('companies.default-language');
            Route::post('companies/default-language-save/', [SuperAdminCompanyController::class, 'defaultLanguageUpdate'])->name('companies.default-language-save');
            Route::put('companies/editPackage/{companyId}', [SuperAdminCompanyController::class, 'updatePackage'])->name('companies.edit-package.post');
            Route::post('companies/verify-user', [SuperAdminCompanyController::class, 'verifyUser'])->name('companies.verifyUser');
            Route::post('/companies', [SuperAdminCompanyController::class, 'store']);
            Route::post('/companies/{id}/login', [SuperAdminCompanyController::class, 'loginAsCompany'])->name('companies.loginAsCompany');

            Route::resource('/companies', SuperAdminCompanyController::class);

            Route::resource('/invoices', SuperAdminInvoiceController::class)->only(['index']);
            Route::get('super-admin/invoices', [SuperAdminInvoiceController::class, 'data'])->name('invoices.data');
            Route::get('paypal-invoice-download/{id}', [SuperAdminInvoiceController::class, 'paypalInvoiceDownload'])->name('paypal.invoice-download');
            Route::get('billing/invoice-download/{invoice}', [SuperAdminInvoiceController::class, 'download'])->name('stripe.invoice-download');
            Route::get('billing/razorpay-download/{invoice}', [SuperAdminInvoiceController::class, 'razorpayInvoiceDownload'])->name('razorpay.invoice-download');
            Route::get('billing/offline-download/{invoice}', [SuperAdminInvoiceController::class, 'offlineInvoiceDownload'])->name('offline.invoice-download');
            Route::get('billing/paystack-download/{id}', [SuperAdminInvoiceController::class, 'paystackInvoiceDownload'])->name('paystack.invoice-download');
            Route::get('billing/mollie-download/{id}', [SuperAdminInvoiceController::class, 'mollieInvoiceDownload'])->name('mollie.invoice-download');
            Route::get('billing/authorize-download/{id}', [SuperAdminInvoiceController::class, 'authorizeInvoiceDownload'])->name('authorize.invoice-download');
            Route::get('billing/payfast-download/{id}', [SuperAdminInvoiceController::class, 'payfastInvoiceDownload'])->name('payfast.invoice-download');

            // Storage settings
            // Settings routes
            Route::resource('/settings', SuperAdminSettingsController::class)->only(['index', 'update']);


            // Super Admin routes
            Route::get('super-admin/data', [SuperAdminController::class, 'data'])->name('super-admin.data');
            Route::resource('/super-admin', SuperAdminController::class);

            // Offline Plan routes
            Route::get('offline-plan/data', [OfflinePlanChangeController::class, 'data'])->name('offline-plan.data');
            Route::post('offline-plan/verify', [OfflinePlanChangeController::class, 'verify'])->name('offline-plan.verify');
            Route::post('offline-plan/reject', [OfflinePlanChangeController::class, 'reject'])->name('offline-plan.reject');
            Route::resource('/offline-plan', OfflinePlanChangeController::class)->only(['index', 'update']);

            // Support Ticket Types routes
            Route::get('support-ticketTypes/createModal', [SupportTicketTypesController::class, 'createModal'])->name('support-ticketTypes.createModal');
            Route::resource('support-ticketTypes', SupportTicketTypesController::class);

            // Support Ticket routes
            Route::get('support-tickets/export/{startDate?}/{endDate?}/{agentId?}/{status?}/{priority?}/{channelId?}/{typeId?}', [SupportTicketsController::class, 'export'])->name('support-tickets.export');
            Route::get('support-tickets/reply-delete/{id?}', [SupportTicketsController::class, 'destroyReply'])->name('support-tickets.reply-delete');
            Route::post('support-tickets/updateOtherData/{id}', [SupportTicketsController::class, 'updateOtherData'])->name('support-tickets.updateOtherData');
            Route::resource('support-tickets', SupportTicketsController::class);

            // Support Ticket File routes
            Route::get('support-ticket-files/download/{id}', [SupportTicketFilesController::class, 'download'])->name('support-ticket-files.download');
            Route::resource('support-ticket-files', SupportTicketFilesController::class);

            Route::group(
                ['prefix' => 'front-settings'],
                function () {

                    // Front Theme Settings
                    Route::get('front-theme-settings', [SuperAdminFrontSettingController::class, 'themeSetting'])->name('theme-settings');
                    Route::post('front-theme-update', [SuperAdminFrontSettingController::class, 'themeUpdate'])->name('theme-update');
                    Route::get('auth-settings', [SuperAdminFrontSettingController::class, 'authSetting'])->name('auth-settings');
                    Route::post('auth-update', [SuperAdminFrontSettingController::class, 'authUpdate'])->name('auth-update');
                    Route::post('front-settings/update-detail', [SuperAdminFrontSettingController::class, 'updateDetail'])->name('front-settings.updateDetail');
                    Route::get('front-settings/change-form', [SuperAdminFrontSettingController::class, 'changeForm'])->name('front-settings.changeForm');
                    Route::resource('front-settings', SuperAdminFrontSettingController::class)->only(['index', 'update']);

                    // SEO Detail
                    Route::resource('seo-detail', SuperAdminSeoDetailController::class)->only(['edit', 'update', 'index']);

                    // Feature Settings
                    Route::get('feature-settings/change-form', [SuperAdminFeatureSettingController::class, 'changeForm'])->name('feature-settings.changeForm');
                    Route::post('feature-settings/title-update', [SuperAdminFeatureSettingController::class, 'updateTitles'])->name('feature-settings.title-update');
                    Route::resource('feature-settings', SuperAdminFeatureSettingController::class);

                    // Sign Up Settings
                    Route::get('sign-up-setting/change-form', [SuperAdminSignUpController::class, 'changeForm'])->name('sign-up-setting.changeForm');
                    Route::resource('sign-up-setting', SuperAdminSignUpController::class);

                    // Front Feature Settings
                    Route::resource('front-feature-settings', FrontFeatureSettingController::class);

                    // Testimonial Settings
                    Route::get('testimonial-settings/change-form', [TestimonialSettingController::class, 'changeForm'])->name('testimonial-settings.changeForm');
                    Route::post('testimonial-settings/title-update', [TestimonialSettingController::class, 'updateTitles'])->name('testimonial-settings.title-update');
                    Route::resource('testimonial-settings', TestimonialSettingController::class);

                    // Client Settings
                    Route::get('client-settings/change-form', [FrontClientSettingController::class, 'changeForm'])->name('client-settings.changeForm');
                    Route::post('client-settings/title-update', [FrontClientSettingController::class, 'updateTitles'])->name('client-settings.title-update');
                    Route::resource('client-settings', FrontClientSettingController::class);

                    // FAQ Settings
                    Route::get('faq-settings/change-form', [FrontFaqSettingController::class, 'changeForm'])->name('faq-settings.changeForm');
                    Route::post('faq-settings/title-update', [FrontFaqSettingController::class, 'updateTitles'])->name('faq-settings.title-update');
                    Route::resource('faq-settings', FrontFaqSettingController::class);

                    // CTA Settings
                    Route::get('cta-settings/change-form', [CtaSettingController::class, 'changeForm'])->name('cta-settings.changeForm');
                    Route::post('cta-settings/title-update', [CtaSettingController::class, 'updateTitles'])->name('cta-settings.title-update');
                    Route::resource('cta-settings', CtaSettingController::class)->only(['index', 'update']);

                    // Front Menu Settings
                    Route::get('front-menu-settings/change-form', [FrontMenuSettingController::class, 'changeForm'])->name('front-menu-settings.changeForm');
                    Route::post('front-menu-settings/title-update', [FrontMenuSettingController::class, 'updateTitles'])->name('front-menu-settings.title-update');
                    Route::resource('front-menu-settings', FrontMenuSettingController::class)->only(['index', 'update']);

                    // Footer Settings
                    Route::get('footer-settings/change-footer-text-form', [SuperAdminFooterSettingController::class, 'changeFooterTextForm'])->name('footer-settings.changeFooterTextForm');
                    Route::get('footer-settings/footer-text', [SuperAdminFooterSettingController::class, 'footerText'])->name('footer-settings.footer-text');
                    Route::post('footer-settings/copyright-text', [SuperAdminFooterSettingController::class, 'updateText'])->name('footer-settings.copyright-text');
                    Route::post('footer-settings/video-upload', [SuperAdminFooterSettingController::class, 'videoUpload'])->name('footer-settings.video-upload');
                    Route::resource('footer-settings', SuperAdminFooterSettingController::class);

                    // Price Settings
                    Route::get('price-settings/change-price-form', [SuperAdminFrontSettingController::class, 'changePriceForm'])->name('price-settings.changePriceForm');
                    Route::post('price-settings-update', [SuperAdminFrontSettingController::class, 'priceUpdate'])->name('price-setting-update');
                    Route::get('price-settings', [SuperAdminFrontSettingController::class, 'price'])->name('price-settings');

                    // Contact Settings
                    Route::post('contactus-setting-update', [SuperAdminFrontSettingController::class, 'contactUpdate'])->name('contactus-setting-update');
                    Route::get('contact-settings', [SuperAdminFrontSettingController::class, 'contact'])->name('contact-settings');

                    // Front Widgets
                    Route::resource('front-widgets', FrontWidgetsController::class);
                }
            );
            Route::group(
                ['prefix' => 'settings'],
                function () {
                    Route::get('email-settings/sent-test-email', [SuperAdminEmailSettingsController::class, 'sendTestEmail'])->name('email-settings.sendTestEmail');
                    Route::resource('/email-settings', SuperAdminEmailSettingsController::class)->only(['index', 'update']);
                    Route::resource('/security-settings', SuperAdminSecuritySettingsController::class);
                    Route::post('security-settings/show-modal', [SuperAdminSecuritySettingsController::class, 'showModal'])->name('security-settings.show-modal');
                    Route::post('/stripe-method-change', [SuperAdminStripeSettingsController::class, 'changePaymentMethod'])->name('stripe.method-change');
                    Route::get('offline-payment-setting/createModal', [OfflinePaymentSettingController::class, 'createModal'])->name('offline-payment-setting.createModal');
                    Route::get('offline-payment/method', [OfflinePaymentSettingController::class, 'offlinePaymentMethod'])->name('offline-payment-method.create');
                    Route::resource('offline-payment-setting', OfflinePaymentSettingController::class);
                    Route::resource('/payment-settings', SuperAdminStripeSettingsController::class)->only(['index', 'update']);

                    Route::resource('/social-auth-settings', SuperAdminSocialAuthSettingsController::class)->only(['index', 'update']);

                    Route::get('push-notification-settings/sent-test-notification', [SuperAdminPushSettingsController::class, 'sendTestEmail'])->name('push-notification-settings.sendTestEmail');
                    Route::get('push-notification-settings/sendTestNotification', [SuperAdminPushSettingsController::class, 'sendTestNotification'])->name('push-notification-settings.sendTestNotification');
                    Route::resource('/push-notification-settings', SuperAdminPushSettingsController::class)->only(['index', 'update']);

                    Route::get('currency/exchange-key', [SuperAdminCurrencySettingController::class, 'currencyExchangeKey'])->name('currency.exchange-key');
                    Route::post('currency/exchange-key-store', [SuperAdminCurrencySettingController::class, 'currencyExchangeKeyStore'])->name('currency.exchange-key-store');
                    Route::resource('currency', SuperAdminCurrencySettingController::class);
                    Route::get('currency/exchange-rate/{currency}', [SuperAdminCurrencySettingController::class, 'exchangeRate'])->name('currency.exchange-rate');
                    Route::get('currency/update/exchange-rates', [SuperAdminCurrencySettingController::class, 'updateExchangeRate'])->name('currency.update-exchange-rates');

                    Route::post('update-settings/deleteFile', [UpdateDatabaseController::class, 'deleteFile'])->name('update-settings.deleteFile');
                    Route::get('update-settings/install', [UpdateDatabaseController::class, 'install'])->name('update-settings.install');
                    Route::get('update-settings/manual-update', [UpdateDatabaseController::class, 'manual'])->name('update-settings.manual');
                    Route::resource('update-settings', UpdateDatabaseController::class);

                    Route::post('storage-settings-awstest', [StorageSettingsController::class, 'awsTest'])->name('storage-settings.awstest');
                    Route::resource('storage-settings', StorageSettingsController::class);

                    Route::post('language-settings/update-data/{id?}', [SuperAdminLanguageSettingsController::class, 'updateData'])->name('language-settings.update-data');
                    Route::resource('language-settings', SuperAdminLanguageSettingsController::class);

                    Route::resource('package-settings', SuperAdminPackageSettingController::class)->only(['index', 'update']);

                    Route::post('custom-modules/verify-purchase', [CustomModuleController::class, 'verifyingModulePurchase'])->name('custom-modules.verify-purchase');
                    Route::resource('custom-modules', CustomModuleController::class);

                    Route::post('theme-settings/activeTheme', [SuperAdminThemeSettingsController::class, 'activeTheme'])->name('theme-settings.activeTheme');
                    Route::post('theme-settings/rtlTheme', [SuperAdminThemeSettingsController::class, 'rtlTheme'])->name('theme-settings.rtlTheme');
                    Route::resource('theme-settings', SuperAdminThemeSettingsController::class);

                    Route::get('data', [CustomFieldsController::class, 'getFields'])->name('custom-fields.data');
                    Route::resource('custom-fields', CustomFieldsController::class);

                    Route::resource('google-calendar-settings', GoogleCalendarSettingsController::class)->only(['index', 'update']);

                }
            );
        }
    );

    // Admin routes
    Route::group(
        ['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['role:admin']],
        function () {
            Route::group(['middleware' => ['account-setup', 'license-expire']], function () {
                Route::get('/dashboard', 'AdminDashboardController@index')->name('dashboard');
                Route::get('/dashboard/stripe-pop-up-close', 'AdminDashboardController@stripePopUpClose')->name('dashboard.stripe-pop-up-close');
                //                Route::post('/dashboard/widget', 'AdminDashboardController@widget')->name('dashboard.widget');
                Route::get('/client-dashboard', 'AdminDashboardController@clientDashboard')->name('clientDashboard');
                Route::get('/finance-dashboard', 'AdminDashboardController@financeDashboard')->name('financeDashboard');
                Route::get('/finance-dashboard/estimate', 'AdminDashboardController@financeDashboardEstimate')->name('financeDashboardEstimate');
                Route::get('/finance-dashboard/invoice', 'AdminDashboardController@financeDashboardInvoice')->name('financeDashboardInvoice');
                Route::get('/finance-dashboard/expense', 'AdminDashboardController@financeDashboardExpense')->name('financeDashboardExpense');
                Route::get('/finance-dashboard/payment', 'AdminDashboardController@financeDashboardPayment')->name('financeDashboardPayment');
                Route::get('/finance-dashboard/proposal', 'AdminDashboardController@financeDashboardProposal')->name('financeDashboardProposal');
                Route::get('/hr-dashboard', 'AdminDashboardController@hrDashboard')->name('hrDashboard');
                Route::get('/project-dashboard', 'AdminDashboardController@projectDashboard')->name('projectDashboard');
                Route::get('/ticket-dashboard', 'AdminDashboardController@ticketDashboard')->name('ticketDashboard');
                Route::post('/dashboard/widget/{dashboardType}', 'AdminDashboardController@widget')->name('dashboard.widget');


                Route::get('designations/quick-create', ['uses' => 'ManageDesignationController@quickCreate'])->name('designations.quick-create');
                Route::post('designations/quick-store', ['uses' => 'ManageDesignationController@quickStore'])->name('designations.quick-store');
                Route::resource('designations', 'ManageDesignationController');


                // FAQ
                Route::get('faqs/{id}', ['uses' => 'FaqController@details'])->name('faqs.details');
                Route::get('faqs', ['uses' => 'FaqController@index'])->name('faqs.index');

                // Employee Faq routes
                Route::post('employee-faq/file-store', ['uses' => 'AdminEmployeeFaqController@fileStore'])->name('employee-faq.file-store');
                Route::post('employee-faq/file-destroy/{id}', ['uses' => 'AdminEmployeeFaqController@fileDelete'])->name('employee-faq.file-destroy');
                Route::get('employee-faq/download/{id}', ['uses' => 'AdminEmployeeFaqController@download'])->name('employee-faq.download');

                Route::get('employee-faq/data', ['uses' => 'AdminEmployeeFaqController@data'])->name('employee-faq.data');
                Route::resource('/employee-faq', 'AdminEmployeeFaqController');

                // Faq Category routes
                Route::get('employee-faq-category/data', ['uses' => 'AdminEmployeeFaqCategoryController@data'])->name('employee-faq-category.data');

                Route::resource('/employee-faq-category', 'AdminEmployeeFaqCategoryController');


                Route::get('clients/export/{status?}/{client?}', ['uses' => 'ManageClientsController@export'])->name('clients.export');
                Route::get('clients/create/{clientID?}', ['uses' => 'ManageClientsController@create'])->name('clients.create');
                Route::resource('clients', 'ManageClientsController', ['except' => ['create']]);
                Route::post('clients/getSubcategory', ['uses' => 'ManageClientsController@getSubcategory'])->name('clients.getSubcategory');

                Route::get('leads/kanban-board', ['uses' => 'LeadController@kanbanboard'])->name('leads.kanbanboard');
                Route::get('leads/kanban-board', ['uses' => 'LeadController@kanbanboard'])->name('leads.kanbanboard');
                Route::get('leads/gdpr/{leadID}', ['uses' => 'LeadController@gdpr'])->name('leads.gdpr');
                Route::get('leads/export/{followUp?}/{client?}', ['uses' => 'LeadController@export'])->name('leads.export');
                Route::post('leads/change-status', ['uses' => 'LeadController@changeStatus'])->name('leads.change-status');
                Route::get('leads/follow-up/{leadID}', ['uses' => 'LeadController@followUpCreate'])->name('leads.follow-up');
                Route::get('leads/followup/{leadID}', ['uses' => 'LeadController@followUpShow'])->name('leads.followup');
                Route::post('leads/follow-up-store', ['uses' => 'LeadController@followUpStore'])->name('leads.follow-up-store');
                Route::get('leads/follow-up-edit/{id?}', ['uses' => 'LeadController@editFollow'])->name('leads.follow-up-edit');
                Route::post('leads/follow-up-update', ['uses' => 'LeadController@UpdateFollow'])->name('leads.follow-up-update');
                Route::post('leads/follow-up-delete/{id}', ['uses' => 'LeadController@deleteFollow'])->name('leads.follow-up-delete');
                Route::get('leads/follow-up-sort', ['uses' => 'LeadController@followUpSort'])->name('leads.follow-up-sort');
                Route::post('leads/save-consent-purpose-data/{lead}', ['uses' => 'LeadController@saveConsentLeadData'])->name('leads.save-consent-purpose-data');
                Route::get('leads/consent-purpose-data/{lead}', ['uses' => 'LeadController@consentPurposeData'])->name('leads.consent-purpose-data');
                Route::post('leads/updateIndex', ['as' => 'leads.updateIndex', 'uses' => 'LeadController@updateIndex']);
                Route::resource('leads', 'LeadController');

                Route::post('lead-form/sortFields', ['as' => 'lead-form.sortFields', 'uses' => 'LeadCustomFormController@sortFields']);
                Route::resource('lead-form', 'LeadCustomFormController');
                Route::get('leadCategory/create-cat', ['uses' => 'LeadCategoryController@createCat'])->name('leadCategory.create-cat');
                Route::post('leadCategory/store-cat', ['uses' => 'LeadCategoryController@storeCat'])->name('leadCategory.store-cat');
                Route::resource('leadCategory', 'LeadCategoryController');
                Route::resource('events-type', 'EventTypeController');
                Route::resource('events-category', 'EventCategoryController');

                Route::get('clientCategory/create-cat', ['uses' => 'ClientCategoryController@createCat'])->name('clientCategory.create-cat');
                Route::post('clientCategory/store-cat', ['uses' => 'ClientCategoryController@storeCat'])->name('clientCategory.store-cat');
                //subcategory
                Route::resource('clientSubCategory', 'ClientSubCategoryController');
                Route::resource('clientCategory', 'ClientCategoryController');
                // Lead Files
                Route::get('lead-files/download/{id}', ['uses' => 'LeadFilesController@download'])->name('lead-files.download');
                Route::get('lead-files/thumbnail', ['uses' => 'LeadFilesController@thumbnailShow'])->name('lead-files.thumbnail');
                Route::resource('lead-files', 'LeadFilesController');

                // Proposal routes
                Route::get('proposals/data/{id?}', ['uses' => 'ProposalController@data'])->name('proposals.data');
                Route::get('proposals/download/{id}', ['uses' => 'ProposalController@download'])->name('proposals.download');
                Route::get('proposals/create/{leadID?}', ['uses' => 'ProposalController@create'])->name('proposals.create');
                Route::get('proposals/send/{id?}', ['uses' => 'ProposalController@sendProposal'])->name('proposals.send');
                Route::get('proposals/convert-proposal/{id?}', ['uses' => 'ProposalController@convertProposal'])->name('proposals.convert-proposal');

                Route::get('proposals/send/{id?}', ['uses' => 'ProposalController@sendProposal'])->name('proposals.send');
                Route::resource('proposals', 'ProposalController', ['except' => ['create']]);

                // Holidays
                Route::get('holidays/calendar-month', 'HolidaysController@getCalendarMonth')->name('holidays.calendar-month');
                Route::get('holidays/view-holiday/{year?}', 'HolidaysController@viewHoliday')->name('holidays.view-holiday');
                Route::get('holidays/mark_sunday', 'HolidaysController@Sunday')->name('holidays.mark-sunday');
                Route::get('holidays/calendar/{year?}', 'HolidaysController@holidayCalendar')->name('holidays.calendar');
                Route::get('holidays/mark-holiday', 'HolidaysController@markHoliday')->name('holidays.mark-holiday');
                Route::post('holidays/mark-holiday-store', 'HolidaysController@markDayHoliday')->name('holidays.mark-holiday-store');
                Route::resource('holidays', 'HolidaysController');

                Route::get('/impersonate/stop', 'AdminProfileSettingsController@stopImpersonate')->name('impersonate.stop');

                Route::group(
                    ['prefix' => 'employees'],
                    function () {

                        Route::get('employees/free-employees', ['uses' => 'ManageEmployeesController@freeEmployees'])->name('employees.freeEmployees');
                        Route::get('employees/docs-create/{id}', ['uses' => 'ManageEmployeesController@docsCreate'])->name('employees.docs-create');
                        Route::get('employees/tasks/{userId}/{hideCompleted}', ['uses' => 'ManageEmployeesController@tasks'])->name('employees.tasks');
                        Route::get('employees/time-logs/{userId}', ['uses' => 'ManageEmployeesController@timeLogs'])->name('employees.time-logs');
                        Route::get('employees/export/{status?}/{employee?}/{role?}', ['uses' => 'ManageEmployeesController@export'])->name('employees.export');
                        Route::post('employees/assignRole', ['uses' => 'ManageEmployeesController@assignRole'])->name('employees.assignRole');
                        Route::post('employees/assignProjectAdmin', ['uses' => 'ManageEmployeesController@assignProjectAdmin'])->name('employees.assignProjectAdmin');
                        Route::get('employees/leaveTypeEdit/{id}', ['uses' => 'ManageEmployeesController@leaveTypeEdit'])->name('employees.leaveTypeEdit');
                        Route::post('employees/leaveTypeUpdate/{id}', ['uses' => 'ManageEmployeesController@leaveTypeUpdate'])->name('employees.leaveTypeUpdate');
                        Route::resource('employees', 'ManageEmployeesController');

                        Route::get('department/quick-create', ['uses' => 'ManageTeamsController@quickCreate'])->name('teams.quick-create');
                        Route::post('department/quick-store', ['uses' => 'ManageTeamsController@quickStore'])->name('teams.quick-store');
                        Route::resource('teams', 'ManageTeamsController');
                        Route::resource('employee-teams', 'ManageEmployeeTeamsController');

                        Route::get('employee-docs/download/{id}', ['uses' => 'EmployeeDocsController@download'])->name('employee-docs.download');
                        Route::resource('employee-docs', 'EmployeeDocsController');
                    }
                );

                Route::post('projects/gantt-task-update/{id}', ['uses' => 'ManageProjectsController@updateTaskDuration'])->name('projects.gantt-task-update');
                Route::get('projects/ajaxCreate/{columnId?}', ['uses' => 'ManageProjectsController@ajaxCreate'])->name('projects.ajaxCreate');
                Route::get('projects/archive-data', ['uses' => 'ManageProjectsController@archiveData'])->name('projects.archive-data');
                Route::get('projects/archive', ['uses' => 'ManageProjectsController@archive'])->name('projects.archive');
                Route::get('projects/archive-restore/{id?}', ['uses' => 'ManageProjectsController@archiveRestore'])->name('projects.archive-restore');
                Route::get('projects/archive-delete/{id?}', ['uses' => 'ManageProjectsController@archiveDestroy'])->name('projects.archive-delete');
                Route::get('projects/export/{status?}/{clientID?}', ['uses' => 'ManageProjectsController@export'])->name('projects.export');
                Route::get('projects/ganttData/{projectId?}', ['uses' => 'ManageProjectsController@ganttData'])->name('projects.ganttData');
                Route::get('projects/gantt/{projectId?}', ['uses' => 'ManageProjectsController@gantt'])->name('projects.gantt');
                Route::get('projects/burndown/{projectId?}', ['uses' => 'ManageProjectsController@burndownChart'])->name('projects.burndown-chart');
                Route::post('projects/updateStatus/{id}', ['uses' => 'ManageProjectsController@updateStatus'])->name('projects.updateStatus');
                Route::get('projects/discussion-replies/{projectId}/{discussionId}', ['uses' => 'ManageProjectsController@discussionReplies'])->name('projects.discussionReplies');
                Route::get('projects/discussion/{projectId}', ['uses' => 'ManageProjectsController@discussion'])->name('projects.discussion');
                Route::get('projects/template-data/{templateId}', ['uses' => 'ManageProjectsController@templateData'])->name('projects.template-data');
                Route::get('projects/pinned-project', ['uses' => 'ManageProjectsController@pinnedItem'])->name('projects.pinned-project');
                Route::resource('projects', 'ManageProjectsController');

                Route::get('project-template/data', ['uses' => 'ProjectTemplateController@data'])->name('project-template.data');
                Route::get('project-template/detail/{id?}', ['uses' => 'ProjectTemplateController@taskDetail'])->name('project-template.detail');
                Route::resource('project-template', 'ProjectTemplateController');

                Route::post('project-template-members/save-group', ['uses' => 'ProjectMemberTemplateController@storeGroup'])->name('project-template-members.storeGroup');
                Route::resource('project-template-member', 'ProjectMemberTemplateController');

                Route::get('project-template-task/data/{templateId?}', ['uses' => 'ProjectTemplateTaskController@data'])->name('project-template-task.data');
                Route::get('project-template-task/detail/{id?}', ['uses' => 'ProjectTemplateTaskController@taskDetail'])->name('project-template-task.detail');
                Route::resource('project-template-task', 'ProjectTemplateTaskController');

                Route::resource('project-template-sub-task', 'ProjectTemplateSubTaskController');

                Route::post('projectCategory/store-cat', ['uses' => 'ManageProjectCategoryController@storeCat'])->name('projectCategory.store-cat');
                Route::get('projectCategory/create-cat', ['uses' => 'ManageProjectCategoryController@createCat'])->name('projectCategory.create-cat');
                Route::resource('projectCategory', 'ManageProjectCategoryController');

                Route::post('expenseCategory/store-cat', ['uses' => 'ManageExpenseCategoryController@storeCat'])->name('expenseCategory.store-cat');
                Route::get('expenseCategory/create-cat', ['uses' => 'ManageExpenseCategoryController@createCat'])->name('expenseCategory.create-cat');
                Route::resource('expenseCategory', 'ManageExpenseCategoryController');

                Route::post('taskCategory/store-cat', ['uses' => 'ManageTaskCategoryController@storeCat'])->name('taskCategory.store-cat');
                Route::get('taskCategory/create-cat', ['uses' => 'ManageTaskCategoryController@createCat'])->name('taskCategory.create-cat');
                Route::resource('taskCategory', 'ManageTaskCategoryController');

                Route::resource('productCategory', 'ManageProductCategoryController');
                Route::resource('productSubCategory', 'ProductSubCategoryController');

                Route::resource('pinned', 'ManagePinnedController', ['only' => ['store', 'destroy']]);

                Route::post('task-label/store-label', ['uses' => 'ManageTaskLabelController@storeLabel'])->name('task-label.store-label');
                Route::get('task-label/create-label', ['uses' => 'ManageTaskLabelController@createLabel'])->name('task-label.create-label');
                Route::resource('task-label', 'ManageTaskLabelController');

                Route::get('notices/export/{startDate}/{endDate}', ['uses' => 'ManageNoticesController@export'])->name('notices.export');
                Route::resource('notices', 'ManageNoticesController');

                Route::get('settings/change-language', ['uses' => 'OrganisationSettingsController@changeLanguage'])->name('settings.change-language');
                Route::resource('settings', 'OrganisationSettingsController', ['only' => ['edit', 'update', 'index', 'change-language']]);



                Route::group(
                    ['prefix' => 'settings'],
                    function () {
                        Route::get('email-settings/sent-test-email', [SuperAdminEmailSettingsController::class, 'sendTestEmail'])->name('email-settings.sendTestEmail');
                        Route::post('email-settings/updateMailConfig', [SuperAdminEmailSettingsController::class, 'updateMailConfig'])->name('email-settings.updateMailConfig');
                        Route::resource('email-settings', SuperAdminEmailSettingsController::class);

                        Route::get('profile-settings', [AdminProfileSettingsController::class, 'index'])->name('profile-settings.index');
                        Route::post('profile-settings', [AdminProfileSettingsController::class, 'store'])->name('profile-settings.store');

                        Route::get('currency/currency-format', [CurrencySettingController::class, 'currencyFormat'])->name('currency.currency-format');
                        Route::post('currency/update-currency-format', [CurrencySettingController::class, 'updateCurrencyFormat'])->name('currency.update-currency-format');
                        Route::get('currency/exchange-key', [CurrencySettingController::class, 'currencyExchangeKey'])->name('currency.exchange-key');
                        Route::post('currency/exchange-key-store', [CurrencySettingController::class, 'currencyExchangeKeyStore'])->name('currency.exchange-key-store');
                        Route::resource('currency', CurrencySettingController::class);
                        Route::get('currency/exchange-rate/{currency}', [CurrencySettingController::class, 'exchangeRate'])->name('currency.exchange-rate');
                        Route::get('currency/update/exchange-rates', [CurrencySettingController::class, 'updateExchangeRate'])->name('currency.update-exchange-rates');

                        Route::post('theme-settings/activeTheme', [ThemeSettingsController::class, 'activeTheme'])->name('theme-settings.activeTheme');
                        Route::post('theme-settings/roundedTheme', [ThemeSettingsController::class, 'roundedTheme'])->name('theme-settings.roundedTheme');
                        Route::post('theme-settings/rtlTheme', [ThemeSettingsController::class, 'rtlTheme'])->name('theme-settings.rtlTheme');
                        Route::resource('theme-settings', ThemeSettingsController::class);

                        Route::resource('project-settings', ProjectSettingsController::class);

                        Route::resource('log-time-settings', LogTimeSettingsController::class);
                        Route::resource('task-settings', TaskSettingsController::class)->only(['index', 'store']);

                        Route::resource('payment-gateway-credential', PaymentGatewayCredentialController::class);
                        Route::resource('invoice-settings', InvoiceSettingController::class);

                        Route::get('slack-settings/sendTestNotification', [SlackSettingController::class, 'sendTestNotification'])->name('slack-settings.sendTestNotification');
                        Route::post('slack-settings/updateSlackNotification/{id}', [SlackSettingController::class, 'updateSlackNotification'])->name('slack-settings.updateSlackNotification');
                        Route::resource('slack-settings', SlackSettingController::class);

                        Route::get('push-notification-settings/sendTestNotification', [PushNotificationController::class, 'sendTestNotification'])->name('push-notification-settings.sendTestNotification');
                        Route::post('push-notification-settings/updatePushNotification/{id}', [PushNotificationController::class, 'updatePushNotification'])->name('push-notification-settings.updatePushNotification');
                        Route::resource('push-notification-settings', PushNotificationController::class);

                        Route::post('ticket-agents/update-group/{id}', [TicketAgentsController::class, 'updateGroup'])->name('ticket-agents.update-group');
                        Route::resource('ticket-agents', TicketAgentsController::class);
                        Route::resource('ticket-groups', TicketGroupsController::class);

                        Route::get('ticketTypes/createModal', [TicketTypesController::class, 'createModal'])->name('ticketTypes.createModal');
                        Route::resource('ticketTypes', TicketTypesController::class);

                        Route::get('lead-source-settings/createModal', [LeadSourceSettingController::class, 'createModal'])->name('lead-source-settings.createModal');
                        Route::resource('lead-source-settings', LeadSourceSettingController::class);

                        Route::get('lead-status-settings/createModal', [LeadStatusSettingController::class, 'createModal'])->name('leadSetting.createModal');
                        Route::get('lead-status-update/{statusId}', [LeadStatusSettingController::class, 'statusUpdate'])->name('leadSetting.statusUpdate');
                        Route::resource('lead-status-settings', LeadStatusSettingController::class);

                        Route::post('lead-agent-settings/create-agent', [LeadAgentSettingController::class, 'storeAgent'])->name('lead-agent-settings.create-agent');
                        Route::resource('lead-agent-settings', LeadAgentSettingController::class);

                        Route::get('offline-payment-setting/createModal', [OfflinePaymentSettingController::class, 'createModal'])->name('offline-payment-setting.createModal');
                        Route::resource('offline-payment-setting', OfflinePaymentSettingController::class);

                        Route::get('ticketChannels/createModal', [TicketChannelsController::class, 'createModal'])->name('ticketChannels.createModal');
                        Route::resource('ticketChannels', TicketChannelsController::class);

                        Route::post('replyTemplates/fetch-template', [TicketReplyTemplatesController::class, 'fetchTemplate'])->name('replyTemplates.fetchTemplate');
                        Route::resource('replyTemplates', TicketReplyTemplatesController::class);

                        Route::resource('attendance-settings', AttendanceSettingController::class);
                        Route::resource('leaves-settings', LeavesSettingController::class);

                        Route::get('data', [AdminCustomFieldsController::class, 'getFields'])->name('custom-fields.data');
                        Route::resource('custom-fields', AdminCustomFieldsController::class);

                        Route::resource('message-settings', MessageSettingsController::class);
                        Route::resource('module-settings', ModuleSettingsController::class);
                        Route::resource('pusher-settings', PusherSettingsController::class);
                        Route::resource('google-calendar', GoogleSettingController::class)->only(['index']);

                        Route::get('gdpr/lead/approve-reject/{id}/{type}', [GdprSettingsController::class, 'approveRejectLead'])->name('gdpr.lead.approve-reject');
                        Route::get('gdpr/approve-reject/{id}/{type}', [GdprSettingsController::class, 'approveReject'])->name('gdpr.approve-reject');
                        Route::get('gdpr/lead/removal-data', [GdprSettingsController::class, 'removalLeadData'])->name('gdpr.lead.removal-data');
                        Route::get('gdpr/removal-data', [GdprSettingsController::class, 'removalData'])->name('gdpr.removal-data');
                        Route::put('gdpr/update-consent/{id}', [GdprSettingsController::class, 'updateConsent'])->name('gdpr.update-consent');
                        Route::get('gdpr/edit-consent/{id}', [GdprSettingsController::class, 'editConsent'])->name('gdpr.edit-consent');
                        Route::delete('gdpr/purpose-delete/{id}', [GdprSettingsController::class, 'purposeDelete'])->name('gdpr.purpose-delete');
                        Route::get('gdpr/consent-data', [GdprSettingsController::class, 'data'])->name('gdpr.purpose-data');
                        Route::post('gdpr/store-consent', [GdprSettingsController::class, 'storeConsent'])->name('gdpr.store-consent');
                        Route::get('gdpr/add-consent', [GdprSettingsController::class, 'AddConsent'])->name('gdpr.add-consent');
                        Route::get('gdpr/consent', [GdprSettingsController::class, 'consent'])->name('gdpr.consent');
                        Route::get('gdpr/right-of-access', [GdprSettingsController::class, 'rightOfAccess'])->name('gdpr.right-of-access');
                        Route::get('gdpr/right-to-informed', [GdprSettingsController::class, 'rightToInformed'])->name('gdpr.right-to-informed');
                        Route::get('gdpr/right-to-data-portability', [GdprSettingsController::class, 'rightToDataPortability'])->name('gdpr.right-to-data-portability');
                        Route::get('gdpr/right-to-erasure', [GdprSettingsController::class, 'rightToErasure'])->name('gdpr.right-to-erasure');
                        Route::resource('gdpr', GdprSettingsController::class)->only(['index', 'store']);
                    }
                );

                Route::group(
                    ['prefix' => 'projects'],
                    function () {
                        Route::post('project-members/save-group', ['uses' => 'ManageProjectMembersController@storeGroup'])->name('project-members.storeGroup');
                        Route::resource('project-members', 'ManageProjectMembersController');

                        Route::post('tasks/sort', ['uses' => 'ManageTasksController@sort'])->name('tasks.sort');
                        Route::post('tasks/change-status', ['uses' => 'ManageTasksController@changeStatus'])->name('tasks.changeStatus');
                        Route::get('tasks/check-task/{taskID}', ['uses' => 'ManageTasksController@checkTask'])->name('tasks.checkTask');
                        Route::post('tasks/data/{projectId?}', 'ManageTasksController@data')->name('tasks.data');
                        Route::get('tasks/kanban-board/{id}', ['uses' => 'ManageTasksController@kanbanboard'])->name('tasks.kanbanboard');
                        Route::get('tasks/export/{projectId?}', 'ManageTasksController@export')->name('tasks.export');

                        Route::resource('tasks', 'ManageTasksController');

                        Route::post('files/store-link', ['uses' => 'ManageProjectFilesController@storeLink'])->name('files.storeLink');
                        Route::get('files/download/{id}', ['uses' => 'ManageProjectFilesController@download'])->name('files.download');
                        Route::get('files/thumbnail', ['uses' => 'ManageProjectFilesController@thumbnailShow'])->name('files.thumbnail');
                        Route::post('files/multiple-upload', ['uses' => 'ManageProjectFilesController@storeMultiple'])->name('files.multiple-upload');
                        Route::resource('files', 'ManageProjectFilesController');

                        Route::get('invoices/download/{id}', ['uses' => 'ManageInvoicesController@download'])->name('invoices.download');
                        Route::get('invoices/create-invoice/{id}', ['uses' => 'ManageInvoicesController@createInvoice'])->name('invoices.createInvoice');
                        Route::resource('invoices', 'ManageInvoicesController');

                        Route::resource('issues', 'ManageIssuesController');

                        Route::post('time-logs/stop-timer/{id}', ['uses' => 'ManageTimeLogsController@stopTimer'])->name('time-logs.stopTimer');
                        Route::get('time-logs/data/{id}', ['uses' => 'ManageTimeLogsController@data'])->name('time-logs.data');
                        Route::resource('time-logs', 'ManageTimeLogsController');


                        Route::get('milestones/detail/{id}', ['uses' => 'ManageProjectMilestonesController@detail'])->name('milestones.detail');
                        Route::get('milestones/data/{id}', ['uses' => 'ManageProjectMilestonesController@data'])->name('milestones.data');
                        Route::resource('milestones', 'ManageProjectMilestonesController');

                        Route::resource('project-expenses', 'ManageProjectExpensesController');
                        Route::resource('project-payments', 'ManageProjectPaymentsController');

                        Route::resource('project-notes', 'AdminProjectNotesController');
                        Route::get('project-notes/data/{id}', ['uses' => 'AdminProjectNotesController@data'])->name('project-notes.data');
                        Route::get('project-notes/view/{id}', ['uses' => 'AdminProjectNotesController@view'])->name('project-notes.view');

                        Route::resource('project-ratings', 'ManageProjectRatingController');
                    }
                );

                Route::group(
                    ['prefix' => 'clients'],
                    function () {
                        Route::post('save-consent-purpose-data/{client}', ['uses' => 'ManageClientsController@saveConsentLeadData'])->name('clients.save-consent-purpose-data');
                        Route::get('consent-purpose-data/{client}', ['uses' => 'ManageClientsController@consentPurposeData'])->name('clients.consent-purpose-data');
                        Route::get('gdpr/{id}', ['uses' => 'ManageClientsController@gdpr'])->name('clients.gdpr');
                        Route::get('projects/{id}', ['uses' => 'ManageClientsController@showProjects'])->name('clients.projects');
                        Route::get('invoices/{id}', ['uses' => 'ManageClientsController@showInvoices'])->name('clients.invoices');
                        Route::get('payments/{id}', ['uses' => 'ManageClientsController@showPayments'])->name('clients.payments');

                        //  Route::get('notes/{id}', ['uses' => 'ManageClientsController@showNotes'])->name('clients.notes');

                        Route::get('contacts/data/{id}', ['uses' => 'ClientContactController@data'])->name('contacts.data');
                        Route::resource('contacts', 'ClientContactController');

                        Route::get('notes/data/{id}', ['uses' => 'ClientNotesController@data'])->name('notes.data');
                        Route::get('notes/view/{id}', ['uses' => 'ClientNotesController@view'])->name('notes.view');

                        Route::resource('notes', 'ClientNotesController');

                        Route::get('client-docs/download/{id}', ['uses' => 'ClientDocsController@download'])->name('client-docs.download');
                        Route::get('client-docs/quick-create/{id}', ['uses' => 'ClientDocsController@quickCreate'])->name('client-docs.quick-create');
                        Route::resource('client-docs', 'ClientDocsController');
                    }
                );

                Route::get('all-issues/data', ['uses' => 'ManageAllIssuesController@data'])->name('all-issues.data');
                Route::resource('all-issues', 'ManageAllIssuesController');

                Route::get('all-time-logs/members/{projectId}', ['uses' => 'ManageAllTimeLogController@membersList'])->name('all-time-logs.members');
                Route::get('all-time-logs/task-members/{taskId}', ['uses' => 'ManageAllTimeLogController@taskMembersList'])->name('all-time-logs.task-members');
                Route::get('all-time-logs/show-active-timer', ['uses' => 'ManageAllTimeLogController@showActiveTimer'])->name('all-time-logs.show-active-timer');
                Route::get('all-time-logs/export/{startDate?}/{endDate?}/{projectId?}/{employee?}', ['uses' => 'ManageAllTimeLogController@export'])->name('all-time-logs.export');
                Route::post('all-time-logs/stop-timer/{id}', ['uses' => 'ManageAllTimeLogController@stopTimer'])->name('all-time-logs.stopTimer');
                Route::post('all-time-logs/data', ['uses' => 'ManageAllTimeLogController@data'])->name('all-time-logs.data');
                Route::get('all-time-logs/by-employee', ['uses' => 'ManageAllTimeLogController@byEmployee'])->name('all-time-logs.by-employee');
                Route::post('all-time-logs/userTimelogs', ['uses' => 'ManageAllTimeLogController@userTimelogs'])->name('all-time-logs.userTimelogs');
                Route::post('all-time-logs/approve-timelog', ['uses' => 'ManageAllTimeLogController@approveTimelog'])->name('all-time-logs.approve-timelog');
                Route::get('all-time-logs/active-timelogs', ['uses' => 'ManageAllTimeLogController@activeTimelogs'])->name('all-time-logs.active-timelogs');
                Route::get('all-time-logs/calendar', ['uses' => 'ManageAllTimeLogController@calendar'])->name('all-time-logs.calendar');
                Route::resource('all-time-logs', 'ManageAllTimeLogController');

                // task routes
                Route::resource('task', 'ManageAllTasksController', ['only' => ['edit', 'update', 'index']]); // hack to make left admin menu item active
                Route::group(
                    ['prefix' => 'task'],
                    function () {

                        Route::get('all-tasks/export/{startDate?}/{endDate?}/{projectId?}/{hideCompleted?}', ['uses' => 'ManageAllTasksController@export'])->name('all-tasks.export');
                        Route::get('all-tasks/dependent-tasks/{projectId}/{taskId?}', ['uses' => 'ManageAllTasksController@dependentTaskLists'])->name('all-tasks.dependent-tasks');
                        Route::get('all-tasks/members/{projectId}', ['uses' => 'ManageAllTasksController@membersList'])->name('all-tasks.members');
                        Route::get('all-tasks/ajaxCreate/{columnId?}', ['uses' => 'ManageAllTasksController@ajaxCreate'])->name('all-tasks.ajaxCreate');
                        Route::get('all-tasks/reminder/{taskid}', ['uses' => 'ManageAllTasksController@remindForTask'])->name('all-tasks.reminder');
                        Route::get('all-tasks/files/{taskid}', ['uses' => 'ManageAllTasksController@showFiles'])->name('all-tasks.show-files');
                        Route::get('all-tasks/history/{taskid}', ['uses' => 'ManageAllTasksController@history'])->name('all-tasks.history');
                        Route::get('all-tasks/pinned-task', ['uses' => 'ManageAllTasksController@pinnedItem'])->name('all-tasks.pinned-task');
                        Route::resource('all-tasks', 'ManageAllTasksController');
                        //task request
                        Route::resource('task-request', 'AdminTaskRequestController');
                        Route::post('task-request/reject-tasks/{taskId?}', ['uses' => 'AdminTaskRequestController@rejectTask'])->name('task-request.reject-tasks');
                        Route::delete('task-request/delete-file/{id?}', ['uses' => 'AdminTaskRequestController@deleteTaskFile'])->name('task-request.delete-file');
                        Route::get('task-request/download/{id}', ['uses' => 'AdminTaskRequestController@download'])->name('task-request.download');

                        // taskboard resource
                        Route::post('taskboard/getMilestone', ['uses' => 'AdminTaskboardController@getMilestone'])->name('taskboard.getMilestone');
                        Route::post('taskboard/updateIndex', ['as' => 'taskboard.updateIndex', 'uses' => 'AdminTaskboardController@updateIndex']);
                        Route::resource('taskboard', 'AdminTaskboardController');

                        // task calendar routes
                        Route::resource('task-calendar', 'AdminCalendarController');
                        Route::get('task-files/download/{id}', ['uses' => 'TaskFilesController@download'])->name('task-files.download');
                        Route::resource('task-files', 'TaskFilesController');

                        Route::get('sub-task-files/download/{id}', ['uses' => 'SubTaskFilesController@download'])->name('sub-task-files.download');
                        Route::resource('sub-task-files', 'SubTaskFilesController');
                    }
                );

                Route::resource('sticky-note', 'ManageStickyNotesController');


                Route::resource('reports', 'TaskReportController', ['only' => ['edit', 'update', 'index']]); // hack to make left admin menu item active
                Route::group(
                    ['prefix' => 'reports'],
                    function () {
                        Route::post('task-report/data', ['uses' => 'TaskReportController@data'])->name('task-report.data');
                        Route::post('task-report/export', ['uses' => 'TaskReportController@export'])->name('task-report.export');
                        Route::resource('task-report', 'TaskReportController');
                        Route::resource('time-log-report', 'TimeLogReportController');
                        Route::resource('finance-report', 'FinanceReportController');
                        Route::resource('income-expense-report', 'IncomeVsExpenseReportController');
                        //region Leave Report routes
                        Route::post('leave-report/data', ['uses' => 'LeaveReportController@data'])->name('leave-report.data');
                        Route::post('leave-report/export', 'LeaveReportController@export')->name('leave-report.export');
                        Route::get('leave-report/pending-leaves/{id?}', 'LeaveReportController@pendingLeaves')->name('leave-report.pending-leaves');
                        Route::get('leave-report/upcoming-leaves/{id?}', 'LeaveReportController@upcomingLeaves')->name('leave-report.upcoming-leaves');
                        Route::resource('leave-report', 'LeaveReportController');

                        Route::post('attendance-report/report', ['uses' => 'AttendanceReportController@report'])->name('attendance-report.report');
                        Route::get('attendance-report/export/{startDate}/{endDate}/{employee}', ['uses' => 'AttendanceReportController@reportExport'])->name('attendance-report.reportExport');
                        Route::resource('attendance-report', 'AttendanceReportController');
                        //endregion
                    }
                );

                Route::resource('search', 'AdminSearchController');



                Route::resource('finance', 'ManageEstimatesController', ['only' => ['edit', 'update', 'index']]); // hack to make left admin menu item active

                Route::group(
                    ['prefix' => 'finance'],
                    function () {

                        // Estimate routes
                        Route::get('estimates/download/{id}', ['uses' => 'ManageEstimatesController@download'])->name('estimates.download');
                        Route::get('estimates/export/{startDate}/{endDate}/{status}', ['uses' => 'ManageEstimatesController@export'])->name('estimates.export');
                        Route::get('estimates/duplicate-estimate/{id}', ['uses' => 'ManageEstimatesController@duplicateEstimate'])->name('estimates.duplicate-estimate');
                        Route::get('estimates/change-status/{id}', ['uses' => 'ManageEstimatesController@changeStatus'])->name('estimates.change-status');
                        Route::resource('estimates', 'ManageEstimatesController');

                        //Expenses routes
                        Route::post('expenses/change-status', ['uses' => 'ManageExpensesController@changeStatus'])->name('expenses.changeStatus');
                        Route::get('expenses/export/{startDate}/{endDate}/{status}/{employee}', ['uses' => 'ManageExpensesController@export'])->name('expenses.export');
                        Route::post('estimates/send-estimate/{id}', ['uses' => 'ManageEstimatesController@sendEstimate'])->name('estimates.send-estimate');
                        Route::resource('expenses', 'ManageExpensesController');

                        //Expenses recurring
                        Route::post('expenses-recurring/change-status', ['uses' => 'ManageExpensesRecurringController@changeStatus'])->name('expenses-recurring.changeStatus');
                        Route::get('expenses-recurring/export/{startDate}/{endDate}/{status}/{employee}', ['uses' => 'ManageExpensesRecurringController@export'])->name('expenses-recurring.export');
                        Route::get('expenses-recurring/recurring-expenses/{id}', ['uses' => 'ManageExpensesRecurringController@recurringExpenses'])->name('expenses-recurring.recurring-expenses');
                        Route::get('expenses-recurring/download/{id}', ['uses' => 'ManageExpensesRecurringController@download'])->name('expenses-recurring.download');
                        Route::resource('expenses-recurring', 'ManageExpensesRecurringController');

                        // All invoices list routes
                        Route::post('file/store', ['uses' => 'ManageAllInvoicesController@storeFile'])->name('invoiceFile.store');
                        Route::delete('file/destroy', ['uses' => 'ManageAllInvoicesController@destroyFile'])->name('invoiceFile.destroy');
                        Route::get('all-invoices/applied-credits/{id}', ['uses' => 'ManageAllInvoicesController@appliedCredits'])->name('all-invoices.applied-credits');
                        Route::post('all-invoices/delete-applied-credit/{id}', ['uses' => 'ManageAllInvoicesController@deleteAppliedCredit'])->name('all-invoices.delete-applied-credit');
                        Route::get('all-invoices/download/{id}', ['uses' => 'ManageAllInvoicesController@download'])->name('all-invoices.download');
                        Route::get('all-invoices/export/{startDate}/{endDate}/{status}/{projectID}', ['uses' => 'ManageAllInvoicesController@export'])->name('all-invoices.export');
                        Route::get('all-invoices/convert-estimate/{id}', ['uses' => 'ManageAllInvoicesController@convertEstimate'])->name('all-invoices.convert-estimate');
                        Route::get('all-invoices/convert-milestone/{id}', ['uses' => 'ManageAllInvoicesController@convertMilestone'])->name('all-invoices.convert-milestone');
                        Route::get('all-invoices/convert-proposal/{id}', ['uses' => 'ManageAllInvoicesController@convertProposal'])->name('all-invoices.convert-proposal');
                        Route::get('all-invoices/update-item', ['uses' => 'ManageAllInvoicesController@addItems'])->name('all-invoices.update-item');
                        Route::get('all-invoices/payment-detail/{invoiceID}', ['uses' => 'ManageAllInvoicesController@paymentDetail'])->name('all-invoices.payment-detail');
                        Route::get('all-invoices/get-client-company/{projectID?}', ['uses' => 'ManageAllInvoicesController@getClientOrCompanyName'])->name('all-invoices.get-client-company');
                        Route::get('all-invoices/get-client/{projectID}', ['uses' => 'ManageAllInvoicesController@getClient'])->name('all-invoices.get-client');
                        Route::get('all-invoices/check-shipping-address', ['uses' => 'ManageAllInvoicesController@checkShippingAddress'])->name('all-invoices.checkShippingAddress');
                        Route::get('all-invoices/toggle-shipping-address/{invoice}', ['uses' => 'ManageAllInvoicesController@toggleShippingAddress'])->name('all-invoices.toggleShippingAddress');
                        Route::get('all-invoices/shipping-address-modal/{invoice}', ['uses' => 'ManageAllInvoicesController@shippingAddressModal'])->name('all-invoices.shippingAddressModal');
                        Route::post('all-invoices/add-shipping-address/{user}', ['uses' => 'ManageAllInvoicesController@addShippingAddress'])->name('all-invoices.addShippingAddress');
                        Route::get('all-invoices/payment-reminder/{invoiceID}', ['uses' => 'ManageAllInvoicesController@remindForPayment'])->name('all-invoices.payment-reminder');
                        Route::get('all-invoices/payment-verify/{invoiceID}', ['uses' => 'ManageAllInvoicesController@verifyOfflinePayment'])->name('all-invoices.payment-verify');
                        Route::post('all-invoices/payment-verify-submit/{offlinePaymentId}', ['uses' => 'ManageAllInvoicesController@verifyPayment'])->name('offline-invoice-payment.verify');
                        Route::post('all-invoices/payment-reject-submit/{offlinePaymentId}', ['uses' => 'ManageAllInvoicesController@rejectPayment'])->name('offline-invoice-payment.reject');
                        Route::get('all-invoices/update-status/{invoiceID}', ['uses' => 'ManageAllInvoicesController@cancelStatus'])->name('all-invoices.update-status');
                        Route::post('all-invoices/fetchTimelogs', ['uses' => 'ManageAllInvoicesController@fetchTimelogs'])->name('all-invoices.fetchTimelogs');
                        Route::post('all-invoices/send-invoice/{invoiceID}', ['uses' => 'ManageAllInvoicesController@sendInvoice'])->name('all-invoices.send-invoice');

                        Route::resource('all-invoices', 'ManageAllInvoicesController');

                        //Invoice recurring
                        Route::post('invoice-recurring/change-status', ['uses' => 'ManageInvoicesRecurringController@changeStatus'])->name('invoice-recurring.changeStatus');
                        Route::get('invoice-recurring/export/{startDate}/{endDate}/{status}/{employee}', ['uses' => 'ManageInvoicesRecurringController@export'])->name('invoice-recurring.export');
                        Route::get('invoice-recurring/recurring-invoice/{id}', ['uses' => 'ManageInvoicesRecurringController@recurringInvoices'])->name('invoice-recurring.recurring-invoice');
                        Route::resource('invoice-recurring', 'ManageInvoicesRecurringController');

                        // All Credit Note routes
                        Route::post('credit-file/store', ['uses' => 'ManageAllCreditNotesController@storeFile'])->name('creditNoteFile.store');
                        Route::delete('credit-file/destroy', ['uses' => 'ManageAllCreditNotesController@destroyFile'])->name('creditNoteFile.destroy');
                        Route::get('all-credit-notes/apply-to-invoice/{id}', ['uses' => 'ManageAllCreditNotesController@applyToInvoiceModal'])->name('all-credit-notes.apply-to-invoice-modal');
                        Route::post('all-credit-notes/apply-to-invoice/{id}', ['uses' => 'ManageAllCreditNotesController@applyToInvoice'])->name('all-credit-notes.apply-to-invoice');
                        Route::get('all-credit-notes/credited-invoices/{id}', ['uses' => 'ManageAllCreditNotesController@creditedInvoices'])->name('all-credit-notes.credited-invoices');
                        Route::post('all-credit-notes/delete-credited-invoice/{id}', ['uses' => 'ManageAllCreditNotesController@deleteCreditedInvoice'])->name('all-credit-notes.delete-credited-invoice');
                        Route::get('all-credit-notes/download/{id}', ['uses' => 'ManageAllCreditNotesController@download'])->name('all-credit-notes.download');
                        Route::get('all-credit-notes/export/{startDate}/{endDate}/{projectID}', ['uses' => 'ManageAllCreditNotesController@export'])->name('all-credit-notes.export');
                        Route::get('all-credit-notes/convert-invoice/{id}', ['uses' => 'ManageAllCreditNotesController@convertInvoice'])->name('all-credit-notes.convert-invoice');
                        // Route::get('all-credit-notes/convert-proposal/{id}', ['uses' => 'ManageAllCreditNotesController@convertProposal'])->name('all-credit-notes.convert-proposal');
                        Route::get('all-credit-notes/update-item', ['uses' => 'ManageAllCreditNotesController@addItems'])->name('all-credit-notes.update-item');
                        Route::get('all-credit-notes/payment-detail/{creditNoteID}', ['uses' => 'ManageAllCreditNotesController@paymentDetail'])->name('all-credit-notes.payment-detail');
                        Route::resource('all-credit-notes', 'ManageAllCreditNotesController');

                        //Payments routes
                        Route::get('payments/export/{startDate}/{endDate}/{status}/{payment}', ['uses' => 'ManagePaymentsController@export'])->name('payments.export');
                        Route::get('payments/pay-invoice/{invoiceId}', ['uses' => 'ManagePaymentsController@payInvoice'])->name('payments.payInvoice');
                        Route::get('payments/download', ['uses' => 'ManagePaymentsController@downloadSample'])->name('payments.downloadSample');
                        Route::post('payments/import', ['uses' => 'ManagePaymentsController@importExcel'])->name('payments.importExcel');
                        Route::get('payments/getinvoice', ['uses' => 'ManagePaymentsController@invoiceByProject'])->name('payments.getinvoice');
                        Route::resource('payments', 'ManagePaymentsController');
                    }
                );

                //Ticket routes
                Route::get('tickets/export/{startDate?}/{endDate?}/{agentId?}/{status?}/{priority?}/{channelId?}/{typeId?}', ['uses' => 'ManageTicketsController@export'])->name('tickets.export');
                Route::post('tickets/refresh-count', ['uses' => 'ManageTicketsController@refreshCount'])->name('tickets.refreshCount');
                Route::get('tickets/reply-delete/{id?}', ['uses' => 'ManageTicketsController@destroyReply'])->name('tickets.reply-delete');
                Route::post('tickets/updateOtherData/{id}', ['uses' => 'ManageTicketsController@updateOtherData'])->name('tickets.updateOtherData');
                Route::post('tickets/updateStatus', ['uses' => 'ManageTicketsController@updateStatus'])->name('tickets.updateStatus');

                Route::resource('tickets', 'ManageTicketsController');

                Route::post('ticket-form/sortFields', ['as' => 'ticket-form.sortFields', 'uses' => 'TicketCustomFormController@sortFields']);
                Route::resource('ticket-form', 'TicketCustomFormController');

                Route::get('ticket-files/download/{id}', ['uses' => 'TicketFilesController@download'])->name('ticket-files.download');
                Route::resource('ticket-files', 'TicketFilesController');

                //Support Ticket routes
                Route::get('support-tickets/export/{startDate?}/{endDate?}/{agentId?}/{status?}/{priority?}/{channelId?}/{typeId?}', ['uses' => 'SupportTicketsController@export'])->name('support-tickets.export');
                Route::get('support-tickets/reply-delete/{id?}', ['uses' => 'SupportTicketsController@destroyReply'])->name('support-tickets.reply-delete');
                Route::post('support-tickets/updateOtherData/{id}', ['uses' => 'SupportTicketsController@updateOtherData'])->name('support-tickets.updateOtherData');
                Route::resource('support-tickets', 'SupportTicketsController');

                // Support ticket file routes
                Route::get('support-ticket-files/download/{id}', ['uses' => 'SupportTicketFilesController@download'])->name('support-ticket-files.download');
                Route::resource('support-ticket-files', 'SupportTicketFilesController');

                Route::get('user-chat-files/download/{id}', ['uses' => 'UserChatFilesController@download'])->name('user-chat-files.download');
                Route::resource('user-chat-files', 'UserChatFilesController');

                // User message
                Route::post('message-submit', ['as' => 'user-chat.message-submit', 'uses' => 'AdminChatController@postChatMessage']);
                Route::get('user-search', ['as' => 'user-chat.user-search', 'uses' => 'AdminChatController@getUserSearch']);
                Route::resource('user-chat', 'AdminChatController');

                Route::get('user-chat-files/download/{id}', ['uses' => 'AdminChatFilesController@download'])->name('user-chat-files.download');
                Route::resource('user-chat-files', 'AdminChatFilesController');

                // attendance
                Route::get('attendances/export/{startDate?}/{endDate?}/{employee?}', ['uses' => 'ManageAttendanceController@export'])->name('attendances.export');

                Route::get('attendances/bulk', ['uses' => 'ManageAttendanceController@bulkAttendance'])->name('attendances.bulk');
                Route::post('attendances/bulk-store', ['uses' => 'ManageAttendanceController@bulkAttendanceStore'])->name('attendances.bulk-store');
                Route::get('attendances/detail', ['uses' => 'ManageAttendanceController@attendanceDetail'])->name('attendances.detail');
                Route::get('attendances/data', ['uses' => 'ManageAttendanceController@data'])->name('attendances.data');
                Route::get('attendances/check-holiday', ['uses' => 'ManageAttendanceController@checkHoliday'])->name('attendances.check-holiday');
                Route::get('attendances/employeeData/{startDate?}/{endDate?}/{userId?}', ['uses' => 'ManageAttendanceController@employeeData'])->name('attendances.employeeData');
                Route::get('attendances/refresh-count/{startDate?}/{endDate?}/{userId?}', ['uses' => 'ManageAttendanceController@refreshCount'])->name('attendances.refreshCount');
                Route::get('attendances/attendance-by-date', ['uses' => 'ManageAttendanceController@attendanceByDate'])->name('attendances.attendanceByDate');
                Route::get('attendances/byDateData', ['uses' => 'ManageAttendanceController@byDateData'])->name('attendances.byDateData');
                Route::post('attendances/dateAttendanceCount', ['uses' => 'ManageAttendanceController@dateAttendanceCount'])->name('attendances.dateAttendanceCount');
                Route::get('attendances/info/{id}', ['uses' => 'ManageAttendanceController@detail'])->name('attendances.info');
                Route::get('attendances/summary', ['uses' => 'ManageAttendanceController@summary'])->name('attendances.summary');
                Route::post('attendances/summaryData', ['uses' => 'ManageAttendanceController@summaryData'])->name('attendances.summaryData');
                Route::post('attendances/storeMark', ['uses' => 'ManageAttendanceController@storeMark'])->name('attendances.storeMark');
                Route::get('attendances/mark/{id}/{day}/{month}/{year}', ['uses' => 'ManageAttendanceController@mark'])->name('attendances.mark');

                Route::resource('attendances', 'ManageAttendanceController');

                //Event Calendar
                Route::post('events/removeAttendee', ['as' => 'events.removeAttendee', 'uses' => 'AdminEventCalendarController@removeAttendee']);
                Route::get('events/get-filter', 'AdminEventCalendarController@filterEvent')->name('events.get-filter');
                Route::resource('events', 'AdminEventCalendarController');

                // Role permission routes
                Route::post('role-permission/assignAllPermission', ['as' => 'role-permission.assignAllPermission', 'uses' => 'ManageRolePermissionController@assignAllPermission']);
                Route::post('role-permission/removeAllPermission', ['as' => 'role-permission.removeAllPermission', 'uses' => 'ManageRolePermissionController@removeAllPermission']);
                Route::post('role-permission/assignRole', ['as' => 'role-permission.assignRole', 'uses' => 'ManageRolePermissionController@assignRole']);
                Route::post('role-permission/detachRole', ['as' => 'role-permission.detachRole', 'uses' => 'ManageRolePermissionController@detachRole']);
                Route::post('role-permission/storeRole', ['as' => 'role-permission.storeRole', 'uses' => 'ManageRolePermissionController@storeRole']);
                Route::post('role-permission/deleteRole', ['as' => 'role-permission.deleteRole', 'uses' => 'ManageRolePermissionController@deleteRole']);
                Route::get('role-permission/showMembers/{id}', ['as' => 'role-permission.showMembers', 'uses' => 'ManageRolePermissionController@showMembers']);
                Route::resource('role-permission', 'ManageRolePermissionController');

                //Leaves
                Route::post('leaves/leaveAction', ['as' => 'leaves.leaveAction', 'uses' => 'ManageLeavesController@leaveAction']);
                Route::get('leaves/show-reject-modal', ['as' => 'leaves.show-reject-modal', 'uses' => 'ManageLeavesController@rejectModal']);
                Route::post('leave/data/{employeeId?}', ['uses' => 'ManageLeavesController@data'])->name('leave.data');
                Route::get('leave/all-leaves', ['uses' => 'ManageLeavesController@allLeave'])->name('leave.all-leaves');
                Route::get('leaves/pending', ['as' => 'leaves.pending', 'uses' => 'ManageLeavesController@pendingLeaves']);

                Route::resource('leaves', 'ManageLeavesController');

                Route::resource('leaveType', 'ManageLeaveTypesController');

                //sub task routes
                Route::post('sub-task/changeStatus', ['as' => 'sub-task.changeStatus', 'uses' => 'ManageSubTaskController@changeStatus']);
                Route::resource('sub-task', 'ManageSubTaskController');

                //task comments
                Route::post('task-comment/comment-file', ['uses' => 'AdminTaskCommentController@storeCommentFile'])->name('task-comment.comment-file');

                Route::resource('task-comment', 'AdminTaskCommentController');
                Route::get('task-comment/download/{id}', ['uses' => 'AdminTaskCommentController@download'])->name('task-comment.download');

                Route::delete('task-comment/comment-file-delete/{id}', ['uses' => 'AdminTaskCommentController@destroyCommentFile'])->name('task-comment.comment-file-delete');

                //task Note
                Route::resource('task-note', 'AdminNoteController');

                //taxes
                Route::resource('taxes', 'TaxSettingsController');

                //region Products Routes
                Route::get('products/export', ['uses' => 'AdminProductController@export'])->name('products.export');
                Route::post('products/getSubcategory', ['uses' => 'AdminProductController@getSubcategory'])->name('products.getSubcategory');
                Route::resource('products', 'AdminProductController');
                //endregion

                //region contracts routes
                Route::get('contracts/download/{id}', ['as' => 'contracts.download', 'uses' => 'AdminContractController@download']);
                Route::get('contracts/sign/{id}', ['as' => 'contracts.sign-modal', 'uses' => 'AdminContractController@contractSignModal']);
                Route::post('contracts/sign/{id}', ['as' => 'contracts.sign', 'uses' => 'AdminContractController@contractSign']);
                Route::get('contracts/copy/{id}', ['as' => 'contracts.copy', 'uses' => 'AdminContractController@copy']);
                Route::post('contracts/copy-submit', ['as' => 'contracts.copy-submit', 'uses' => 'AdminContractController@copySubmit']);
                Route::post('contracts/send/{id}', ['as' => 'contracts.send', 'uses' => 'AdminContractController@send']);
                // Route::post('contracts/send/{id}', ['uses' => 'AdminContractController@send'])->name('contracts.send');

                Route::post('contracts/add-discussion/{id}', ['as' => 'contracts.add-discussion', 'uses' => 'AdminContractController@addDiscussion']);
                Route::get('contracts/edit-discussion/{id}', ['as' => 'contracts.edit-discussion', 'uses' => 'AdminContractController@editDiscussion']);
                Route::post('contracts/update-discussion/{id}', ['as' => 'contracts.update-discussion', 'uses' => 'AdminContractController@updateDiscussion']);
                Route::post('contracts/remove-discussion/{id}', ['as' => 'contracts.remove-discussion', 'uses' => 'AdminContractController@removeDiscussion']);
                Route::resource('contracts', 'AdminContractController');
                //endregion

                //region contract files routes
                Route::post('contract-files/store-link', ['uses' => 'ContractFilesController@storeLink'])->name('contract-files.storeLink');
                Route::get('contract-files/download/{id}', ['uses' => 'ContractFilesController@download'])->name('contract-files.download');
                Route::get('contract-files/thumbnail', ['uses' => 'ContractFilesController@thumbnailShow'])->name('contract-files.thumbnail');
                Route::post('contract-files/multiple-upload', ['uses' => 'ContractFilesController@storeMultiple'])->name('contract-files.multiple-upload');
                Route::resource('contract-files', 'ContractFilesController');
                //endregion

                //region contracts type routes
                Route::get('contract-type/data', ['as' => 'contract-type.data', 'uses' => 'AdminContractTypeController@data']);
                Route::post('contract-type/type-store', ['as' => 'contract-type.store-contract-type', 'uses' => 'AdminContractTypeController@storeContractType']);
                Route::get('contract-type/type-create', ['as' => 'contract-type.create-contract-type', 'uses' => 'AdminContractTypeController@createContractType']);

                Route::resource('contract-type', 'AdminContractTypeController')->parameters([
                    'contract-type' => 'type'
                ]);
                //endregion

                //region contract renew routes
                Route::get('contract-renew/{id}', ['as' => 'contracts.renew', 'uses' => 'AdminContractRenewController@index']);
                Route::post('contract-renew-submit/{id}', ['as' => 'contracts.renew-submit', 'uses' => 'AdminContractRenewController@renew']);
                Route::post('contract-renew-remove/{id}', ['as' => 'contracts.renew-remove', 'uses' => 'AdminContractRenewController@destroy']);
                //endregion

                //region discussion category routes
                Route::resource('discussion-category', 'DiscussionCategoryController');
                //endregion

                Route::get('discussion-files/download/{id}', ['uses' => 'DiscussionFilesController@download'])->name('discussion-files.download');
                Route::resource('discussion-files', 'DiscussionFilesController');
                //region discussion routes
                Route::post('discussion/setBestAnswer', ['as' => 'discussion.setBestAnswer', 'uses' => 'DiscussionController@setBestAnswer']);
                Route::resource('discussion', 'DiscussionController');
                //endregion

                //region discussion routes
                Route::resource('discussion-reply', 'DiscussionReplyController');
                //endregion

            });
            Route::group(['middleware' => ['account-setup']], function () {
                Route::post('billing/unsubscribe',  'AdminBillingController@cancelSubscription')->name('billing.unsubscribe');
                Route::post('billing/razorpay-payment',  'AdminBillingController@razorpayPayment')->name('billing.razorpay-payment');
                Route::post('billing/razorpay-subscription',  'AdminBillingController@razorpaySubscription')->name('billing.razorpay-subscription');
                Route::get('billing/data',  'AdminBillingController@data')->name('billing.data');
                Route::get('billing/select-package/{packageID}',  'AdminBillingController@selectPackage')->name('billing.select-package');
                Route::get('billing', 'AdminBillingController@index')->name('billing');
                Route::get('billing/packages', 'AdminBillingController@packages')->name('billing.packages');
                Route::post('billing/payment-stripe', 'AdminBillingController@payment')->name('payments.stripe');
                Route::post('billing/payment-authorize', 'AuthorizeController@createSubscription')->name('payments.authorize');
                Route::post('billing/check-authorize-subscription', 'AuthorizeController@checkSubscription')->name('check-authorize-subscription');
                Route::get('billing/invoice-download/{invoice}', 'AdminBillingController@download')->name('stripe.invoice-download');
                Route::get('billing/razorpay-invoice-download/{id}', 'AdminBillingController@razorpayInvoiceDownload')->name('billing.razorpay-invoice-download');
                Route::get('billing/offline-invoice-download/{id}', 'AdminBillingController@offlineInvoiceDownload')->name('billing.offline-invoice-download');
                Route::get('billing/paystack-invoice-download/{id}', 'AdminBillingController@paystackInvoiceDownload')->name('billing.paystack-invoice-download');
                Route::get('billing/mollie-invoice-download/{id}', 'AdminBillingController@mollieInvoiceDownload')->name('billing.mollie-invoice-download');
                Route::get('billing/authorize-invoice-download/{id}', 'AdminBillingController@authorizeInvoiceDownload')->name('billing.authorize-invoice-download');
                Route::get('billing/payfast-invoice-download/{id}', 'AdminBillingController@payfastInvoiceDownload')->name('billing.payfast-invoice-download');

                Route::get('billing/payfast-success', 'AdminBillingController@payFastPaymentSuccess')->name('billing.payfast-success');
                Route::get('billing/payfast-cancel', 'AdminBillingController@payFastPaymentCancel')->name('billing.payfast-cancel');

                //Pay stack payment
                Route::post('/pay', 'PaystackController@redirectToGateway')->name('payments.paystack');
                Route::get('/payment/callback', 'PaystackController@handleGatewayCallback')->name('payments.paystack.callback');

                Route::get('/payfast/cancel', 'AdminPayFastController@payFastPaymentCancel')->name('payfast.cancel');
                Route::get('/payfast/notify', 'AdminPayFastController@payFastPaymentNotify')->name('payfast.notify');

                Route::resource('payfast', 'AdminPayFastController');

                //Pay stack payment
                Route::post('/mollie', 'MollieController@redirectToGateway')->name('payments.mollie');
                Route::get('/mollie/payment/callback', 'MollieController@handleGatewayCallback')->name('payments.mollie.callback');

                Route::get('billing/offline-payment', 'AdminBillingController@offlinePayment')->name('billing.offline-payment');
                Route::post('billing/free-plan', 'AdminBillingController@freePlan')->name('billing.free-plan');
                Route::post('billing/offline-payment-submit', 'AdminBillingController@offlinePaymentSubmit')->name('billing.offline-payment-submit');

                Route::get('paypal-recurring', array('as' => 'paypal-recurring', 'uses' => 'AdminPaypalController@payWithPaypalRecurrring',));
                Route::get('paypal-invoice-download/{id}', array('as' => 'paypal.invoice-download', 'uses' => 'AdminPaypalController@paypalInvoiceDownload',));
                Route::get('paypal-invoice', array('as' => 'paypal-invoice', 'uses' => 'AdminPaypalController@createInvoice'));

                // route for view/blade file
                Route::get('paywithpaypal', array('as' => 'paywithpaypal', 'uses' => 'AdminPaypalController@payWithPaypal'));
                // route for post request
                Route::get('paypal/{packageId}/{type}', array('as' => 'paypal', 'uses' => 'AdminPaypalController@paymentWithpaypal'));
                Route::get('paypal/cancel-agreement', array('as' => 'paypal.cancel-agreement', 'uses' => 'AdminPaypalController@cancelAgreement'));
                // route for check status responce
                Route::get('paypal', array('as' => 'status', 'uses' => 'AdminPaypalController@getPaymentStatus'));
            });
            Route::resource('account-setup', 'ManageAccountSetupController');
            Route::put('account-setup/update-invoice/{id}', ['uses' => 'ManageAccountSetupController@updateInvoice'])->name('account-setup.update-invoice');
        }
    );


    Route::post('image/upload', [ImageController::class, 'store'])->name('image.store');
    Route::resource('sms', SmsController::class);

    Route::post('sms/apply-quick-action', [SmsController::class, 'applyQuickAction'])->name('sms.apply_quick_action');

    Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('account-unverified', [DashboardController::class, 'accountUnverified'])->name('account_unverified');
    Route::get('checklist', [DashboardController::class, 'checklist'])->name('checklist');
    Route::get('dashboard', [DashboardController::class, 'statistics'])->name('dashboard');
    Route::get('custom-dashboard', [DashboardController::class, 'customDashboard'])->name('customDashboard');



    Route::get('kpi', [KpiController::class, 'index'])->name('kpi.index');

    Route::get('dashboard-advanced', [DashboardController::class, 'advancedDashboard'])->name('dashboard.advanced');
    Route::post('dashboard/widget/{dashboardType}', [DashboardController::class, 'widget'])->name('dashboard.widget');
    Route::post('dashboard/week-timelog', [DashboardController::class, 'weekTimelog'])->name('dashboard.week_timelog');

    Route::get('attendances/clock-in-modal', [DashboardController::class, 'clockInModal'])->name('attendances.clock_in_modal');
    Route::post('attendances/store-clock-in', [DashboardController::class, 'storeClockIn'])->name('attendances.store_clock_in');
    Route::get('attendances/update-clock-in', [DashboardController::class, 'updateClockIn'])->name('attendances.update_clock_in');
    Route::get('dashboard/private_calendar', [DashboardController::class, 'privateCalendar'])->name('dashboard.private_calendar');

    Route::get('settings/change-language', [SettingsController::class, 'changeLanguage'])->name('settings.change_language');
    Route::resource('settings', SettingsController::class)->only(['edit', 'update', 'index', 'change_language']);


    Route::post('approve/{id}', [ClientController::class, 'approve'])->name('clients.approve');
    Route::post('save-consent-purpose-data/{client}', [ClientController::class, 'saveConsentLeadData'])->name('clients.save_consent_purpose_data');
    Route::get('clients/gdpr-consent', [ClientController::class, 'consent'])->name('clients.gdpr_consent');
    Route::post('clients/save-client-consent/{lead}', [ClientController::class, 'saveClientConsent'])->name('clients.save_client_consent');
    Route::post('clients/ajax-details/{id}', [ClientController::class, 'ajaxDetails'])->name('clients.ajax_details');
    Route::post('clients/project-list/{id}', [ClientController::class, 'projectList'])->name('clients.project_list');
    Route::post('clients/apply-quick-action', [ClientController::class, 'applyQuickAction'])->name('clients.apply_quick_action');
    Route::get('clients/import', [ClientController::class, 'importClient'])->name('clients.import');
    Route::post('clients/import', [ClientController::class, 'importStore'])->name('clients.import.store');
    Route::post('clients/import/process', [ClientController::class, 'importProcess'])->name('clients.import.process');
    Route::resource('clients', ClientController::class);
    Route::get('clients/{integrationId}/custom-create', [ClientController::class, 'customCreate'])->name('client.custom-create');

    Route::get('clients/finance-count/{id}', [ClientController::class, 'financeCount'])->name('clients.finance_count');

    Route::post('client-contacts/apply-quick-action', [ClientContactController::class, 'applyQuickAction'])->name('client-contacts.apply_quick_action');
    Route::resource('client-contacts', ClientContactController::class);

    Route::get('client-notes/ask-for-password/{id}', [ClientNoteController::class, 'askForPassword'])->name('client_notes.ask_for_password');
    Route::post('client-notes/check-password', [ClientNoteController::class, 'checkPassword'])->name('client_notes.check_password');
    Route::post('client-notes/apply-quick-action', [ClientNoteController::class, 'applyQuickAction'])->name('client-notes.apply_quick_action');
    Route::post('client-notes/showVerified/{id}', [ClientNoteController::class, 'showVerified'])->name('client-notes.show_verified');
    Route::resource('client-notes', ClientNoteController::class);

    Route::get('client-docs/download/{id}', [ClientDocController::class, 'download'])->name('client-docs.download');
    Route::resource('client-docs', ClientDocController::class);

    // client category & subcategory
    Route::resource('clientCategory', ClientCategoryController::class);

    Route::get('getClientSubCategories/{id}', [ClientSubCategoryController::class, 'getSubCategories'])->name('get_client_sub_categories');
    Route::resource('clientSubCategory', ClientSubCategoryController::class);

    // employee routes
    Route::post('employees/apply-quick-action', [EmployeeController::class, 'applyQuickAction'])->name('employees.apply_quick_action');
    Route::post('employees/assignRole', [EmployeeController::class, 'assignRole'])->name('employees.assign_role');
    Route::get('employees/byDepartment/{id}', [EmployeeController::class, 'byDepartment'])->name('employees.by_department');
    Route::get('employees/invite-member', [EmployeeController::class, 'inviteMember'])->name('employees.invite_member');
    Route::get('employees/import', [EmployeeController::class, 'importMember'])->name('employees.import');
    Route::post('employees/import', [EmployeeController::class, 'importStore'])->name('employees.import.store');
    Route::post('employees/import/process', [EmployeeController::class, 'importProcess'])->name('employees.import.process');
    Route::get('import/process/{name}/{id}', [ImportController::class, 'getImportProgress'])->name('import.process.progress');

    Route::get('employees/import/exception/{name}', [ImportController::class, 'getQueueException'])->name('import.process.exception');
    Route::post('employees/send-invite', [EmployeeController::class, 'sendInvite'])->name('employees.send_invite');
    Route::post('employees/create-link', [EmployeeController::class, 'createLink'])->name('employees.create_link');
    Route::resource('employees', EmployeeController::class);
    Route::resource('passport', PassportController::class);
    Route::resource('employee-visa', EmployeeVisaController::class);

    Route::resource('emergency-contacts', EmergencyContactController::class);

    Route::get('employee-docs/download/{id}', [EmployeeDocController::class, 'download'])->name('employee-docs.download');
    Route::resource('employee-docs', EmployeeDocController::class);

    Route::get('employee-leaves/employeeLeaveTypes/{id}', [LeavesQuotaController::class, 'employeeLeaveTypes'])->name('employee-leaves.employee_leave_types');
    Route::resource('employee-leaves', LeavesQuotaController::class);

    Route::get('designations/designation-hierarchy', [DesignationController::class, 'hierarchyData'])->name('designation.hierarchy');
    Route::post('designations/changeParent', [DesignationController::class, 'changeParent'])->name('designation.changeParent');
    Route::post('designations/search-filter', [DesignationController::class, 'searchFilter'])->name('designation.srchFilter');
    Route::post('designations/apply-quick-action', [DesignationController::class, 'applyQuickAction'])->name('designations.apply_quick_action');
    Route::resource('designations', DesignationController::class);

    Route::post('departments/apply-quick-action', [DepartmentController::class, 'applyQuickAction'])->name('departments.apply_quick_action');
    Route::get('departments/department-hierarchy', [DepartmentController::class, 'hierarchyData'])->name('department.hierarchy');
    Route::post('department/changeParent', [DepartmentController::class, 'changeParent'])->name('department.changeParent');
    Route::get('department/search', [DepartmentController::class, 'searchDepartment'])->name('departments.search');
    Route::get('department/{id}', [DepartmentController::class, 'getMembers'])->name('departments.members');
    Route::resource('departments', DepartmentController::class);

    Route::post('user-permissions/customPermissions/{id}', [UserPermissionController::class, 'customPermissions'])->name('user-permissions.custom_permissions');
    Route::post('user-permissions/resetPermissions/{id}', [UserPermissionController::class, 'resetPermissions'])->name('user-permissions.reset_permissions');
    Route::resource('user-permissions', UserPermissionController::class);

    /* PROJECTS */
    Route::resource('projectCategory', ProjectCategoryController::class);
    Route::post('projects/change-status', [ProjectController::class, 'changeProjectStatus'])->name('projects.change_status');

    Route::group(
        ['prefix' => 'projects'],
        function () {

            Route::get('import', [ProjectController::class, 'importProject'])->name('projects.import');
            Route::post('import', [ProjectController::class, 'importStore'])->name('projects.import.store');
            Route::post('import/process', [ProjectController::class, 'importProcess'])->name('projects.import.process');

            Route::post('assignProjectAdmin', [ProjectController::class, 'assignProjectAdmin'])->name('projects.assign_project_admin');
            Route::post('archive-restore/{id}', [ProjectController::class, 'archiveRestore'])->name('projects.archive_restore');
            Route::post('archive-delete/{id}', [ProjectController::class, 'archiveDestroy'])->name('projects.archive_delete');
            Route::get('archive', [ProjectController::class, 'archive'])->name('projects.archive');
            Route::post('apply-quick-action', [ProjectController::class, 'applyQuickAction'])->name('projects.apply_quick_action');
            Route::post('updateStatus/{id}', [ProjectController::class, 'updateStatus'])->name('projects.update_status');
            Route::post('store-pin', [ProjectController::class, 'storePin'])->name('projects.store_pin');
            Route::post('destroy-pin/{id}', [ProjectController::class, 'destroyPin'])->name('projects.destroy_pin');
            Route::post('gantt-data', [ProjectController::class, 'ganttData'])->name('projects.gantt_data');
            Route::post('invoiceList/{id}', [ProjectController::class, 'invoiceList'])->name('projects.invoice_list');
            Route::get('duplicate-project/{id}', [ProjectController::class, 'duplicateProject'])->name('projects.duplicate_project');

            Route::get('members/{id}', [ProjectController::class, 'members'])->name('projects.members');
            Route::get('pendingTasks/{id}', [ProjectController::class, 'pendingTasks'])->name('projects.pendingTasks');
            Route::get('labels/{id}', [TaskLabelController::class, 'labels'])->name('projects.labels');

            Route::post('project-members/save-group', [ProjectMemberController::class, 'storeGroup'])->name('project-members.store_group');
            Route::resource('project-members', ProjectMemberController::class);

            Route::post('files/store-link', [ProjectFileController::class, 'storeLink'])->name('files.store_link');
            Route::get('files/download/{id}', [ProjectFileController::class, 'download'])->name('files.download');
            Route::get('files/thumbnail', [ProjectFileController::class, 'thumbnailShow'])->name('files.thumbnail');
            Route::post('files/multiple-upload', [ProjectFileController::class, 'storeMultiple'])->name('files.multiple_upload');
            Route::resource('files', ProjectFileController::class);

            Route::get('milestones/byProject/{id}', [ProjectMilestoneController::class, 'byProject'])->name('milestones.by_project');
            Route::resource('milestones', ProjectMilestoneController::class);

            // Discussion category routes
            Route::resource('discussion-category', DiscussionCategoryController::class);
            Route::post('discussion/setBestAnswer', [DiscussionController::class, 'setBestAnswer'])->name('discussion.set_best_answer');
            Route::resource('discussion', DiscussionController::class);
            Route::get('discussion-reply/get-replies/{id}', [DiscussionReplyController::class, 'getReplies'])->name('discussion-reply.get_replies');
            Route::resource('discussion-reply', DiscussionReplyController::class);

            // Discussion Files
            Route::get('discussion-files/download/{id}', [DiscussionFilesController::class, 'download'])->name('discussion_file.download');
            Route::resource('discussion-files', DiscussionFilesController::class);

            // Rating routes
            Route::resource('project-ratings', ProjectRatingController::class);

            Route::get('projects/burndown/{projectId?}', [ProjectController::class, 'burndown'])->name('projects.burndown');

            /* PROJECT TEMPLATE */
            Route::post('project-template/apply-quick-action', [ProjectTemplateController::class, 'applyQuickAction'])->name('project_template.apply_quick_action');
            Route::resource('project-template', ProjectTemplateController::class);
            Route::post('project-template-members/save-group', [ProjectTemplateMemberController::class, 'storeGroup'])->name('project_template_members.store_group');
            Route::resource('project-template-member', ProjectTemplateMemberController::class);
            Route::get('project-template-task/data/{templateId?}', [ProjectTemplateTaskController::class, 'data'])->name('project_template_task.data');
            Route::resource('project-template-task', ProjectTemplateTaskController::class);
            Route::resource('project-template-sub-task', ProjectTemplateSubTaskController::class);
            Route::resource('project-calendar', ProjectCalendarController::class);

        }
    );

    Route::get('project-notes/ask-for-password/{id}', [ProjectNoteController::class, 'askForPassword'])->name('project_notes.ask_for_password');
    Route::post('project-notes/check-password', [ProjectNoteController::class, 'checkPassword'])->name('project_notes.check_password');
    Route::post('project-notes/apply-quick-action', [ProjectNoteController::class, 'applyQuickAction'])->name('project_notes.apply_quick_action');
    Route::resource('project-notes', ProjectNoteController::class);
    Route::get('projects-ajax', [ProjectController::class, 'ajaxLoadProject'])->name('get.projects-ajax');
    Route::resource('projects', ProjectController::class);

    Route::resource('integrations', IntegrationController::class);
    Route::get('integrations/create/{clientId}', [IntegrationController::class, 'create'])->name('integrations.create.client');
    Route::get('data/get-currency', [CurrencyController::class, 'getCurrency'])->name('integrations.getCurrency');

    /* PRODUCTS */
    Route::post('products/apply-quick-action', [ProductController::class, 'applyQuickAction'])->name('products.apply_quick_action');
    Route::post('products/remove-cart-item/{id}', [ProductController::class, 'removeCartItem'])->name('products.remove_cart_item');


    Route::post('products/add-cart-item', [ProductController::class, 'addCartItem'])->name('products.add_cart_item');
    Route::get('products/cart', [ProductController::class, 'cart'])->name('products.cart');
    Route::get('products/empty-cart', [ProductController::class, 'emptyCart'])->name('products.empty_cart');

    Route::resource('products', ProductController::class);
    Route::resource('product-settings', ProductSettingController::class);
    Route::resource('productCategory', ProductCategoryController::class);
    Route::get('getProductSubCategories/{id}', [ProductSubCategoryController::class, 'getSubCategories'])->name('get_product_sub_categories');
    Route::resource('productSubCategory', ProductSubCategoryController::class);

    /* PRODUCT FILES */
    Route::get('product-files/download/{id}', [ProductFileController::class, 'download'])->name('product-files.download');
    Route::post('product-files/delete-image/{id}', [ProductFileController::class, 'deleteImage'])->name('product-files.delete_image');
    Route::post('product-files/update-images', [ProductFileController::class, 'updateImages'])->name('product-files.update_images');
    Route::resource('product-files', ProductFileController::class);

    /* INVOICE FILES */
    Route::get('invoice-files/download/{id}', [InvoiceFilesController::class, 'download'])->name('invoice-files.download');
    Route::resource('invoice-files', InvoiceFilesController::class);

    // Tax Settings
    Route::resource('taxes', TaxSettingController::class);

    /* Payments */
    Route::delete('orders/{order}/order-item', [OrderController::class, 'deleteItems'])->name('orders.deleteItems');

    Route::get('orders/offline-payment-modal', [OrderController::class, 'offlinePaymentModal'])->name('orders.offline_payment_modal');
    Route::get('orders/add-item', [OrderController::class, 'addItem'])->name('orders.add_item');
    Route::get('orders/stripe-modal', [OrderController::class, 'stripeModal'])->name('orders.stripe_modal');
    Route::post('orders/make-invoice/{orderId}', [OrderController::class, 'makeInvoice'])->name('orders.make_invoice');
    Route::post('payment/change-status', [PaymentController::class, 'changeStatusIndex'])->name('payment.change_status_index');


    Route::post('orders/payment-failed/{orderId}', [OrderController::class, 'paymentFailed'])->name('orders.payment_failed');
    Route::post('orders/save-stripe-detail/', [OrderController::class, 'saveStripeDetail'])->name('orders.save_stripe_detail');
    Route::post('orders/change-status/', [OrderController::class, 'changeStatus'])->name('orders.change_status');
    /* Payments */
    Route::get('orders/download/{id}', [OrderController::class, 'download'])->name('orders.download');
    Route::post('orders/store-quantity/', [OrderController::class, 'storeQuantity'])->name('orders.store_quantity');

    Route::get('orders/{leadId}/custom-create', [OrderController::class, 'customCreate'])->name('orders.custom-create');


    /* Orders */
    Route::resource('orders', OrderController::class);
    Route::get('orders/custom-edit/{id}', [OrderController::class, 'customEdit'])->name('orders.custom-edit');
    Route::get('orders/custom-update/{id}', [OrderController::class, 'customUpdate'])->name('orders.custom-update');


    /* NOTICE */
    Route::post('notices/apply-quick-action', [NoticeController::class, 'applyQuickAction'])->name('notices.apply_quick_action');
    Route::resource('notices', NoticeController::class);

    /* User Appreciation */
    Route::group(
        ['prefix' => 'appreciations'],
        function () {
            Route::post('awards/apply-quick-action', [AwardController::class, 'applyQuickAction'])->name('awards.apply_quick_action');
            Route::post('awards/change-status/{id?}', [AwardController::class, 'changeStatus'])->name('awards.change-status');
            Route::get('awards/quick-create', [AwardController::class, 'quickCreate'])->name('awards.quick-create');
            Route::post('awards/quick-store', [AwardController::class, 'quickStore'])->name('awards.quick-store');
            Route::resource('awards', AwardController::class);
        });
    Route::post('appreciations/apply-quick-action', [AppreciationController::class, 'applyQuickAction'])->name('appreciations.apply_quick_action');
    Route::resource('appreciations', AppreciationController::class);

    /* KnowledgeBase */
    Route::get('knowledgebase/create/{id?}', [KnowledgeBaseController::class, 'create'])->name('knowledgebase.create');
    Route::post('knowledgebase/apply-quick-action', [KnowledgeBaseController::class, 'applyQuickAction'])->name('knowledgebase.apply_quick_action');
    Route::get('knowledgebase/searchquery/{query?}', [KnowledgeBaseController::class, 'searchQuery'])->name('knowledgebase.searchQuery');
    Route::resource('knowledgebase', KnowledgeBaseController::class)->except(['create']);

    Route::get('knowledgebase-files/download/{id}', [KnowledgeBaseFileController::class, 'download'])->name('knowledgebase-files.download');
    Route::resource('knowledgebase-files', KnowledgeBaseFileController::class);

    /* KnowledgeBase category */
    Route::resource('knowledgebasecategory', KnowledgeBaseCategoryController::class);

    /* EVENTS */
    Route::resource('events', EventCalendarController::class);


    /* Event Files */
    Route::get('event-files/download/{id}', [EventFileController::class, 'download'])->name('event-files.download');
    Route::resource('event-files', EventFileController::class);

    /* TASKS */
    Route::get('tasks/client-detail', [TaskController::class, 'clientDetail'])->name('tasks.clientDetail');
    Route::post('tasks/change-status', [TaskController::class, 'changeStatus'])->name('tasks.change_status');
    Route::post('tasks/apply-quick-action', [TaskController::class, 'applyQuickAction'])->name('tasks.apply_quick_action');
    Route::post('tasks/store-pin', [TaskController::class, 'storePin'])->name('tasks.store_pin');
    Route::post('tasks/reminder', [TaskController::class, 'reminder'])->name('tasks.reminder');
    Route::post('tasks/destroy-pin/{id}', [TaskController::class, 'destroyPin'])->name('tasks.destroy_pin');
    Route::post('tasks/check-task/{taskID}', [TaskController::class, 'checkTask'])->name('tasks.check_task');
    Route::post('tasks/gantt-task-update/{id}', [TaskController::class, 'updateTaskDuration'])->name('tasks.gantt_task_update');
    Route::get('tasks/members/{id}', [TaskController::class, 'members'])->name('tasks.members');
    Route::get('tasks/project_tasks/{id}', [TaskController::class, 'projectTasks'])->name('tasks.project_tasks');
    Route::get('tasks/check-leaves', [TaskController::class, 'checkLeaves'])->name('tasks.checkLeaves');


    Route::group(['prefix' => 'tasks'], function () {

        Route::resource('task-label', TaskLabelController::class);
        Route::resource('taskCategory', TaskCategoryController::class);
        Route::post('taskComment/save-comment-like', [TaskCommentController::class, 'saveCommentLike'])->name('taskComment.save_comment_like');
        Route::resource('taskComment', TaskCommentController::class);
        Route::resource('task-note', TaskNoteController::class);

        // task files routes
        Route::get('task-files/download/{id}', [TaskFileController::class, 'download'])->name('task_files.download');
        Route::resource('task-files', TaskFileController::class);

        // Sub task routes
        Route::post('sub-task/change-status', [SubTaskController::class, 'changeStatus'])->name('sub_tasks.change_status');
        Route::resource('sub-tasks', SubTaskController::class);

        // Task files routes
        Route::get('sub-task-files/download/{id}', [SubTaskFileController::class, 'download'])->name('sub-task-files.download');
        Route::resource('sub-task-files', SubTaskFileController::class);

        // Taskboard routes
        Route::post('taskboards/collapseColumn', [TaskBoardController::class, 'collapseColumn'])->name('taskboards.collapse_column');
        Route::post('taskboards/updateIndex', [TaskBoardController::class, 'updateIndex'])->name('taskboards.update_index');
        Route::get('taskboards/loadMore', [TaskBoardController::class, 'loadMore'])->name('taskboards.load_more');
        Route::resource('taskboards', TaskBoardController::class);

        Route::resource('task-calendar', TaskCalendarController::class);
    });

    Route::resource('tasks', TaskController::class);

    // Holidays
    Route::get('holidays/mark-holiday', [HolidayController::class, 'markHoliday'])->name('holidays.mark_holiday');
    Route::post('holidays/mark-holiday-store', [HolidayController::class, 'markDayHoliday'])->name('holidays.mark_holiday_store');
    Route::get('holidays/table-view', [HolidayController::class, 'tableView'])->name('holidays.table_view');
    Route::post('holidays/apply-quick-action', [HolidayController::class, 'applyQuickAction'])->name('holidays.apply_quick_action');
    Route::resource('holidays', HolidayController::class);

    // Lead Files
    Route::group(['prefix' => 'leads'], function () {
        Route::get('lead-files/download/{id}', [LeadFileController::class, 'download'])->name('lead-files.download');
        Route::get('lead-files/layout', [LeadFileController::class, 'layout'])->name('lead-files.layout');
        Route::resource('lead-files', LeadFileController::class);

        Route::get('leads/follow-up/{leadID}', [LeadController::class, 'followUpCreate'])->name('leads.follow_up');
        Route::post('leads/follow-up-store', [LeadController::class, 'followUpStore'])->name('leads.follow_up_store');
        Route::get('leads/follow-up-edit/{id?}', [LeadController::class, 'editFollow'])->name('leads.follow_up_edit');
        Route::post('leads/follow-up-update', [LeadController::class, 'updateFollow'])->name('leads.follow_up_update');

        Route::post('leads/follow-up-delete/{id}', [LeadController::class, 'deleteFollow'])->name('leads.follow_up_delete');

        Route::post('leads/change-status', [LeadController::class, 'changeStatus'])->name('leads.change_status');
        Route::post('leads/apply-quick-action', [LeadController::class, 'applyQuickAction'])->name('leads.apply_quick_action');

        Route::get('leads/gdpr-consent', [LeadController::class, 'consent'])->name('leads.gdpr_consent');
        Route::post('leads/save-lead-consent/{lead}', [LeadController::class, 'saveLeadConsent'])->name('leads.save_lead_consent');
        Route::post('leads/change-follow-up-status', [LeadController::class, 'changeFollowUpStatus'])->name('leads.change_follow_up_status');

        Route::resource('leadCategory', LeadCategoyController::class);

        // Lead Note
        Route::get('lead-notes/ask-for-password/{id}', [LeadNoteController::class, 'askForPassword'])->name('lead_notes.ask_for_password');
        Route::post('lead-notes/check-password', [LeadNoteController::class, 'checkPassword'])->name('lead_notes.check_password');
        Route::post('lead-notes/apply-quick-action', [LeadNoteController::class, 'applyQuickAction'])->name('lead-notes.apply_quick_action');

        Route::resource('lead-notes', LeadNoteController::class);

        // lead board routes
        Route::post('leadboards/collapseColumn', [LeadBoardController::class, 'collapseColumn'])->name('leadboards.collapse_column');
        Route::post('leadboards/updateIndex', [LeadBoardController::class, 'updateIndex'])->name('leadboards.update_index');
        Route::get('leadboards/loadMore', [LeadBoardController::class, 'loadMore'])->name('leadboards.load_more');
        Route::resource('leadboards', LeadBoardController::class);
        Route::post('leadboards/custom-store', [LeadController::class, 'customStore'])->name('leadboards.custom-create');
        Route::get('leadboards/{clientId}/attach/{integrationId}', [LeadController::class, 'attachIntegration'])->name('leadboards.attach');

        Route::post('lead-form/sortFields', [LeadCustomFormController::class, 'sortFields'])->name('lead-form.sortFields');
        Route::resource('lead-form', LeadCustomFormController::class);
        Route::get('import', [LeadController::class, 'importLead'])->name('leads.import');
        Route::post('import', [LeadController::class, 'importStore'])->name('leads.import.store');
        Route::post('import/process', [LeadController::class, 'importProcess'])->name('leads.import.process');
        // leads route

    });

    Route::resource('leads', LeadController::class);
    Route::put('leads/{leadId}/update-note', [LeadController::class, 'updateNote'])->name('leads.update-note');


    // leaves files routes
    Route::get('leave-files/download/{id}', [LeaveFileController::class, 'download'])->name('leave-files.download');
    Route::resource('leave-files', LeaveFileController::class);

    /* LEAVES */
    Route::get('leaves/leaves-date', [LeaveController::class, 'getDate'])->name('leaves.date');
    Route::get('leaves/personal', [LeaveController::class, 'personalLeaves'])->name('leaves.personal');
    Route::get('leaves/calendar', [LeaveController::class, 'leaveCalendar'])->name('leaves.calendar');
    Route::post('leaves/data', [LeaveController::class, 'data'])->name('leaves.data');
    Route::post('leaves/leaveAction', [LeaveController::class, 'leaveAction'])->name('leaves.leave_action');
    Route::get('leaves/show-reject-modal', [LeaveController::class, 'rejectLeave'])->name('leaves.show_reject_modal');
    Route::get('leaves/show-approved-modal', [LeaveController::class, 'approveLeave'])->name('leaves.show_approved_modal');
    Route::post('leaves/pre-approve-leave', [LeaveController::class, 'preApprove'])->name('leaves.pre_approve_leave');
    Route::post('leaves/apply-quick-action', [LeaveController::class, 'applyQuickAction'])->name('leaves.apply_quick_action');
    Route::get('leaves/view-related-leave/{id}', [LeaveController::class, 'viewRelatedLeave'])->name('leaves.view_related_leave');
    Route::resource('leaves', LeaveController::class);

    // Messages
    Route::get('messages/fetch-user-list', [MessageController::class, 'fetchUserListView'])->name('messages.fetch_user_list');
    Route::post('messages/fetch_messages/{id}', [MessageController::class, 'fetchUserMessages'])->name('messages.fetch_messages');
    Route::post('messages/check_messages', [MessageController::class, 'checkNewMessages'])->name('messages.check_new_message');
    Route::resource('messages', MessageController::class);

    // Chat Files
    Route::get('message-file/download/{id}', [MessageFileController::class, 'download'])->name('message_file.download');
    Route::resource('message-file', MessageFileController::class);

    // Invoices
    Route::get('invoices/offline-method-description', [InvoiceController::class, 'offlineDescription'])->name('invoices.offline_method_description');
    Route::get('invoices/offline-payment-modal', [InvoiceController::class, 'offlinePaymentModal'])->name('invoices.offline_payment_modal');
    Route::get('invoices/stripe-modal', [InvoiceController::class, 'stripeModal'])->name('invoices.stripe_modal');
    Route::post('invoices/save-stripe-detail/', [InvoiceController::class, 'saveStripeDetail'])->name('invoices.save_stripe_detail');
    Route::get('invoices/delete-image', [InvoiceController::class, 'deleteInvoiceItemImage'])->name('invoices.delete_image');
    Route::get('invoices/show-image', [InvoiceController::class, 'showImage'])->name('invoices.show_image');
    Route::post('invoices/store-offline-payment', [InvoiceController::class, 'storeOfflinePayment'])->name('invoices.store_offline_payment');
    Route::post('invoices/store_file', [InvoiceController::class, 'storeFile'])->name('invoices.store_file');
    Route::get('invoices/file-upload', [InvoiceController::class, 'fileUpload'])->name('invoices.file_upload');
    Route::post('invoices/delete-applied-credit/{id}', [InvoiceController::class, 'deleteAppliedCredit'])->name('invoices.delete_applied_credit');
    Route::get('invoices/applied-credits/{id}', [InvoiceController::class, 'appliedCredits'])->name('invoices.applied_credits');
    Route::get('invoices/payment-reminder/{invoiceID}', [InvoiceController::class, 'remindForPayment'])->name('invoices.payment_reminder');
    Route::post('invoices/send-invoice/{invoiceID}', [InvoiceController::class, 'sendInvoice'])->name('invoices.send_invoice');
    Route::post('invoices/apply-quick-action', [InvoiceController::class, 'applyQuickAction'])->name('invoices.apply_quick_action');
    Route::get('invoices/download/{id}', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::get('invoices/add-item', [InvoiceController::class, 'addItem'])->name('invoices.add_item');
    Route::get('invoices/update-status/{invoiceID}', [InvoiceController::class, 'cancelStatus'])->name('invoices.update_status');
    Route::get('invoices/get-client-company/{projectID?}', [InvoiceController::class, 'getClientOrCompanyName'])->name('invoices.get_client_company');
    Route::post('invoices/fetchTimelogs', [InvoiceController::class, 'fetchTimelogs'])->name('invoices.fetch_timelogs');
    Route::get('invoices/check-shipping-address', [InvoiceController::class, 'checkShippingAddress'])->name('invoices.check_shipping_address');
    Route::get('invoices/product-category/{id}', [InvoiceController::class, 'productCategory'])->name('invoices.product_category');

    Route::get('invoices/toggle-shipping-address/{invoice}', [InvoiceController::class, 'toggleShippingAddress'])->name('invoices.toggle_shipping_address');
    Route::get('invoices/shipping-address-modal/{invoice}', [InvoiceController::class, 'shippingAddressModal'])->name('invoices.shipping_address_modal');
    Route::post('invoices/add-shipping-address/{clientId}', [InvoiceController::class, 'addShippingAddress'])->name('invoices.add_shipping_address');
    Route::get('invoices/get-exchange-rate/{id}', [InvoiceController::class, 'getExchangeRate'])->name('invoices.get_exchange_rate');

    Route::group(['prefix' => 'invoices'], function () {
        // Invoice recurring
        Route::post('recurring-invoice/change-status', [RecurringInvoiceController::class, 'changeStatus'])->name('recurring_invoice.change_status');
        Route::get('recurring-invoice/export/{startDate}/{endDate}/{status}/{employee}', [RecurringInvoiceController::class, 'export'])->name('recurring_invoice.export');
        Route::get('recurring-invoice/recurring-invoice/{id}', [RecurringInvoiceController::class, 'recurringInvoices'])->name('recurring_invoice.recurring_invoice');
        Route::resource('recurring-invoices', RecurringInvoiceController::class);
    });
    Route::resource('invoices', InvoiceController::class);

    // Estimates
    Route::get('estimates/delete-image', [EstimateController::class, 'deleteEstimateItemImage'])->name('estimates.delete_image');
    Route::get('estimates/download/{id}', [EstimateController::class, 'download'])->name('estimates.download');
    Route::post('estimates/send-estimate/{id}', [EstimateController::class, 'sendEstimate'])->name('estimates.send_estimate');
    Route::get('estimates/change-status/{id}', [EstimateController::class, 'changeStatus'])->name('estimates.change_status');
    Route::post('estimates/accept/{id}', [EstimateController::class, 'accept'])->name('estimates.accept');
    Route::post('estimates/decline/{id}', [EstimateController::class, 'decline'])->name('estimates.decline');
    Route::get('estimates/add-item', [EstimateController::class, 'addItem'])->name('estimates.add_item');
    Route::resource('estimates', EstimateController::class);

    // Actions
    Route::get('actions/index', [ActionController::class, 'index'])->name('actions.index');
    // Proposals
    Route::get('proposals/delete-image', [ProposalController::class, 'deleteProposalItemImage'])->name('proposals.delete_image');
    Route::get('proposals/download/{id}', [ProposalController::class, 'download'])->name('proposals.download');
    Route::post('proposals/send-proposal/{id}', [ProposalController::class, 'sendProposal'])->name('proposals.send_proposal');
    Route::get('proposals/add-item', [ProposalController::class, 'addItem'])->name('proposals.add_item');
    Route::resource('proposals', ProposalController::class);

    // Proposal Template
    Route::post('proposal-template/apply-quick-action', [ProposalTemplateController::class, 'applyQuickAction'])->name('proposal_template.apply_quick_action');
    Route::get('proposal-template/add-item', [ProposalController::class, 'addItem'])->name('proposal-template.add_item');
    Route::resource('proposal-template', ProposalTemplateController::class);
    Route::get('proposal-template/download/{id}', [ProposalTemplateController::class, 'download'])->name('proposal-template.download');
    Route::get('proposals-template/delete-image', [ProposalTemplateController::class, 'deleteProposalItemImage'])->name('proposal_template.delete_image');

    // Payments
    Route::post('payments/apply-quick-action', [PaymentController::class, 'applyQuickAction'])->name('payments.apply_quick_action');
    Route::get('payments/download/{id}', [PaymentController::class, 'download'])->name('payments.download');
    Route::get('payments/account-list', [PaymentController::class, 'accountList'])->name('payments.account_list');
    Route::get('payments/offline-payments', [PaymentController::class, 'offlineMethods'])->name('offline.methods');
    Route::get('payments/add-bulk-payments', [PaymentController::class, 'addBulkPayments'])->name('payments.add_bulk_payments');
    Route::get('payments/get-currency', [PaymentController::class, 'getCurrency'])->name('payments.get-currency');
    Route::post('payments/save-bulk-payments', [PaymentController::class, 'saveBulkPayments'])->name('payments.save_bulk_payments');

    Route::resource('payments', PaymentController::class);
    Route::get('payments/{orderId}/create-custom', [PaymentController::class, 'customCreate'])->name('payments.custom.create');
    Route::post('payments/store-custom', [PaymentController::class, 'customStore'])->name('payments.custom.store');

    // Credit notes
    Route::post('creditnotes/store_file', [CreditNoteController::class, 'storeFile'])->name('creditnotes.store_file');
    Route::get('creditnotes/file-upload', [CreditNoteController::class, 'fileUpload'])->name('creditnotes.file_upload');
    Route::post('creditnotes/delete-credited-invoice/{id}', [CreditNoteController::class, 'deleteCreditedInvoice'])->name('creditnotes.delete_credited_invoice');
    Route::get('creditnotes/credited-invoices/{id}', [CreditNoteController::class, 'creditedInvoices'])->name('creditnotes.credited_invoices');
    Route::post('creditnotes/apply-invoice-credit/{id}', [CreditNoteController::class, 'applyInvoiceCredit'])->name('creditnotes.apply_invoice_credit');
    Route::get('creditnotes/apply-to-invoice/{id}', [CreditNoteController::class, 'applyToInvoice'])->name('creditnotes.apply_to_invoice');
    Route::get('creditnotes/download/{id}', [CreditNoteController::class, 'download'])->name('creditnotes.download');

    Route::get('creditnotes/convert-invoice/{id}', [CreditNoteController::class, 'convertInvoice'])->name('creditnotes.convert-invoice');

    Route::resource('creditnotes', CreditNoteController::class);

    // Bank account
    Route::post('bankaccount/apply-quick-action', [BankAccountController::class, 'applyQuickAction'])->name('bankaccounts.apply_quick_action');
    Route::post('bankaccount/apply-transaction-quick-action', [BankAccountController::class, 'applyTransactionQuickAction'])->name('bankaccounts.apply_transaction_quick_action');
    Route::get('bankaccount/create-transaction', [BankAccountController::class, 'createTransaction'])->name('bankaccounts.create_transaction');
    Route::post('bankaccount/store-transaction', [BankAccountController::class, 'storeTransaction'])->name('bankaccounts.store_transaction');
    Route::post('bankaccount/change-status', [BankAccountController::class, 'changeStatus'])->name('bankaccounts.change_status');

    Route::get('bankaccount/view-transaction/{id}', [BankAccountController::class, 'viewTransaction'])->name('bankaccounts.view_transaction');
    Route::post('bankaccount/destroy-transaction', [BankAccountController::class, 'destroyTransaction'])->name('bankaccounts.destroy_transaction');
    Route::get('bankaccount/generate-statement/{id}', [BankAccountController::class, 'generateStatement'])->name('bankaccounts.generate_statement');
    Route::get('bankaccount/get-bank-statement', [BankAccountController::class, 'getBankStatement'])->name('bankaccounts.get_bank_statement');

    Route::resource('bankaccounts', BankAccountController::class);

    // Expenses
    Route::group(['prefix' => 'expenses'], function () {
        Route::post('recurring-expenses/change-status', [RecurringExpenseController::class, 'changeStatus'])->name('recurring-expenses.change_status');
        Route::resource('recurring-expenses', RecurringExpenseController::class);
        Route::get('change-status', [ExpenseController::class, 'getEmployeeProjects'])->name('expenses.get_employee_projects');
        Route::get('category', [ExpenseController::class, 'getCategoryEmployee'])->name('expenses.get_category_employees');
        Route::post('change-status', [ExpenseController::class, 'changeStatus'])->name('expenses.change_status');
        Route::post('apply-quick-action', [ExpenseController::class, 'applyQuickAction'])->name('expenses.apply_quick_action');
    });
    Route::resource('expenses', ExpenseController::class);
    Route::resource('expenseCategory', ExpenseCategoryController::class);

    // Timelogs
    Route::group(['prefix' => 'timelogs'], function () {
        Route::resource('timelog-calendar', TimelogCalendarController::class);
        Route::resource('timelog-break', ProjectTimelogBreakController::class);
        Route::get('by-employee', [TimelogController::class, 'byEmployee'])->name('timelogs.by_employee');
        Route::get('export', [TimelogController::class, 'export'])->name('timelogs.export');
        Route::get('show-active-timer', [TimelogController::class, 'showActiveTimer'])->name('timelogs.show_active_timer');
        Route::get('show-timer', [TimelogController::class, 'showTimer'])->name('timelogs.show_timer');
        Route::post('start-timer', [TimelogController::class, 'startTimer'])->name('timelogs.start_timer');
        Route::post('stop-timer', [TimelogController::class, 'stopTimer'])->name('timelogs.stop_timer');
        Route::post('pause-timer', [TimelogController::class, 'pauseTimer'])->name('timelogs.pause_timer');
        Route::post('resume-timer', [TimelogController::class, 'resumeTimer'])->name('timelogs.resume_timer');
        Route::post('apply-quick-action', [TimelogController::class, 'applyQuickAction'])->name('timelogs.apply_quick_action');

        Route::post('employee_data', [TimelogController::class, 'employeeData'])->name('timelogs.employee_data');
        Route::post('user_time_logs', [TimelogController::class, 'userTimelogs'])->name('timelogs.user_time_logs');
        Route::post('approve_timelog', [TimelogController::class, 'approveTimelog'])->name('timelogs.approve_timelog');
    });
    Route::resource('timelogs', TimelogController::class);

    // Contracts
    Route::post('contracts/apply-quick-action', [ContractController::class, 'applyQuickAction'])->name('contracts.apply_quick_action');
    Route::get('contracts/download/{id}', [ContractController::class, 'download'])->name('contracts.download');
    Route::post('contracts/sign/{id}', [ContractController::class, 'sign'])->name('contracts.sign');
    Route::post('companySign/sign/{id}', [ContractController::class, 'companySign'])->name('companySign.sign');
    Route::get('companySignStore/sign/{id}', [ContractController::class, 'companiesSign'])->name('companySignStore.sign');

    Route::group(['prefix' => 'contracts'], function () {
        Route::resource('contractDiscussions', ContractDiscussionController::class);
        Route::get('contractFiles/download/{id}', [ContractFileController::class, 'download'])->name('contractFiles.download');
        Route::resource('contractFiles', ContractFileController::class);
        Route::resource('contractTypes', ContractTypeController::class);
    });

    Route::resource('contracts', ContractController::class);
    Route::resource('contract-renew', ContractRenewController::class);

    // Contract template
    Route::post('contract-template/apply-quick-action', [ContractTemplateController::class, 'applyQuickAction'])->name('contract_template.apply_quick_action');
    Route::resource('contract-template', ContractTemplateController::class);
    Route::get('contract-template/download/{id}', [ContractTemplateController::class, 'download'])->name('contract-template.download');

    // Attendance
    Route::get('attendances/export-attendance/{year}/{month}/{id}', [AttendanceController::class, 'exportAttendanceByMember'])->name('attendances.export_attendance');
    Route::get('attendances/export-all-attendance/{year}/{month}/{id}/{department}/{designation}', [AttendanceController::class, 'exportAllAttendance'])->name('attendances.export_all_attendance');
    Route::post('attendances/employee-data', [AttendanceController::class, 'employeeData'])->name('attendances.employee_data');
    Route::get('attendances/mark/{id}/{day}/{month}/{year}', [AttendanceController::class, 'mark'])->name('attendances.mark');
    Route::get('attendances/by-member', [AttendanceController::class, 'byMember'])->name('attendances.by_member');
    Route::get('attendances/by-hour', [AttendanceController::class, 'byHour'])->name('attendances.by_hour');
    Route::post('attendances/bulk-mark', [AttendanceController::class, 'bulkMark'])->name('attendances.bulk_mark');
    Route::get('attendances/import', [AttendanceController::class, 'importAttendance'])->name('attendances.import');
    Route::post('attendances/import', [AttendanceController::class, 'importStore'])->name('attendances.import.store');
    Route::post('attendances/import/process', [AttendanceController::class, 'importProcess'])->name('attendances.import.process');
    Route::get('attendances/by-map-location', [AttendanceController::class, 'byMapLocation'])->name('attendances.by_map_location');
    Route::resource('attendances', AttendanceController::class);
    Route::get('attendance/{id}/{day}/{month}/{year}', [AttendanceController::class, 'addAttendance'])->name('attendances.add-user-attendance');

    Route::get('shifts/mark/{id}/{day}/{month}/{year}', [EmployeeShiftScheduleController::class, 'mark'])->name('shifts.mark');
    Route::get('shifts/export-all/{year}/{month}/{id}/{department}/{startDate}/{viewType}', [EmployeeShiftScheduleController::class, 'exportAllShift'])->name('shifts.export_all');

    Route::get('shifts/employee-shift-calendar', [EmployeeShiftScheduleController::class, 'employeeShiftCalendar'])->name('shifts.employee_shift_calendar');
    Route::post('shifts/bulk-shift', [EmployeeShiftScheduleController::class, 'bulkShift'])->name('shifts.bulk_shift');

    Route::group(['prefix' => 'shifts'], function () {
        Route::post('shifts-change/approve_request/{id}', [EmployeeShiftChangeRequestController::class, 'approveRequest'])->name('shifts-change.approve_request');
        Route::post('shifts-change/decline_request/{id}', [EmployeeShiftChangeRequestController::class, 'declineRequest'])->name('shifts-change.decline_request');
        Route::post('shifts-change/apply-quick-action', [EmployeeShiftChangeRequestController::class, 'applyQuickAction'])->name('shifts-change.apply_quick_action');
        Route::resource('shifts-change', EmployeeShiftChangeRequestController::class);
    });

    Route::resource('shifts', EmployeeShiftScheduleController::class);

    // Tickets
    Route::post('tickets/apply-quick-action', [TicketController::class, 'applyQuickAction'])->name('tickets.apply_quick_action');
    Route::post('tickets/updateOtherData/{id}', [TicketController::class, 'updateOtherData'])->name('tickets.update_other_data');
    Route::post('tickets/change-status', [TicketController::class, 'changeStatus'])->name('tickets.change-status');
    Route::post('tickets/refreshCount', [TicketController::class, 'refreshCount'])->name('tickets.refresh_count');
    Route::get('tickets/agent-group/{id}', [TicketController::class, 'agentGroup'])->name('tickets.agent_group');
    Route::resource('tickets', TicketController::class);

    // Ticket Custom Embed From
    Route::post('ticket-form/sort-fields', [TicketCustomFormController::class, 'sortFields'])->name('ticket-form.sort_fields');
    Route::resource('ticket-form', TicketCustomFormController::class);

    Route::get('ticket-files/download/{id}', [TicketFileController::class, 'download'])->name('ticket-files.download');
    Route::resource('ticket-files', TicketFileController::class);

    Route::resource('ticket-replies', TicketReplyController::class);

    Route::post('task-report-chart', [TaskReportController::class, 'taskChartData'])->name('task-report.chart');
    Route::resource('task-report', TaskReportController::class);

    Route::post('time-log-report-chart', [TimelogReportController::class, 'timelogChartData'])->name('time-log-report.chart');
    Route::resource('time-log-report', TimelogReportController::class);

    Route::post('finance-report-chart', [FinanceReportController::class, 'financeChartData'])->name('finance-report.chart');
    Route::resource('finance-report', FinanceReportController::class);

    Route::resource('income-expense-report', IncomeVsExpenseReportController::class);

    Route::resource('leave-report', LeaveReportController::class);

    Route::resource('attendance-report', AttendanceReportController::class);

    Route::post('expense-report-chart', [ExpenseReportController::class, 'expenseChartData'])->name('expense-report.chart');
    Route::get('expense-report/expense-category-report', [ExpenseReportController::class, 'expenseCategoryReport'])->name('expense-report.expense_category_report');

    Route::resource('expense-report', ExpenseReportController::class);
    Route::resource('lead-report', LeadReportController::class);
    Route::resource('sales-report', SalesReportController::class);

    Route::resource('sticky-notes', StickyNoteController::class);

    Route::post('show-notifications', [NotificationController::class, 'showNotifications'])->name('show_notifications');


    Route::get('gdpr/lead/approve-reject/{id}/{type}', [GdprSettingsController::class, 'approveRejectLead'])->name('gdpr.lead.approve_reject');
    Route::get('gdpr/customer/approve-reject/{id}/{type}', [GdprSettingsController::class, 'approveRejectClient'])->name('gdpr.customer.approve_reject');

    Route::post('gdpr-settings/apply-quick-action', [GdprSettingsController::class, 'applyQuickAction'])->name('gdpr_settings.apply_quick_action');
    Route::put('gdpr-settings.update-general', [GdprSettingsController::class, 'updateGeneral'])->name('gdpr_settings.update_general');

    Route::post('gdpr/store-consent', [GdprSettingsController::class, 'storeConsent'])->name('gdpr.store_consent');
    Route::get('gdpr/add-consent', [GdprSettingsController::class, 'AddConsent'])->name('gdpr.add_consent');
    Route::get('gdpr/edit-consent/{id}', [GdprSettingsController::class, 'editConsent'])->name('gdpr.edit_consent');

    Route::put('gdpr/update-consent/{id}', [GdprSettingsController::class, 'updateConsent'])->name('gdpr.update_consent');

    Route::delete('gdpr-settings/purpose-delete/{id}', [GdprSettingsController::class, 'purposeDelete'])->name('gdpr_settings.purpose_delete');

    Route::resource('gdpr-settings', GdprSettingsController::class);

    Route::post('gdpr/update-client-consent', [GdprController::class, 'updateClientConsent'])->name('gdpr.update_client_consent');
    Route::get('gdpr/export-data', [GdprController::class, 'downloadJson'])->name('gdpr.export_data');
    Route::resource('gdpr', GdprController::class);

    Route::get('all-notifications', [NotificationController::class, 'all'])->name('all-notifications');
    Route::post('mark-read', [NotificationController::class, 'markRead'])->name('mark_single_notification_read');
    Route::post('mark_notification_read', [NotificationController::class, 'markAllRead'])->name('mark_notification_read');

    Route::resource('search', SearchController::class);

    // Remove in v 5.2.5
    Route::get('hide-webhook-url', [SettingsController::class, 'hideWebhookAlert'])->name('hideWebhookAlert');

    // Estimate Template
    Route::get('estimate-template/add-item', [EstimateTemplateController::class, 'addItem'])->name('estimate-template.add_item');
    Route::resource('estimate-template', EstimateTemplateController::class);
    Route::get('estimates-template/delete-image', [EstimateTemplateController::class, 'deleteEstimateItemImage'])->name('estimate-template.delete_image');
    Route::get('estimate-template/download/{id}', [EstimateTemplateController::class, 'download'])->name('estimate-template.download');

    Route::get('quickbooks/{hash}/callback', [QuickbookController::class, 'callback'])->name('quickbooks.callback');
    Route::get('quickbooks', [QuickbookController::class, 'index'])->name('quickbooks.index');
    Route::get('test', [TemplateController::class, 'editPptxFile'])->name('test');
    Route::put('orders/{orderId}/update', [OrderController::class, 'customUpdate'])->name('custom.orders.update');

    Route::get('orders/{orderId}/custom-edit', [OrderController::class, 'customEdit'])->name('custom.orders.edit');

    Route::resource('partners', PartnersController::class);

    //applications
    Route::resource('applications', ApplicationController::class);
    Route::get('applications/{clientId}/index-lead-application', [ApplicationController::class, 'indexLeadApplication'])->name('applications.indexLeadApplication');
    Route::get('applications/{lead}/add-using-lead', [ApplicationController::class, 'addViaLead'])->name('applications.addViaLead');
    Route::post('applications/delete', [ApplicationController::class, 'deleteApplicationFromJson'])->name('applications.delete');
    Route::post('order-number/store', [ApplicationController::class, 'storeOrderNumber'])->name('order-number.store');
    Route::get('order-numbers/{application}', [ApplicationController::class, 'fetchOrderNumbers'])->name('order-numbers.fetch');
    Route::post('/order-number/delete', [ApplicationController::class, 'deleteOrderNumber'])->name('order-number.delete');
    Route::post('/order-number/update', [ApplicationController::class, 'updateOrderNumber'])->name('order-number.update');




    Route::post('order-item/change-status', [ApplicationController::class, 'changeOrderStatus'])->name('order-item.change_status');

    Route::post('applications/apply-quick-action', [ApplicationController::class, 'applyQuickAction'])->name('applications.apply_quick_action');

    // payments
    Route::get('applications/{application}/{type}/payments/create', [CustomPaymentController::class, 'create'])->name('applications.payments.create');
    Route::post('applications/{application}/payments/store', [CustomPaymentController::class, 'store'])->name('applications.payments.store');
    Route::get('applications/{application}/schemas/{schema}/create', [ApplicationController::class, 'createSchema'])->name('applications.schemas.create');
    Route::post('applications/{application}/schemas/{schema}/book', [ApplicationController::class, 'bookSchema'])->name('applications.book');

    // services & packages
    Route::get('applications/{application}/packages/{package}/add-package', [ApplicationController::class, 'addPackage'])->name('applications.packages.addPackage');
    Route::get('applications/{application}/packages/find-package', [ApplicationController::class, 'findPackage'])->name('applications.packages.findPackage');
    Route::get('package/search', [PackageController::class, 'search'])->name('package.search');

    Route::get('applications/{application}/packages/create', [ApplicationController::class, 'createPackage'])->name('applications.packages.create');
    Route::get('applications/{application}/packages/{orderItem}/edit', [ApplicationController::class, 'editPackage'])->name('applications.packages.edit');

    Route::get('applications/{application}/services/add-service', [ApplicationController::class, 'addService'])->name('applications.services.create');
    Route::get('applications/{application}/packages/find-service', [ApplicationController::class, 'findService'])->name('applications.services.findService');
    Route::get('service/search', [ServicesController::class, 'search'])->name('service.search');
    Route::get('applications/{application}/services/{service}/store', [ApplicationController::class, 'addService'])->name('applications.services.store');


    Route::post('applications/{application}/packages/store', [ApplicationController::class, 'storePackage'])->name('applications.packages.store');
    Route::post('applications/packages/{orderItem}/update', [ApplicationController::class, 'updatePackage'])->name('applications.packages.update');
    // clients
    Route::get('applications/{application}/clients/create', [CustomClientController::class, 'create'])->name('applications.clients.create');
    Route::post('applications/{application}/clients/store', [CustomClientController::class, 'store'])->name('applications.client.store');
    Route::get('applications/{application}/clients/{client}/edit', [CustomClientController::class, 'edit'])->name('applications.clients.edit');
    Route::put('applications/{application}/clients/{client}/update-client', [CustomClientController::class, 'update'])->name('applications.clients.update');

    //travellers
    Route::get('applications/{application}/search-client', [CustomClientController::class, 'searchClientView'])->name('applications.searchClient');
    Route::get('applications/{application}/search-traveller', [CustomClientController::class, 'searchTraveller'])->name('applications.searchTraveller');

    Route::get('applications/{application}/search', [CustomClientController::class, 'searchClient'])->name('applications.search');
    Route::get('applications/{application}/add-user/{user}', [CustomClientController::class, 'addUser'])->name('applications.addUser');
    Route::get('applications/{application}/add-traveller/{user}', [CustomClientController::class, 'addTraveller'])->name('applications.addTraveller');

    Route::delete('applications/{application}/remove-user/{client}', [CustomClientController::class, 'removeUser'])->name('applications.removeUser');
    Route::delete('applications/{application}/remove-traveller/{client}', [CustomClientController::class, 'removeTraveller'])->name('applications.removeTraveller');

    Route::get('applications/{application}/travellers/create', [CustomClientController::class, 'createTraveller'])->name('applications.travellers.create');
    Route::post('applications/{application}/travellers/store', [CustomClientController::class, 'storeTraveller'])->name('applications.travellers.store');

    Route::get('scanner', [PassportScanController::class, 'openScanner'])->name('scanner.read');
    Route::post('scan/passport', [PassportScanController::class, 'scan'])->name('scanner.store');
    Route::post('increase-scan-number', [PassportScanController::class, 'increaseScanNumber'])->name('increase_scan_number');

    Route::resource('debit', 'App\Http\Controllers\DebitController');
    Route::get('debits/{client}/items', [DebitController::class, 'items'])->name('debits.items');

    Route::resource('partner-debits', 'App\Http\Controllers\Applications\PartnerDebitsController');
    Route::get('partner-debits/{partner}/items', [PartnerDebitsController::class, 'items'])->name('partners.items');

    Route::resource('services', ServicesController::class);
    Route::post('services/apply-quick-action', [ServicesController::class, 'applyQuickAction'])->name('services.apply_quick_action');

    Route::resource('schema', LocationSchemaController::class);
    Route::resource('packages', PackageController::class);
    Route::post('packages/apply-quick-action', [PackageController::class, 'applyQuickAction'])->name('packages.apply_quick_action');

    Route::get('payment-deadline/{type}/{application}/create', [DeadlinePaymentController::class, 'create'])
        ->name('payment-deadline.create');
    Route::put('payment-deadline-settings/{application}/update', [DeadlinePaymentController::class, 'update'])
        ->name('payment-deadline.update');

    Route::get('orders/{payment}/invoice-show', [OrderController::class, 'paymentView'])->name('custom.invoice.show');
    Route::get('find-user', [UserController::class, 'findUser'])->name('users.find');

    Route::group(['prefix' => 'marketing'], function () {
        Route::get('lead-stats', [\App\Http\Controllers\MarketingController::class, 'index'])->name('marketing.index');
    });

    // Mark all notifications as readu
    Route::post('show-admin-notifications', ['uses' => 'NotificationController@showAdminNotifications'])->name('show-admin-notifications');
    Route::post('show-user-notifications', ['uses' => 'NotificationController@showUserNotifications'])->name('show-user-notifications');
    Route::post('show-client-notifications', ['uses' => 'NotificationController@showClientNotifications'])->name('show-client-notifications');
    Route::post('mark-notification-read', ['uses' => 'NotificationController@markAllRead'])->name('mark-notification-read');
    Route::get('show-all-member-notifications', ['uses' => 'NotificationController@showAllMemberNotifications'])->name('show-all-member-notifications');
    Route::get('show-all-client-notifications', ['uses' => 'NotificationController@showAllClientNotifications'])->name('show-all-client-notifications');
    Route::get('show-all-admin-notifications', ['uses' => 'NotificationController@showAllAdminNotifications'])->name('show-all-admin-notifications');

    Route::post('show-superadmin-user-notifications', ['uses' => 'SuperAdmin\NotificationController@showUserNotifications'])->name('show-superadmin-user-notifications');
    Route::post('mark-superadmin-notification-read', ['uses' => 'SuperAdmin\NotificationController@markAllRead'])->name('mark-superadmin-notification-read');
    Route::get('show-all-super-admin-notifications', ['uses' => 'SuperAdmin\NotificationController@showAllSuperAdminNotifications'])->name('show-all-super-admin-notifications');
});
