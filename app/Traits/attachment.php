<?php
namespace App\Traits;
trait attachment{
    function save($file,$folderPath){
        // $attach_file_extension = $file->getClientOriginalExtension();
        $original_name = $file->getClientOriginalName();
        $attach_file_name = time().$original_name;
        $avatar_path = $folderPath;
        $file->move($avatar_path,$attach_file_name);
        return $attach_file_name;
    }
}
?>