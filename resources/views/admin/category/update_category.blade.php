@extends('layouts1.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts1.navbars.auth.topnav', ['title' => 'Verify Account'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0"> {{$category->title}} </p>
                        </div>
                    </div>
                    <form action="{{route('update.category',$category->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                      
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('Category Title')}}</label>
                                        <input class="form-control" type="text" name="title" value="{{$category->title}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('image')}}</label>
                                        <input class="form-control" type="file" name="image" value="{{$category->image}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">{{__('Status')}}</label>
                                        <select class="form-select" aria-label="Default select example" name="status">
                                            <option value="0" {{$category->status ==0 ? 'selected':''}}>{{__('Remove From Popular')}}</option>
                                            <option value="1" {{$category->status ==1 ? 'selected':''}}>{{__('Popular')}}</option>
                                          </select>
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
