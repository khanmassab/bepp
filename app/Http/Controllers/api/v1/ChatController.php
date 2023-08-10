<?php

namespace App\Http\Controllers\api\v1;

use App\Constant;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {

        $validatedData = Validator::make($request->all(), [
            'reciever_id' => 'required',
            'message' => 'required',
        ]);

        if($validatedData->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validatedData->errors()->first(),
            ],422);
        }
        // dd($request->all());
        $user = auth()->user();
        $reciever=User::where('id',$request->reciever_id)->first();

        if($reciever)
        {
           $chat= Chat::create([
                'sender_id' => $user->id,
                'receiver_id' => $request->reciever_id,
                'message' => $request->message,
            ]);

            return response()->json([
                'code' => 200,
                'message' => 'succefully sended',
                'data' => $chat,
            ]);
        }
        else
        {
            return response()->json([
                'code' => 400,
                'error' => 'User not exists',

            ],400);
        }

      }

      public function getChatList()
      {
            try{
                $user =auth()->user();
                $chat_list = Chat::where('receiver_id',$user->id)->select('id', 'sender_id', 'message')
                ->whereIn('id', function ($query) {
                    $query->selectRaw('MAX(id)')
                        ->from('chats')
                        ->groupBy('sender_id');
                })
                ->latest()->get();
                if($user->role_id == Constant::Provider)
                {
                    $chat_list->load('userlist.customerInfo');
                }
                elseif($user->role_id == Constant::Customer)
                {
                    $chat_list->load('userlist.providerInfo');
                }
                return response()->json([
                    'code' => 200,
                    'message' => 'Succefully fetched',
                    'data' => $chat_list,
                ],200);
            }
            catch(\Throwable $th){
                return response()->json([ 'code' => 500,  'error' => 'Internal Error',],500);
            }
        }

        public function getMessages(Request $request)
        {

            $validatedData = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);

            if($validatedData->fails()) {
                return response()->json([
                    'code' => 422,
                    'error' => $validatedData->errors()->first(),
                ],422);
            }
            $id = $request->user_id;
            $user = auth()->user();
            Chat::where('sender_id',$id)->where('receiver_id', $user->id)->update(['seen' => true]);
              $messages = Chat::where(function ($q) use ($id,$user) {
                    $q->where('sender_id', $user->id);
                    $q->where('receiver_id', $id);
                })->orWhere(function ($q) use ($id,$user) {
                    $q->where('sender_id', $id)->with('chatlist');
                    $q->where('receiver_id', $user->id);
                })->get();

        return response()->json([
            'code' => 200,
            'message' => 'Succefully fetched',
            'data' => $messages,
          ],200);
        }
    }
