<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class ImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array <mixed>
     */
    public function rules(): array
    {
        return [
            'import' => ['required', 'mimes:csv,txt']
        ];
    }

    public function getFileExtension(): string
    {
        /** @var UploadedFile $file */
        $file = $this->import;
        return $file->extension();
    }
}
