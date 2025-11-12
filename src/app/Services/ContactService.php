<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ContactRequest;

class ContactService
{
   /**
    * 問い合わせを保存するメソッド。
    * @param ContactRequest $request
    * @return Contact
    */
   public static function storeContact(ContactRequest $request): Contact
   {
      return Contact::create([
         'subject' => $request->subject,
         'message' => $request->message,
         'user_id' => Auth::id(),
      ]);
   }
}
