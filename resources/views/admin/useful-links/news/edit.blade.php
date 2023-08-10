@extends('layouts1.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts1.navbars.auth.topnav', ['title' => 'Useful Links - Edit News'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">{{__('Edit News')}}  </p>
                        </div>
                    </div>
                    <form  id="myForm" action="{{route('news.update',encrypt($news->id))}}" method="PUT" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('Title')}}</label>
                                        <input class="form-control" type="text" name="question" value="{{$news->title}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('News Detail')}}</label>
                                        <input class="form-control" type="text" name="question_detail" value="{{$news->news_details}}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('Image')}}</label>
                                        <input class="form-control" type="file" accept="image/*" name="image" value="">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('Image Title')}}</label>
                                        <input class="form-control" type="text" name="image_title" value="">
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
