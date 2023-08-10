@extends('layouts1.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts1.navbars.auth.topnav', ['title' => 'Update Advertisment'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">{{__('Update Advertisement')}} </p>
                        </div>
                    </div>
                    <form action="{{route('update.advertisement',$advertisment->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                      
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('Title')}}</label>
                                        <input class="form-control" type="text" name="title" value="{{$advertisment->title}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('Media')}}</label>
                                        <input class="form-control" type="file" accept="video/mp4,video/x-m4v,video/*,audio/mp3,audio/*" name="media" value="{{$advertisment->media}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('Video Link')}}</label>
                                        <input class="form-control" type="url" name="video_link" value="{{$advertisment->video_link}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('Description')}}</label>
                                        <input class="form-control" type="text" name="description" value="{{$advertisment->description}}">
                                    </div>
                                </div>
                            </div>
                           <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                     <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                  
                </div>
            </div>
          
        </div>
        @include('layouts1.footers.auth.footer')
    </div>
@endsection
