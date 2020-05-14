<?php

namespace glorifiedking\BusTravel;

class ToastNotification
{
      

    public static function toast($message='Process Completed',$title='Info',$type='success')  
    {       
        
        return [
            'bustravel-flash'         => true,
            'bustravel-flash-type'    => $type,
            'bustravel-flash-title'   => $title,
            'bustravel-flash-message' => $message,

        ];
    }  
}