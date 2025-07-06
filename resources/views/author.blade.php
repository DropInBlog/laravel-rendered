@extends(config('dropinblog.layout'))

@push('dropinblog-head')
    {!! $headHtml !!}
@endpush

@section(config('dropinblog.sections.content'))
    {!! $bodyHtml !!}
@endsection
