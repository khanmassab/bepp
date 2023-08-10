@extends('layouts1.app')

@section('content')
    @include('layouts1.navbars.auth.topnav', ['title' => 'Advertisment'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>	
                    <strong>{{ $message }}</strong>
            </div>
            @endif
            <div class="card mb-4">
             
                <div class="card-header d-flex justify-content-between">
                    <h6>{{__('Advertisement')}}</h6>
                    <a href="{{route('create.advertisement')}}" class="btn btn-primary ml-auto" style="">create</a>
                </div>
               
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Title')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Videos')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('videos Link')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Description')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($advertisements) && $advertisements->count())
                                    @foreach ($advertisements as $advertisement)

                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{$advertisement->title}}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <video id="example_video_1" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto"  width="200">
                                                     <source src="{{asset($advertisement->media)}}" type="video/mp4" />
                                                </video>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <h6>{{asset($advertisement->video_link)}}</h6>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <h6>{{asset($advertisement->description)}}</h6>
                                            </td>
                                            <td class="align-middle text-end">
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                    <a  href="{{route('destroy.advertisement',$advertisement->id)}}" class="text-sm font-weight-bold mb-0">Delete</a>
                                                </div>
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                    <a  href="{{route('edit.advertisement',encrypt($advertisement->id))}}" class="text-sm font-weight-bold mb-0">Edit</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                 
                                    <tr>
                                        <td class="align-middle text-center text-sm"  colspan="10">
                                            <p class="text-sm font-weight-bold mb-0">{{__("There are no data.")}}</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        {!! $advertisements->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
