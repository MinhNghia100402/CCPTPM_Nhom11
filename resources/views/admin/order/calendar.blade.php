@extends('admin.extend')
@section('admin_content')
<main id="main" class="main">
    {{-- Tiêu đề --}}
    <div class="pagetitle">
        <h1>Quản lý đặt sân</h1>
    </div>
    {{ Breadcrumbs::render('orderCalendar') }}
    {{-- Body --}}
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2">
                        <div id="external-events" class="mt-3">
                            <p>
                                <strong>Kéo các sân bên dưới vào lịch để đặt sân</strong>
                            </p>
                              
                              @foreach ($footballPitches as $item)
                                <div data-football_pitch_id="{{ $item->id }}" class='mb-1 p-1 fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event'>
                                    <div class='fc-event-main'>{{ $item->name . ' - ' .  $item->pitchType->quantity }} người</div>
                                </div>
                              @endforeach
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div id='calendar-container'>
                            <div id='calendar'></div>
                          </div>
                    </div> <!-- end col -->
                </div>  <!-- end row -->
            </div> <!-- end card body-->
        </div>
    </div>
    @include('admin.modal.order.updateOrderCalendar')
</main>
@endsection