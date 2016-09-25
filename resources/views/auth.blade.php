<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <base href="{{url('/')}}" />

        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" type="text/css">
    </head>
    <body id="auth">
        <div class="side" id="signin">
            <h1 class="heading">Wac</h1>
            <div class="container">
                <div class="box">
                    <h2>Déjà membre ?</h2>
                    <form method="POST" action="/auth">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="action" value="signin" />
                        <table>
                            <tr>
                                <td><label for="email">Adresse e-mail</label></td>
                                <td><input type="email" id="email" name="email"/></td>
                            </tr>
                            <tr>
                                <td><label for="password">Mot de passe</label></td>
                                <td><input type="password" id="password" name="password"/></td>
                            </tr>
                        </table>
                        <button type="submit"/>Connexion</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="side" id="signup">
            <h1 class="heading">Keep</h1>
            <div class="container">
                <div class="box">
                    <h2>Devenez un keeper</h2>
                    <form method="POST" action="/auth">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="action" value="signup" />
                        <table>
                            <tr>
                                <td><label for="email">Adresse e-mail</label></td>
                                <td><input type="email" id="email" name="email" /></td>
                            </tr>
                            <tr>
                                <td><label for="password">Mot de passe</label></td>
                                <td><input type="password" id="password" name="password"/></td>
                            </tr>
                            <tr>
                                <td><label for="password_confirmation">Confirmation</label></td>
                                <td><input type="password" id="password" name="password_confirmation"/></td>
                            </tr>
                        </table>
                        <button type="submit"/>Inscription</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
