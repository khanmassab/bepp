@extends('layouts1.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts1.navbars.auth.topnav', ['title' => 'Update Artical'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0"> </p>
                        </div>
                    </div>
                    <form  id="myForm" action="{{route('update.question',encrypt($question->id))}}" method="POST" enctype="multipart/form-data">
                        @csrf
                      
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('Question')}}</label>
                                        <input class="form-control" type="text" name="question" value="{{$question->question}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('Question Detail')}}</label>
                                        <input class="form-control" type="text" name="question_detail" value="{{$question->question_detail}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('media')}}</label>
                                        <input class="form-control" type="file" accept="image/*" name="media" value="{{$question->media}}">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label"  >{{__('answer')}}</label>
                                        <input class="form-control" type="text" name="answer" value="{{$question->answer}}">
                                        
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
