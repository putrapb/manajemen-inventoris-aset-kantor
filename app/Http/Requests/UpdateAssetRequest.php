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

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama' => is_string($this->nama) ? trim($this->nama) : $this->nama,
            'kode' => is_string($this->kode) ? strtoupper(trim($this->kode)) : $this->kode,
            'lokasi' => is_string($this->lokasi) ? trim($this->lokasi) : $this->lokasi,
        ]);
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

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama aset wajib diisi.',
            'nama.max' => 'Nama aset maksimal 150 karakter.',
            'kode.required' => 'Kode aset wajib diisi.',
            'kode.unique' => 'Kode aset sudah digunakan.',
            'kode.max' => 'Kode aset maksimal 50 karakter.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak ditemukan.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'lokasi.max' => 'Lokasi maksimal 150 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'tanggal_beli.required' => 'Tanggal beli wajib diisi.',
            'tanggal_beli.date' => 'Tanggal beli tidak valid.',
            'nilai.required' => 'Nilai aset wajib diisi.',
            'nilai.numeric' => 'Nilai aset harus berupa angka.',
            'nilai.min' => 'Nilai aset tidak boleh kurang dari 0.',
        ];
    }
}
