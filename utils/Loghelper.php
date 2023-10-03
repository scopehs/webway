<?php

namespace utils\Loghelper;

use App\Models\ActivityLog;

class Loghelper
{
    public static function logadd($nameID, $descriptionID, $subjectTypeID, $subjectID, $causerTypeID, $causerID)
    {
        // $causerType = "";
        // if ($causerTypeID == 1) {
        //     $causerType = "App\Models\User";
        // }
        // ActivityLog::create([
        //     'name_id' => $nameID,
        //     'description_id' => $descriptionID,
        //     'subject_type' => $subjectTypeID,
        //     'subject_id' => $subjectID,
        //     'causer_type' => $causerType,
        //     'causer_id' => $causerID
        // ]);
    }
}
