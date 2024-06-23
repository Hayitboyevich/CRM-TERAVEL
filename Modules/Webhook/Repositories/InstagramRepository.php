<?php

namespace Modules\WebHook\Repositories;

use App\Models\Company;
use App\Models\Event;
use App\Models\Lead;
use App\Models\LeadHelper;
use App\Models\SocialEvent;
use App\Models\User;
use App\Models\WorkFlow;
use Carbon\Carbon;
use Modules\WebHook\Helper\Helper;

class InstagramRepository
{
    private array $array = [
        'message', //messages
        'reaction', //message_reactions
        'pass_thread_control', //messaging_handover
        'postback', //messaging_postbacks
        'referral', //messaging_referral
        'referral', //messaging_referral
        'read', //messaging_seen
    ];

    public function __construct(protected $data){}

    public function create()
    {
        if (array_key_exists('messaging', $this->data->meta)) {
            $method = $this->findMethodName($this->data->meta['messaging'][0]);
            $this->executeWorkflow($method);

        }
        if (array_key_exists('changes', $this->data->meta)) {
            $this->executeWorkflow($this->data->meta['changes'][0]['field']);
        }
    }

    private function executeWorkflow($method)
    {
        $socialEvent = SocialEvent::query()->where('event', $method)->first();
        if ($socialEvent) {
            $workFlow = WorkFlow::query()
                ->where('social_event_id', $socialEvent->id)
                ->where('company_token', $this->data->token)
                ->exists();
            if ($workFlow){
                $this->$method();
            }
        }
    }



    protected function checkText($event, $text)
    {
        $event = SocialEvent::query()->where('event', $event)->first();
        $workflow = WorkFlow::query()
            ->where('company_token', $this->data->token)
            ->where('social_network_id', $event->social->id)
            ->where('social_event_id', $event->id)
            ->first();
        info($workflow->text);

        $verify = $workflow->text;
        $method = $workflow->verify->name;


        return Helper::$method($text, $verify);
    }


    protected function findMethodName($array): string
    {
        $commonElement = null;
        foreach ($array as $key => $value) {
            if (in_array($key, $this->array)) {
                $commonElement = $key;
            }
        }
        return $commonElement;
    }

    private function message()
    {
        $value = $this->data->meta['messaging'][0];
        if ($this->checkText('message', $value['message']['text'])) {
            $leadHelper = new LeadHelper();
            $leadHelper->field = 'message';
            $leadHelper->time = Carbon::now();
            $leadHelper->from_id = $value['sender']['id'];
            $leadHelper->recipient_id = $value['recipient']['id'];
            $leadHelper->message_mid = $value['message']['mid'];
            $leadHelper->company_token = $this->data->token;
            $leadHelper->text = $value['message']['text'];
            $leadHelper->save();
            $this->checkWorkflow('message', $leadHelper);
        }else{
            info("message failed validation text");
        }
    }


    private function comments()
    {
        $value = $this->data->meta['changes'][0]['value'];

        if ($this->checkText('comments', $value['text']))
        {
            $leadHelper = new LeadHelper();
            $leadHelper->field = 'comments';
            $leadHelper->time = Carbon::now();
            $leadHelper->from_id = $value['from']['id'];
            $leadHelper->from_username = $value['from']['username'];
            $leadHelper->media_id = $value['media']['id'];
            $leadHelper->media_product_type = $value['media']['media_product_type'];
            $leadHelper->text_id = $value['id'];
            $leadHelper->company_token = $this->data->token;
            $leadHelper->text_parent_id = $value['parent_id'];
            $leadHelper->text = $value['text'];
            $leadHelper->save();
            $this->checkWorkflow('comments', $leadHelper);

        }else{
            info("comments failed validation text");
        }
    }

    private function live_comments()
    {
        $value = $this->data->meta['changes'][0]['value'];
        if ($this->checkText('live_comments', $value['text'])) {
            $leadHelper = new LeadHelper();
            $leadHelper->field = 'live_comments';
            $leadHelper->time = Carbon::now();
            $leadHelper->from_id = $value['from']['id'];
            $leadHelper->from_username = $value['from']['username'];
            $leadHelper->media_id = $value['media']['id'];
            $leadHelper->media_product_type = $value['media']['media_product_type'];
            $leadHelper->text_id = $value['id'];
            $leadHelper->company_token = $this->data->token;
            $leadHelper->text = $value['text'];
            $leadHelper->save();
        }else{
            info("live_comments failed validation text");
        }

    }

    private function mentions()
    {
        $value = $this->data->meta['changes'][0]['value'];
            $leadHelper = new LeadHelper();
            $leadHelper->field = 'mentions';
            $leadHelper->time = Carbon::now();
            $leadHelper->media_id = $value['media_id'];
            $leadHelper->comment_id = $value['comment_id'];
            $leadHelper->save();
    }

