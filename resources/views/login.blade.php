<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.auth.admin_login') }} - {{ __('messages.common.portal_name') }}</title>
    <link rel="stylesheet" href="/css/portal.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                <div>
                    <div style="font-size: 13px; font-weight: 700; color: #005FB8; letter-spacing: -0.3px; margin-bottom: 1rem;">{{ __('messages.common.portal_name') }}</div>
                    <div class="auth-title">{{ __('messages.auth.admin_login') }}</div>
                    <div class="auth-subtitle">{{ __('messages.auth.admin_subtitle') }}</div>
                </div>
                @include('partials.lang-switcher')
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">{{ __('messages.auth.email') }}</label>
                    <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">{{ __('messages.auth.password') }}</label>
                    <input class="form-control" type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 0.5rem;">
                    {{ __('messages.common.login') }}
                </button>
            </form>

            <hr class="divider">
            <div style="text-align: center; font-size: 13px; color: #6b7280;">
                <a href="/" class="link">{{ __('messages.auth.whistleblower_link') }}</a>
            </div>
        </div>
    </div>
</body>
</html>
