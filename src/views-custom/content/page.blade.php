@extends('layouts/master')
@include('helpers.meta')

@section('css')
  @include('helpers.page-css',['page'=>$page])
@endsection

@section('banner')
  <div class="top-banner">
    <h1 class="center">{{ strtoupper($page->name) }}</h1>
  </div>
@endsection

@section('content')

  @if(count($nodes)>0)
    <div class="content-page">
      @foreach($nodes as $node)
        <div class="content-segment content-{{ $node['node']->name }} page-{{ $node['node']->pivot->id }}">
          @if($node['node']->type=='form'&&($node['node']->name=='postulation-a'||$node['node']->name=='postulation-b'))
            @include('segments.form-postulation', $node['subarray'])
          @elseif($node['node']->type=='form')
            @if($node['node']->deadline&&$node['node']->deadline->deadline<date('Y-m-d'))
              <div class="container center"><h4>{{ $node['node']->deadline->expired_message }}</h4></div>
            @else
              @include('segments.form', $node['subarray'])
            @endif
          @else
            @include('segments.'.$node['node']->name, $node['subarray'])
          @endif
        </div>
      @endforeach
    </div>
  @endif
@endsection
@section('script')
  @include('helpers.page-script',['page'=>$page])
@endsection