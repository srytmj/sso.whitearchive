<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family:sans-serif;color:#111;max-width:560px;margin:0 auto;padding:40px 20px">
    <h2 style="font-size:20px;margin-bottom:8px">{{ __('emails.invitation_title') }}</h2>
    <p style="color:#555;font-size:14px;line-height:1.6">
        {!! __('emails.invitation_body', ['role' => '<strong>' . e($invitation->role->name) . '</strong>']) !!}
    </p>
    <p style="color:#555;font-size:14px;line-height:1.6">
        {!! __('emails.invitation_instruction', ['hours' => '<strong>24</strong>']) !!}
    </p>
    <a href="{{ url('/register/invite?token=' . $invitation->token) }}"
       style="display:inline-block;margin:16px 0;background:#2563eb;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-size:14px;font-weight:500">
        {{ __('emails.activate_button') }}
    </a>
    <p style="color:#999;font-size:12px;margin-top:24px">
        {{ __('emails.invitation_footer') }}
    </p>
</body>
</html>
