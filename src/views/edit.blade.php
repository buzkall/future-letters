@extends('future-letters::app')

@section('content')
    <div class="row">
        <div class="col-lg-8">

            @include('future-letters::flash-message')

            <h2>Edit your future self</h2>
            <form action="/future-letters/{{$future_letter->id}}" method="POST">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" required class="form-control" name="email" id="email"
                           value="{{ old('email') ?: $future_letter->email }}"
                           aria-describedby="helpId" placeholder="Email">
                    <small id="helpId" class="form-text text-muted">You will need to confirm you can access this email</small>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" required class="form-control" name="subject" id="subject"
                           value="{{ old('subject') ?: $future_letter->subject }}"
                           aria-describedby="helpId" placeholder="Subject">
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea required class="form-control" name="message" id="message" rows="3"
                    placeholder="What would you tell to your future self?">{{ old('message') ?: $future_letter->message }}</textarea>
                </div>

                <div class="form-group">
                    <label for="sending_date">Sending date</label>
                    <div class='input-group date' id='datetimepicker'>
                        <input type="text" name="sending_date" id="sending_date" class="form-control"
                               placeholder="When do you wan't to receive it?"
                               aria-label="Username" aria-describedby="calendar">
                        <span class="input-group-addon input-group-append">
                            <span class="input-group-text" id="calendar"><i class="fa fa-calendar"></i></span>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

        <div class="col-lg-4">
            @include('future-letters::lateral')
        </div>
    </div>
@endsection


@section('scripts_footer')
    <script type="text/javascript">

        $(document).ready(function () {
            $('#datetimepicker').datetimepicker({
                locale: 'es',
                defaultDate: '{!! $future_letter->sending_date  !!}',
                ignoreReadonly: false
            });
        });
    </script>
@endsection
