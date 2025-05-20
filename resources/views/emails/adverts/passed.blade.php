<x-mail::message>
    # Hello!

    Your advert has successfully passed moderation.

{{--    <x-mail::button :url="''">--}}
        View Advert
{{--    </x-mail::button>--}}

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
