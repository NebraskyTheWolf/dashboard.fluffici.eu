@extends('layouts.app')

@section('title', $page->title)

@section('content')
    <div class="ui container page-content">
        <h1 class="ui header">
            <div class="content">
                {!! $page->content !!}
            </div>
        </h1>
        <div class="clearfix"></div>
    </div>
@endsection
@section('style')
    <style>
        h1.ui.header .content {
            padding-top: 10%;
            font-size: 2.8em;
            line-height: 1em;
        }
        h1.ui.header .content .sub.header {
            padding-top: 4%;
            text-align: center;
            font-style: italic;
            font-size: 0.3em;
        }
        .clearfix {
            content: "";
            display: table;
            clear: both;
        }
    </style>
@endsection