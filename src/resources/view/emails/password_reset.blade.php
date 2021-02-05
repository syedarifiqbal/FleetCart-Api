@section('title', "Password Reset Request " . env('app.name'))

@section('content')

    <!-- Action -->
    <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <p>Your Password Reset Token is mentioned below:</p>
                                        <h1>{{ $token->token }}</h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

@endsection
