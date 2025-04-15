<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customerReference' => 'required|string',
            'street' => 'required|string',
            'lgaName' => 'required|string',
            'stateName' => 'required|string',
            'landmark' => 'nullable|string',
            'city' => 'required|string',
            'applicant.firstname' => 'required|string',
            'applicant.lastname' => 'required|string',
            'applicant.phone' => 'required|string',
            'applicant.dob' => 'required|date',
            'applicant.idType' => 'required|string|in:nin',
            'applicant.idNumber' => 'required|string',
            'applicant.gender' => 'required|string|in:male,female',
        ];
    }
}
