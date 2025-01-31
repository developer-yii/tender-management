<?php
namespace App\Traits;

trait HasFilePath
{
    private function getFilePath($user)
    {
        $user->profile_photo_url = $user->profile_photo
            ? $user->getProfilePicUrl()
            : null;

        $user->cv_url = $user->cv
            ? $user->getCvUrl()
            : null;

        $user->document_url = $user->document
            ? $user->getDocumentUrl()
            : null;
    }
}
