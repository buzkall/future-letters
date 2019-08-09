@extends('future-letters::app')

@section('content')
    <div class="row">
        <div class="col-lg-7 mb-3">

            @include('future-letters::flash-message')
            @auth
                <h2>Hi {{ '@'.Auth::user()->name }}, send a letter to your future self</h2>
            @endauth
            @guest
                <h2>Hi, send a letter to your future self</h2>
            @endguest
            <form action="/future-letters" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" required class="form-control" name="email" id="email"
                           value="{{ old('email') ?: Auth::check() ? Auth::user()->email : '' }}"
                           aria-describedby="helpId" placeholder="Email">
                    <small id="helpId" class="form-text text-muted">You will need to confirm you can access this email</small>
                </div>
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" required class="form-control" name="subject" id="subject"
                           value="{{ old('subject') }}"
                           aria-describedby="helpId" placeholder="Subject">
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea required class="form-control" name="message" id="message" rows="5"
                              placeholder="What would you tell to your future self?">{{ old('message') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="sending_date">Sending date:</label>
                    <div class='input-group date' id='datetimepicker'>
                        <input type="text" name="sending_date" id="sending_date" class="form-control"
                               placeholder="When do you want to receive it?"
                               aria-label="Username" aria-describedby="calendar">
                        <span class="input-group-addon input-group-append">
                            <span class="input-group-text" id="calendar"><i class="fa fa-calendar"></i></span>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-envelope" aria-hidden="true"></i> Submit
                </button>
            </form>
        </div>

        <div class="col-lg-4 offset-1">
            @include('future-letters::lateral')
        </div>
    </div>
@endsection

@section('scripts_footer')
    <script type="text/javascript">
        $(document).ready(function () {
            // default sending date: 30 days from now
            $('#datetimepicker').datetimepicker({
                locale: 'es',
                useCurrent: false, // needed so minDate won't override defaultDate
                defaultDate: moment().add(30, 'days'),
                minDate: moment().add(1, 'days'),
                ignoreReadonly: false
            });
        });
    </script>
@endsection
