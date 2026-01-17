<?php

namespace Tests\Feature\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait IndexTrait
{

    protected $indexUndefinedKind = 0;

    public function index($indexKind){

        $response = $this->initialValue($this->initialValueUndefinedKind);

        return($response);
    }

}