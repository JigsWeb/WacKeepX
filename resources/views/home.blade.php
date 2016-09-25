<!DOCTYPE html>
<html>
<head>
    <title>WacKeep</title>

    <base href="{{url('/')}}" />

    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" type="text/css">

    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('bower_components/isotope/dist/isotope.pkgd.js') }}" type="text/javascript"></script>
    <script src="{{ asset('bower_components/isotope-fit-columns/fit-columns.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/global.js') }}" type="text/javascript"></script>
</head>
<body id="home">
    <nav>
        <div class="container">
            <div class="logo">
                <i class="fi flaticon-compose"></i> WacKeep
            </div>

            <ul>
                <li><a href="/">Acceuil</a></li>
            </ul>

            <div class="user-info">
                <a href="#" class="account-link">{{ Auth::user()->email }}</a> <a href="/auth/logout" class="logout-link"><i class="fi flaticon-multiply"></i></a>
            </div>
        </div>
    </nav>

    <form method="POST" class="new-note white" action="/note">
        {{ csrf_field() }}
        <input type="hidden" name="color" value="white" />
        <input type="text" name="title" placeholder="Titre">
        <textarea name="content" rows="1" placeholder="Créer une note..."></textarea>
        <ul class="checkboxes"></ul>
        <div class="toolbar">
            <div class="colors">
                <ul class="choice">
                    <li class="white checked" data-color="white"></li>
                    <li class="red" data-color="red"></li>
                    <li class="blue" data-color="blue"></li>
                    <li class="turquoise" data-color="turquoise"></li>
                    <li class="grey" data-color="grey"></li>
                    <li class="yellow" data-color="yellow"></li>
                    <li class="orange" data-color="orange"></li>
                    <li class="green" data-color="green"></li>
                </ul>
            </div>
            <div class="fi flaticon-list add-checkbox">
                <span class="popover">Ajouter une checkbox</span>
            </div>
            <button type="submit">OK</button>
        </div>
    </form>

    <div class="notes-container">
        @foreach ($notes as $note)
            <form action="/note/{{ $note->id }}" class="note {{ $note->color }}" data-id="{{ $note->id }}">
                <a href="#" class="fi flaticon-multiply close"></a>
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT" />
                <input type="hidden" name="color" value="{{ $note->color }}" />
                <input name="title" class="title" value="{{ $note->title }}">
                <textarea name="content" class="content">{{ $note->content }}</textarea>
                <ul class="checkboxes">
                    @foreach($note->checkboxes as $checkbox)
                        <li>
                            <input type="hidden" name="checkboxes_id[]" value="{{ $checkbox->id }}">
                            <input type="hidden" name="checkboxes_checked[]" value="{{ $checkbox->checked }}">
                            <span class="false-checkbox @if($checkbox->checked) checked @endif"></span>
                            <input type="text" name="checkboxes_value[]" placeholder="Element de liste" value="{{ $checkbox->content }}">
                            <i class="fi flaticon-substract remove-checkbox"></i>
                        </li>
                    @endforeach
                </ul>
                <div class="fi flaticon-list add-checkbox">
                    <span class="popover">Ajouter une checkbox</span>
                </div>
                <div class="colors">
                    <ul class="choice">
                        @foreach(['white','red','blue','turquoise','grey','yellow','orange','green'] as $color)
                            <li class="{{ $color }} @if($color == $note->color) checked @endif" data-color="{{  $color }}"></li>
                        @endforeach
                    </ul>
                </div>
                <button type="submit">OK</button>
            </form>
        @endforeach
    </div>

    <div class="alert-container"></div>
</body>
</html>
