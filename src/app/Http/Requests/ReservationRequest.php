<?php

namespace App\Http\Requests;

use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => ['required', 'after:yesterday'],
            'time_id' => ['required', function ($attribute, $value, $fail) {
                $selectedDate = Carbon::parse($this->input('date'));
                $currentTime = Carbon::now()->format('H:i');
                // 日付が翌日以降であれば、time_idのバリデーションは緩和
                if ($selectedDate->isToday()) {
                    // 今日の場合、現在時刻より後の時刻を選択
                    $availableTimes = Time::where('time', '>', $currentTime)->pluck('id')->toArray();
                    if (!in_array($value, $availableTimes)) {
                        $fail('※現在以降の時間を選択してください。');
                    }
                } else {
                    // 翌日以降の場合は、time_idのバリデーションをスキップする
                }
            }],
            'number_id' => ['required'],

        ];
    }

    public function messages()
    {
        return [
            'date.required' => '※日付を選択してください。',
            'date.after' => '※日付は本日以降の日付を選択してください。',
            'time_id.required' => '※時間を選択してください。',
            'number_id.required' => '※人数を選択してください。',
        ];
    }
}
