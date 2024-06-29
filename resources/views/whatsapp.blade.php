<!DOCTYPE html>

<html>

<head>

    <title>How to Send WhatsApp SMS in Laravel? - ItSolutionStuff.com</title>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container">



        <div class="row">

            <div class="col-md-9">



                <div class="card">

                    <div class="card-header">

                        <h2>How to Send WhatsApp SMS in Laravel? - ItSolutionStuff.com</h2>

                    </div>

                    <div class="card-body">

                        <form method="POST" action="{{ route('whatsapp.post') }}">



                            {{ csrf_field() }}



                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-block">

                                    <strong>{{ $message }}</strong>

                                </div>
                            @endif

                            @if ($pesan = Session::get('pesan'))
                                {{$pesan}}
                            @endif

                            @if ($message = Session::get('error'))
                                <div class="alert alert-danger alert-block">

                                    <strong>{{ $message }}</strong>

                                </div>
                            @endif



                            <div class="mb-3">

                                <label class="form-label" for="inputName">Phone:</label>

                                <input type="text" name="phone" id="inputName"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Phone Number">



                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>



                            <div class="mb-3">

                                <label class="form-label" for="inputName">Message:</label>

                                <textarea name="message" id="inputName" class="form-control @error('message') is-invalid @enderror"
                                    placeholder="Enter Message"></textarea>



                                @error('message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>



                            <div class="mb-3">

                                <button class="btn btn-success btn-submit">Send Message</button>

                            </div>

                        </form>

                    </div>

                </div>



            </div>

        </div>





    </div>

</body>

</html>
