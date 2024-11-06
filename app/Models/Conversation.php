<?php

namespace App\Models;
use App\Models\RestModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;
    protected $table = "chat_messages";  
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = [
        'conversation_id',
        'creater_id',
        'created_by',
        'message',
        'attachment_id',
        'created_date',
        'parent_message_id',
        'is_active',
        'is_deleted',
        'deleted_by'
    ]; 

    public function view_all_conversation($request)
    {
        $query = self::select(
            'chat_messages.id',
            'chat_messages.message',
            'conversation.conversation_start_time',
            'agent.name as agent_name',
            'contacts.contact_name',
            'message_recipient.message_id',
            'agent.ref_type'
        )
        ->join('conversation', 'conversation.id', '=', 'chat_messages.conversation_id')
        ->join('agent', 'chat_messages.creater_id', '=', 'agent.agent_id')
        ->join('message_recipient', 'message_recipient.message_id', '=', 'chat_messages.id')
        ->join('contacts', 'contacts.contact_id', '=', 'message_recipient.recipient_id')
        ->where('chat_messages.is_active', 1)
        ->where('chat_messages.parent_message_id', 0)
        ->where('message_recipient.recipient_group_id', 0);

        // Filter by user_id if provided
        if ($request->has('user_id')) {
            $query->where('conversation.user_id', $request->user_id);
        }

        // Retrieve distinct conversations
        $conversation = $query->distinct()->get();
        
        return $conversation;
    }
}
