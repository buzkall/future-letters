<h3>Saved future letters</h3>
@if(count($future_letters) == 0)
    @auth
        <p>No future letters yet!</p>
    @endauth
    @guest
        <p>Register or login to view your other future letters</p>
    @endguest
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
                    <form class="delete" action="{{url('future-letters', [$future_letter->id])}}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-link submit p-0 pl-3">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
@endif

@section('scripts_footer_include')
    <script type='text/javascript'>
        $(document).ready(function () {
            $('.delete').on('submit', function () {
                return confirm('Are you sure?');
            });
        });
    </script>
@endsection
