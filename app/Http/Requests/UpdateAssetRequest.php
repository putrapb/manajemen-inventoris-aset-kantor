<?php

namespace App\Http\Requests;

use App\Models\Asset;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $asset = $this->route('asset');

        return [
            'nama' => ['required', 'string', 'max:150'],
            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique(Asset::class, 'kode')->ignore($asset instanceof Asset ? $asset->id : $asset),
            ],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'lokasi' => ['required', 'string', 'max:150'],
            'status' => ['required', Rule::in(Asset::STATUSES)],
            'tanggal_beli' => ['required', 'date'],
            'nilai' => ['required', 'numeric', 'min:0'],
        ];
    }
}
