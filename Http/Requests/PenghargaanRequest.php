<?php

namespace Modules\Penghargaan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenghargaanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'label' => 'required',
                    'konten' => 'required',
                    'icon' => 'required|mimes:png,jpg,jpeg'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'label' => 'required',
                    'konten' => 'required',
                    'icon' => 'mimes:png,jpg,jpeg'
                ];
            }
            default:break;
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
