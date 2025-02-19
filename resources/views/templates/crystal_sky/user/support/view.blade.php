@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    @if ($layout == 'frontend')
        <div class="py-120  container">
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card custom--card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="card-title mb-0 p-2">
                        @php echo $myTicket->statusBadge; @endphp
                        [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                    </h6>

                    @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                        <button class="btn btn--danger btn--sm confirmationBtn" data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}" type="button"><i class="la la-times-circle"></i>
                        </button>
                    @endif
                </div>

                <div class="card-body">

                    <form method="post" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-between">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea class="form--control" name="message" rows="4">{{ old('message') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <button type="button" class="btn btn-dark btn-sm addAttachment my-2"> <i class="fas fa-plus"></i> @lang('Add Attachment') </button>
                                <p class="mb-2"><span class="text--info">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                <div class="row fileUploadsContainer">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn--base w-100 my-2" type="submit"><i class="la la-fw la-lg la-reply"></i> @lang('Reply')
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <div class="card custom--card mt-4">
                <div class="card-body">
                    @forelse ($messages as $message)
                        @if ($message->admin_id == 0)
                            <div class="row border-primary my-3 mx-2 border py-3">
                                <div class="col-md-3 border-end text-end">
                                    <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                </div>
                                <div class="col-md-9">
                                    <p class="fw-bold my-3">
                                        @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                    <p>{{ $message->message }}</p>
                                    @if ($message->attachments->count() > 0)
                                        <div class="mt-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a class="me-3" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="row border--warning my-3 mx-2 border py-3">
                                <div class="col-md-3 border-end text-end">
                                    <h5 class="my-3">{{ $message->admin->name }}</h5>
                                    <p class="lead ">@lang('Staff')</p>
                                </div>
                                <div class="col-md-9">
                                    <p class="fw-bold my-3">
                                        @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                    <p>{{ $message->message }}</p>
                                    @if ($message->attachments->count() > 0)
                                        <div class="mt-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a class="me-3" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="empty-message text-center">
                            <img src="{{ asset('assets/images/empty_list.png') }}" alt="empty">
                            <h5 class="text-muted">@lang('No replies found here!')</h5>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
    @if ($layout == 'frontend')
        </div>
    @endif

@endsection
@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }
        .btn[type=submit] {
            height: unset !important;
        }

        .btn {
            padding: 12px 1.875rem;
        }
    </style>
@endpush


@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled', true)
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-lg-4 col-md-12 removeFileInput">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control form--control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text removeFile text-danger"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                `)
            });
            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled', true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });

            $("#confirmationModal").find('.btn--primary').removeClass('btn--primary').addClass('btn--base');
            $("#confirmationModal").find('.modal-header button[data-bs-dismiss="modal"]').remove();
        })(jQuery);
    </script>
@endpush

@push('bottom-menu')
    <div class="col-12 order-lg-3 order-4">
        <div class="d-flex nav-buttons flex-align gap-md-3 gap-2">
            <a href="{{ route('ticket.index') }}" class="btn btn--base active">@lang('My Tickets')</a>
            <a href="{{ route('ticket.open') }}" class="btn btn-outline--base">@lang('Open New Ticket')</a>
        </div>
    </div>
@endpush

@push('modal')
    <x-confirmation-modal />
@endpush

@push('style')
    <style>
        .btn[type=submit],
        .h-45 {
            height: unset;
        }

        .empty-message img {
            width: 120px;
            margin-bottom: 15px;
        }
    </style>
@endpush
