<?php

namespace App\Http\Requests\api\v1\additions;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdditionCategoryRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {

        $this->merge(['user_id' => auth()->id()]);
        return [
            'name' => 'required',
            'display_name' => 'required',
            'check_type' => 'required|in:multiple,individually',
            'is_required' => 'required|boolean',
            'user_id' => 'required',
        ];
    }



    public function messages()
    {
        return [
            'is_required.required' => 'The is required field is required.',
            'is_required.boolean' => 'The is required field must be a boolean value. (0 OR 1)',
            'check_type.in' => 'The check type field must in (multiple,individually)',
        ];
    }

    /*
            $table->string('name')->nullable();
            $table->string('display_name')->nullable();
            $table->enum('check_type' , ['multiple' , 'individually']);
            $table->boolean('is_required')->default(true);
     * */
}
