<!-- ROW START -->
<div class="row mt-4">
    <div class="col-xl-7 col-lg-12 col-md-12 mb-4 mb-xl-0 mb-lg-4">
        <x-cards.data :title="__('modules.client.profileInfo')">
            <x-cards.data-row label="Сообщение" :value="mb_ucwords($sms->message)"/>

            <x-cards.data-row label="Клиенты" :value="mb_ucwords($text)"/>
            <x-cards.data-row label="Дата отправки" :value="mb_ucwords($sms->delivery_date)"/>


        </x-cards.data>
    </div>
</div>
<!-- ROW END -->
