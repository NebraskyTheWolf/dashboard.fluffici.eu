<relative-time format="elapsed" datetime="{{ $date }}" title="{{ Carbon::parse($date) }}"> {{ Carbon::parse($date)->diffForHumans() }} </relative-time>
