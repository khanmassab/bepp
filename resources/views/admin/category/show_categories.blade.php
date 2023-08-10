@extends('layouts1.app')

@section('content')
    @include('layouts1.navbars.auth.topnav', ['title' => 'User Management'])
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
                    <h6>{{__('Categories')}}</h6>
                    <a href="{{route('create.category')}}" class="btn btn-primary ml-auto" style="">create</a>
                </div>
               
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Category Title')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Image')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Status')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Type')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($categories) && $categories->count())
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{$category->title}}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <img style="height: 40px" src="{{asset($category->image)}}" alt="">
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-sm font-weight-bold mb-0">{{$category->status == 1 ? 'Popular' : 'Unpopular'}}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-sm font-weight-bold mb-0">{{$category->category_type}}</p>
                                            </td>
                                            <td class="align-middle text-end">
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                    <a  href="{{route('edit.category',encrypt($category->id))}}" class="text-sm font-weight-bold mb-0">{{__('edit')}}</a>
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
                        {!! $categories->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