    private function reaction()
    {
        $value = $this->data->meta['messaging'][0];
        $leadHelper = new LeadHelper();
        $leadHelper->field = 'reaction';
        $leadHelper->time = Carbon::now();
        $leadHelper->from_id = $value['sender']['id'];
        $leadHelper->recipient_id = $value['recipient']['id'];
        $leadHelper->message_mid = $value['reaction']['mid'];
        $leadHelper->action = $value['reaction']['action'];
        $leadHelper->reaction = $value['reaction']['reaction'];
        $leadHelper->emoji = $value['reaction']['emoji'];
        $leadHelper->save();
    }



    private function pass_thread_control()
    {
        $value = $this->data->meta['messaging'][0];
        $leadHelper = new LeadHelper();
        $leadHelper->field = 'pass_thread_control';
        $leadHelper->time = Carbon::now();
        $leadHelper->from_id = $value['sender']['id'];
        $leadHelper->recipient_id = $value['recipient']['id'];
        $leadHelper->previous_owner_app_id = $value['pass_thread_control']['previous_owner_app_id'];
        $leadHelper->new_owner_app_id = $value['pass_thread_control']['new_owner_app_id'];
        $leadHelper->metadata = $value['pass_thread_control']['metadata'];
        $leadHelper->save();
    }

    private function postback()
    {
        $value = $this->data->meta['messaging'][0];
        $leadHelper = new LeadHelper();
        $leadHelper->field = 'postback';
        $leadHelper->time = Carbon::now();
        $leadHelper->from_id = $value['sender']['id'];
        $leadHelper->recipient_id = $value['recipient']['id'];
        $leadHelper->message_mid = $value['postback']['mid'];
        $leadHelper->title = $value['postback']['title'];
        $leadHelper->payload = $value['postback']['payload'];
        $leadHelper->save();
    }

    private function referral()
    {
        $value = $this->data->meta['messaging'][0];
        $leadHelper = new LeadHelper();
        $leadHelper->field = 'referral';
        $leadHelper->time = Carbon::now();
        $leadHelper->from_id = $value['sender']['id'];
        $leadHelper->recipient_id = $value['recipient']['id'];
        $leadHelper->ref = $value['referral']['ref'];
        $leadHelper->source = $value['referral']['source'];
        $leadHelper->type = $value['referral']['type'];
        $leadHelper->save();
    }

    private function read()
    {
        $value = $this->data->meta['messaging'][0];
        $leadHelper = new LeadHelper();
        $leadHelper->field = 'read';
        $leadHelper->time = Carbon::now();
        $leadHelper->from_id = $value['sender']['id'];
        $leadHelper->recipient_id = $value['recipient']['id'];
        $leadHelper->message_mid = $value['read']['mid'];
        $leadHelper->save();
    }

    private function standby()
    {
        $value = $this->data->meta['changes'][0]['value'];
        $leadHelper = new LeadHelper();
        $leadHelper->field = 'standby';
        $leadHelper->time = Carbon::now();
        $leadHelper->save();
    }

    private function story_insights()
    {
        $value = $this->data->meta['changes'][0]['value'];
        $leadHelper = new LeadHelper();
        $leadHelper->field = 'story_insights';
        $leadHelper->time = Carbon::now();
        $leadHelper->media_id = $value['media_id'];
        $leadHelper->impressions = $value['impressions'];
        $leadHelper->reach = $value['reach'];
        $leadHelper->taps_forward = $value['taps_forward'];
        $leadHelper->taps_back = $value['taps_back'];
        $leadHelper->exits = $value['exits'];
        $leadHelper->replies = $value['replies'];
        $leadHelper->save();
    }

    public function checkWorkflow($event, $leadHelper)
    {
        $event = SocialEvent::where('event', $event)->first();

        if (!empty($event))
        {
            $company = Company::where('hash', $this->data->token)->first();
            if ($leadHelper->field == 'message')
            {
                $comment = LeadHelper::where('field', 'comments')->where('company_token', $this->data->token)->where('from_id', $leadHelper->from_id)->latest()->first();
                if (!empty($comment)){
                    $user  = User::where('mobile', $leadHelper->text)->first();
                    if (!$user){
                        $user = new User();
                        $user->company_id = $company->id;
                        $user->mobile = $leadHelper->text;
                        $user->name = $comment->from_username;
                        $user->save();
                    }
                    $this->createLead($user);
                }
            }

            if ($leadHelper->field == 'comments')
            {
                $message = LeadHelper::where('field', 'message')->where('company_token', $this->data->token)->where('from_id', $leadHelper->from_id)->latest()->first();
                if (!empty($message)){
                    $user  = User::where('mobile', $message->text)->first();
                    if (!$user) {
                        $user = new User();
                        $user->company_id = $company->id;
                        $user->mobile = $message->text;
                        $user->name = $leadHelper->from_username;
                        $user->save();
                    }
                    $this->createLead($user);
                }
            }
        }
    }

    private function createLead($user)
    {
        $lead = new Lead();
        $lead->company_id = $user->company_id;
        $lead->status_id = 17;
        $lead->client_id = $user->id;
        $lead->mobile = $user->mobile;
        $lead->client_name = $user->name;
        $lead->hash = $this->data->token;
        return $lead->save();
    }
}
