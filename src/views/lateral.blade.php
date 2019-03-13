<h3>Saved future letters</h3>
<ul>
    @foreach($future_letters as $future_letter)
        <li>
            <a href="/future-letters/{{$future_letter->id}}/edit">
                {{ Str::limit($future_letter->subject, 10) }}:
            </a>
            {{$future_letter->sending_date->format('d/m/y')}}
            <i class="fa fa-trash" aria-hidden="true"></i>
        </li>
    @endforeach
</ul>
