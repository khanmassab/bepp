@extends('layouts1.app')

@section('content')
    @include('layouts1.navbars.auth.topnav', ['title' => 'Useful Links - News'])
    <div class="row mt-4 mx-4">
        <div class="col-12">

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between">
                    <h6>{{__('Useful Links - Trust A Trader')}}</h6>
                    {{-- <a href="{{ URL::to('news/create') }}" class="btn btn-primary ml-auto" style="">{{__('Create')}}</a> --}}
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
                                @if(!empty($providers) && $providers->count())

                                    @foreach ($providers as $provider)

                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="mb-0 text-sm">{{$provider->title}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="text-center mb-0 text-sm" style="width: 296px; overflow: hidden; white-space: nowrap;text-overflow: ellipsis;">{{$provider->news_details}}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="align-middle text-center text-sm">
                                                <div class="d-flex px-3 py-1">
                                                    <div class="text-center d-flex flex-column justify-content-center">
                                                        <img style="height: 40px" src="{{$provider->image}}" alt="">
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="text-center mb-0 text-sm" style="width: 296px; overflow: hidden; white-space: nowrap;text-overflow: ellipsis;">{{$provider->image_title}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-end">
                                                <a  href="{{ URL::to('trader/' . $provider->id . '/edit') }}" class="btn btn-info text-sm font-weight-bold mb-0">{{__('View')}}</a>
                                                <a  href="{{ URL::to('trader/' . $provider->id . '/edit') }}" class="btn btn-info text-sm font-weight-bold mb-0">{{__('Mark as Trusted')}}</a>
                                                {{-- <div class="col">
                                                    <form method="PUT" action="{{ URL::to('trader/' . $provider->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-danger text-sm font-weight-bold mb-0">
                                                            {{__('Mark as Trusted')}}
                                                        </button>
                                                    </form>
                                                </div> --}}
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
