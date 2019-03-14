<h3>Saved future letters</h3>
@if(count($future_letters) == 0)
    <p>No future letters yet!</p>
@else
    <ul>
        @foreach($future_letters as $future_letter)
            <li>
                <div class="pull-left">
                    <a href="/future-letters/{{$future_letter->id}}/edit">
                        {{ Str::limit($future_letter->subject, 10) }}:
                    </a>
                    {{$future_letter->sending_date->format('d/m/y')}}
                </div>
                <div class="d-inline">
                    <form action="{{url('future-letters', [$future_letter->id])}}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-link submit p-0 pl-3"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
@endif
