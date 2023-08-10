@extends('layouts1.app')

@section('content')
    @include('layouts1.navbars.auth.topnav', ['title' => 'Useful Links - News'])
    <div class="row mt-4 mx-4">
        <div class="col-12">

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between">
                    <h6>{{__('Useful Links - News')}}</h6>
                    <a href="{{ URL::to('news/create') }}" class="btn btn-primary ml-auto" style="">{{__('Create')}}</a>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Title')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('News Detail')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Image')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Title')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($news) && $news->count())

                                    @foreach ($news as $news)

                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="mb-0 text-sm">{{$news->title}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="text-center mb-0 text-sm" style="width: 296px; overflow: hidden; white-space: nowrap;text-overflow: ellipsis;">{{$news->news_details}}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="align-middle text-center text-sm">
                                                <div class="d-flex px-3 py-1">
                                                    <div class="text-center d-flex flex-column justify-content-center">
                                                        {{-- <p class="text-center mb-0 text-sm" style="width: 296px; overflow: hidden; white-space: nowrap;text-overflow: ellipsis;">{{asset( 'storage/image/' . $news->image)}}</p> --}}
                                                        <img style="height: 40px" src="{{$news->image}}" alt="">
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="text-center mb-0 text-sm" style="width: 296px; overflow: hidden; white-space: nowrap;text-overflow: ellipsis;">{{$news->image_title}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-end">
                                              {{-- <div class="row"> --}}
                                                <div class="col">
                                                    <a  href="{{ URL::to('news/' . $news->id . '/edit') }}" class="btn btn-info text-sm font-weight-bold mb-0">{{__('Edit')}}</a>
                                                </div>
                                                <div class="col">
                                                    <form method="POST" action="{{ URL::to('news/' . $news->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger text-sm font-weight-bold mb-0">
                                                            {{__('Delete')}}
                                                        </button>
                                                    </form>
                                                </div>
                                              {{-- </div> --}}
                                            </td>
                                         </tr>
                                    @endforeach
                                    @else

                                    <tr>
                                        <td class="align-middle text-center text-sm"  colspan="10">
                                            <p class="text-sm font-weight-bold mb-0">{{__("No data available in this section")}}</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                            {{-- {!! $news->links() !!} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
