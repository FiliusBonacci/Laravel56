<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MessagesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chat_with($with_user_id)
    {
        $user = User::find($with_user_id);

        // dd($user->id);

        // find conversation Id

        $conversationId = DB::table('conversations')
                            ->where('user_one', Auth::id())
                            ->where('user_two', $user->id)

                            ->orWhere('user_two', Auth::id())
                            ->where('user_one', $user->id)

                            ->first()->id;
        // dd($conversationId);
        //select * from conversations where (user_one=2 and user_two=1) or (user_one=1 and user_two=2)
        return view('pages.chat_with_user', compact('user','conversationId'));
    }
    
    
    
    
    public function getUsersListMessenger()
    {
        $allUsers1 = DB::table('users')
        ->Join('conversations','users.id','conversations.user_one')
        ->where('conversations.user_two', Auth::user()->id)->get();
        //return $allUsers1;
        $allUsers2 = DB::table('users')
        ->Join('conversations','users.id','conversations.user_two')
        ->where('conversations.user_one', Auth::user()->id)->get();
        
        return array_merge($allUsers1->toArray(), $allUsers2->toArray());
    }
    
    
    public function getMessagesForConvId($id)
    {
        $userMsg = 
                    DB::table('users')
                ->join('messages', 'users.id','messages.from_user')
                ->where('messages.conversation_id', $id)->get();
        // $userMsg = DB::table('messages')
        //         ->join('users', 'users.id','messages.from_user')
        //         ->where('messages.conversation_id', $id)->get();
        return $userMsg;
    }
    
    
    
    
    //-------------------------------------------------------------------------
    public function sendMessage($conversationId, Request $request){
        
        $conID = $request->conID;
        $msg = $request->message;
        echo $msg;
        echo $conversationId;
        
        $conversation = DB::table('conversations')->where('id', $conversationId)->first();
        // $checkUserId = DB::table('messages')->where('conversation_id', $conversationId)->first();
        
        
        // fetch user_to
        if($conversation->user_one == Auth::id()){
            $userTo = $conversation->user_two;
        }
        else {
            $userTo = $checkUserId->user_one;
        }
        
        // now send message
        $sendM = DB::table('messages')->insert([
            'from_user' => Auth::id(),
            'to_user' => $userTo,
            'body' => $msg,
            'conversation_id' => $conversationId,
            'status' => 0,
            'created_at' => Carbon::now(),
            ]);
            // dd($sendM);
            
            if($sendM){
                $userMsg = DB::table('messages')
                ->join('users', 'users.id','messages.from_user')
                ->where('messages.conversation_id', $conversationId)->get();
                return $userMsg;
            }
            
            
        }
        
        
        public function newMessage(){
            $uid = Auth::user()->id;
            $friends1 = DB::table('friendships')
            ->leftJoin('users', 'users.id', 'friendships.user_requested') // who is not loggedin but send request to
            ->where('status', 1)
            ->where('requester', $uid) // who is loggedin
            ->get();
            $friends2 = DB::table('friendships')
            ->leftJoin('users', 'users.id', 'friendships.requester')
            ->where('status', 1)
            ->where('user_requested', $uid)
            ->get();
            $friends = array_merge($friends1->toArray(), $friends2->toArray());
            return view('newMessage', compact('friends', $friends));
        }
        public function sendNewMessage(Request $request){
            // return $request->all();
            $msg = $request->msg;
            $user_id = $request->user_id;
            
            $myID = Auth::id();
            //check if conversation already started or not
            $checkCon1 = DB::table('conversations')->where('user_one', $myID)
            ->where('user_two',$user_id)->get(); // if loggedin user started conversation
            $checkCon2 = DB::table('conversations')->where('user_two', $myID)
            ->where('user_one',$user_id)->get(); // if loggedin recviced message first
            $allCons = array_merge($checkCon1->toArray(),$checkCon2->toArray());
            
            if(count($allCons) != 0){
                // old conversation
                $conID_old = $allCons[0]->id;
                //insert data into messages table
                $MsgSent = DB::table('messages')->insert([
                    'from_user' => $myID,
                    'to_user' => $user_id,
                    'conversation_id' =>  $conID_old,
                    'body' => $msg,
                    'status' => false,
                    'created_at' => Carbon::now()
                    ]);
                    
                }else {
                    // new conversation
                    $conID_new = DB::table('conversations')->insertGetId([
                        'user_one' =>  $myID,
                        'user_two' => $user_id,
                        ]);
                        // echo $conID_new;
                        $MsgSent = DB::table('messages')->insert([
                            'from_user' => $myID,
                            'to_user' => $user_id,
                            'conversation_id' =>  $conID_new,
                            'body' => $msg,
                            'status' => false,
                            'created_at' => Carbon::now()
                            ]);
                        }
                    }
                    
                    
                    
                    public function getLastMessage($conID)
                    {
                        // check if conv id exists
                        $conversation = DB::table('conversations')->where('id', $conID)->first();
                        
                        if ($conversation) {
                            $messages = DB::table('messages')
                            ->join('users', 'users.id','messages.from_user')
                            ->where('messages.conversation_id', $conID)
                            ->orderBy('messages.id', 'desc')
                            ->get();
                            // dd( $messages);
                            return $messages;
                        }
                        
                        
                    }
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                }
                