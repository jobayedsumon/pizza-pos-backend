<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    // 0 : Command ran successfully.
    // 1 : Invalid argument or option was passed to the command.
    // 2 : Command failed due to a runtime exception.

    function run_artisan_command(string $command)
    {
        $exitCode = Artisan::call($command);
        if($exitCode == 0){
            $message_title = 'success';
            $message_description = $command.' Successful.';
            return response()->json([$message_title,$message_description],200);
        } elseif($exitCode == 1){
            $message_title = 'error';
            $message_description = 'Invalid argument or option was passed to the command.';
            return response()->json([$message_title,$message_description],400);
        } elseif($exitCode == 2){
            $message_title = 'error';
            $message_description = 'Command failed due to a runtime exception.';
            return response()->json([$message_title,$message_description],500);
        } else {
            $message_title = 'error';
            $message_description = 'Unknown';
            return response()->json([$message_title,$message_description],500);
        }
    }

}
