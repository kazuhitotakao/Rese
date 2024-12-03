<?php

namespace App\Http\Requests;

use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

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
            'time' => ['required', function ($attribute, $value, $fail) {
                $selectedDate = Carbon::parse($this->input('date'));
                $currentTime = Carbon::now()->format('H:i');
                $start = strtotime('10:00');
                $end = strtotime('23:59');
                $times = [];
                $shop_id = $this->input('shop_id');
                $interval = Shop::with('genre', 'area')->where('id', $shop_id)->first()->interval;

                for ($time = $start; $time <= $end; $time += $interval * 60) {
                    $times[] = date('H:i', $time);
                }

                if ($selectedDate->isToday()) {
                    // 今日の場合、現在時刻より後の時刻を選択
                    $availableTimes = array_filter($times, function ($time) use ($currentTime) {
                        return $time > $currentTime;
                    });
                    if (!in_array($value, $availableTimes)) {
                        $fail('※現在以降の時間を選択してください。');
                    }
                } else {
                    // 翌日以降の場合は、timeのバリデーションをスキップする
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
            'time.required' => '※時間を選択してください。',
            'number_id.required' => '※人数を選択してください。',
        ];
    }
}
