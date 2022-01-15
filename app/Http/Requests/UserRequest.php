<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'min:3'],
            'username' => ['required', Rule::unique((new User)->getTable())->ignore($this->route()->user->id ?? null)],
            'email' => ['required', 'email', Rule::unique((new User)->getTable())->ignore($this->route()->user->id ?? null)],
            'password' => [$this->route()->user ? 'nullable' : 'required', 'min:8'],
            'role' => ['nullable', 'in:Admin,Non-Admin']
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function validated()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'role' => $this->role??'Non-Admin'
        ];
    }
}
