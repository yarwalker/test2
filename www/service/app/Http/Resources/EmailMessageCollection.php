<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use \Carbon\Carbon;

class EmailMessageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd(Carbon::createFromFormat($format, $time))
        return [
            'data' => $this->collection->map(fn($item) => [
                'name' => $item->name,
                'email' => $item->email/*,
                'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->toDateTimeString()*/
            ]),
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
