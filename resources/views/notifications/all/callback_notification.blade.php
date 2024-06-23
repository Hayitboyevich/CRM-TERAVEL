@php
    $title = 'Вы должны позвонить ' . $notification->data['lead_name'];
@endphp
<x-cards.notification :notification="$notification" :link="route('leads.show', $notification->data['lead_id'] ?? 1)"
                      image="https://avatars.mds.yandex.net/i?id=170cf77ff53c330dfb828197d9099e1c-5634817-images-thumbs&n=13"
                      :title="$title . ' ' . __('app.today')" :time="$notification->created_at"/>

