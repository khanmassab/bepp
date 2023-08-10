@extends('layouts1.app')

@section('content')
    @include('layouts1.navbars.auth.topnav', ['title' => 'Question Answer'])
    <div class="row mt-4 mx-4">
        <div class="col-12">

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between">
                    <h6>{{__('Question Answer')}}</h6>
                    <a href="{{route('create.question')}}" class="btn btn-primary ml-auto" style="">{{__('Create')}}</a>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Question')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Question Detail')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('answer')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('media')}}</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($questions) && $questions->count())

                                    @foreach ($questions as $question)

                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="mb-0 text-sm">{{$question->question}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="mb-0 text-sm" style="width: 296px; overflow: hidden; white-space: nowrap;text-overflow: ellipsis;">{{$question->question_detail}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="align-middle text-center text-sm">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="mb-0 text-sm" style="width: 296px; overflow: hidden; white-space: nowrap;text-overflow: ellipsis;">{{$question->answer}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <img style="height: 40px" src="{{$question->media}}" alt="">
                                            </td>

                                            <td class="align-middle text-end">
                                              <a  href="{{route('edit.question', encrypt($question->id))}}" class="text-sm font-weight-bold mb-0">{{__('edit')}}</a>
                                              <a  href="{{route('destroy.question', encrypt($question->id))}}" class="text-sm font-weight-bold mb-0">{{__('Delete')}}</a>
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
                            {!! $questions->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
