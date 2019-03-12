@extends('future-letters::app')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <h2>Send a letter to your future self</h2>
            <form action="future-letters" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" id="email" aria-describedby="helpId" placeholder="Email">
                    <small id="helpId" class="form-text text-muted">You will need to confirm you can access this email</small>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control" name="subject" id="subject" aria-describedby="helpId" placeholder="Subject">
                    {{--<small id="helpId" class="form-text text-muted">Enter subject</small>--}}
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea class="form-control" name="message" id="message" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="sending_date">Sending date</label>
                    <div class='input-group date' id='datetimepicker'>
                        <input type="text" name="sending_date" id="sending_date" class="form-control" placeholder="When do you wan't to receive it?" aria-label="Username" aria-describedby="calendar">
                        <span class="input-group-addon input-group-append">
                            <span class="input-group-text" id="calendar"><i class="fa fa-calendar"></i></span>
                        </span>
                    </div>
                </div>

            </form>
        </div>

        <div class="col-lg-4">
            <h3>Saved future letters</h3>
            <ul>
                @foreach($future_letters as $future_letter)
                    <li>{{$future_letter->subject}}</li>
                @endforeach
            </ul>
        </div>
    </div>

@endsection


@section('scripts_footer')

    <script type="text/javascript">
        $(document).ready(function () {
            $('#datetimepicker').datetimepicker({
                locale: 'es'
            });
        });
    </script>
@endsection
