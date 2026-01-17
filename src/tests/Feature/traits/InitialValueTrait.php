<?php

namespace Tests\Feature\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

trait InitialValueTrait
{
    use RefreshDatabase;

    protected $initialValueUndefinedKind = 0;
    protected $initialValueOkKind = 1;

    public function initialValue($initialValueKind)
    {
        $this->seed(); // DatabaseSeederを実行してデータ投入

        $response = $this->get(route('index'));

        if($initialValueKind !== $this->initialValueUndefinedKind){
            $response->assertStatus(200);
        }//$initialValueKind

        return($response);
    }
}