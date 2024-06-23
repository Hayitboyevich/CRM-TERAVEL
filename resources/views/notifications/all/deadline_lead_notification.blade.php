@php

        @endphp

<x-cards.notification :notification="$notification" :link="route('leads.show', $notification->data['id'])"
                      :image="'https://can2-prod.s3.amazonaws.com/uploads/data/000/203/179/original/period-481452_1920.png'"
                      :title="__('email.leadAgent.subject')"
                      :text="$notification->data['name']"
                      :time="$notification->created_at"/>
